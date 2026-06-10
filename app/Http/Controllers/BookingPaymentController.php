<?php

namespace App\Http\Controllers;

use App\Mail\BookingPaidMail;
use App\Models\Booking;
use App\Models\Kamar;
use App\Models\PaymentGateway;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class BookingPaymentController extends Controller
{
    public function create(Request $request, Kamar $kamar)
    {
        $request->validate([
            'tanggal_masuk' => ['required', 'date'],
            'durasi' => ['required', 'integer', 'min:1'],
            'orang' => ['required', 'integer', 'min:1', 'max:2'],

            'customer_name' => ['required', 'string', 'max:150'],
            'customer_phone' => ['required', 'string', 'max:15'],
            'customer_email' => ['nullable', 'email', 'max:150'],
            'customer_address' => ['nullable', 'string', 'max:1000'],
            'customer_note' => ['nullable', 'string', 'max:1000'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Cegah booking dobel kalau masih ada invoice pending
        |--------------------------------------------------------------------------
        | Kalau user sudah pernah booking kamar ini tapi belum bayar, arahkan
        | ke invoice/metode pembayaran yang lama. User bisa lanjut bayar atau
        | cancel lewat Riwayat Pembayaran.
        */
		$pendingBooking = Booking::where('user_id', auth()->id())
		    ->where('payment_status', 'pending')
		    ->latest()
		    ->first();
		
		if ($pendingBooking) {
		    return redirect()
		        ->route('payment-history.index')
		        ->with('error', 'Kamu masih punya pembayaran yang belum selesai. Batalkan invoice lama terlebih dahulu sebelum booking kamar baru.');
		}

        /*
        |--------------------------------------------------------------------------
        | Cegah user punya lebih dari satu kamar aktif
        |--------------------------------------------------------------------------
        */
        $hasActiveRental = Booking::where('user_id', auth()->id())
            ->where('payment_status', 'paid')
            ->whereHas('kamar')
            ->get()
            ->contains(function ($booking) {
                $tanggalMasuk = $booking->tanggal_masuk
                    ? \Carbon\Carbon::parse($booking->tanggal_masuk)->startOfDay()
                    : null;

                if (! $tanggalMasuk) {
                    return false;
                }

                $tanggalHabis = $booking->tanggal_habis_custom
                    ? \Carbon\Carbon::parse($booking->tanggal_habis_custom)->startOfDay()
                    : $tanggalMasuk->copy()->addMonths((int) $booking->durasi);

                return $tanggalHabis->greaterThanOrEqualTo(now()->startOfDay());
            });

        if ($hasActiveRental) {
            return redirect()->route('my-rentals.index')
                ->with('error', 'Kamu masih memiliki kamar aktif. Satu akun hanya bisa menyewa satu kamar aktif.');
        }

        $gateway = PaymentGateway::where('is_active', true)->first();

        if (! $gateway) {
            return back()->with('error', 'Payment gateway belum diaktifkan oleh admin.');
        }

        $durasi = (int) $request->durasi;
        $orang = (int) $request->orang;

        $hargaPerBulan = $orang >= 2
            ? (int) ($kamar->harga_2_orang ?? $kamar->harga ?? 0)
            : (int) ($kamar->harga_1_orang ?? $kamar->harga ?? 0);

        $total = $hargaPerBulan * $durasi;
        $invoice = 'RK-' . time() . '-' . rand(100, 999);

        $booking = Booking::create([
            'invoice' => $invoice,
            'user_id' => auth()->id(),

            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'customer_email' => $request->customer_email,
            'customer_address' => $request->customer_address,
            'customer_note' => $request->customer_note,

            'kamar_id' => $kamar->id,
            'tanggal_masuk' => $request->tanggal_masuk,
            'durasi' => $durasi,
            'orang' => $orang,
            'total_harga' => $total,
            'payment_fee' => 0,
            'payment_total' => $total,
            'payment_gateway' => $gateway->code,
            'payment_status' => 'pending',
            'reference_id' => $invoice,
        ]);

        return redirect()->route('booking.invoice', $booking->invoice);
    }

    public function invoice($invoice)
    {
        $booking = Booking::with(['kamar', 'user'])
            ->where('invoice', $invoice)
            ->firstOrFail();

        if ((int) $booking->user_id !== (int) auth()->id()) {
            abort(403);
        }

        return view('booking.invoice', compact('booking'));
    }

    public function methods($invoice)
    {
        $booking = Booking::with('kamar')
            ->where('invoice', $invoice)
            ->firstOrFail();

        if ((int) $booking->user_id !== (int) auth()->id()) {
            abort(403);
        }

        if ($booking->payment_status === 'canceled') {
            return redirect()
                ->route('payment-history.index')
                ->with('error', 'Invoice ini sudah dibatalkan. Silakan booking ulang jika ingin menyewa kamar.');
        }

        if ($booking->payment_status === 'paid') {
            return redirect()->route('booking.pay', $booking->invoice);
        }

        $gateway = PaymentGateway::where('code', $booking->payment_gateway)
            ->where('is_active', true)
            ->firstOrFail();

        $methods = PaymentMethod::where('gateway_code', $gateway->code)
            ->where('is_active', true)
            ->orderByRaw("FIELD(category, 'qris', 'e-wallet', 'virtual-account', 'saldo', 'lainnya')")
            ->orderBy('name')
            ->get();

        return view('booking.methods', compact('booking', 'gateway', 'methods'));
    }

	public function chooseMethod(Request $request, $invoice)
	{
	    $request->validate([
	        'payment_method_id' => ['required', 'exists:payment_methods,id'],
	    ]);
	
	    $booking = Booking::where('invoice', $invoice)->firstOrFail();
	
	    if ((int) $booking->user_id !== (int) auth()->id()) {
	        abort(403);
	    }
	
	    if ($booking->payment_status === 'canceled') {
	        return redirect()
	            ->route('payment-history.index')
	            ->with('error', 'Invoice ini sudah dibatalkan.');
	    }
	
	    if ($booking->payment_status === 'paid') {
	        return redirect()->route('booking.pay', $booking->invoice);
	    }
	
	    $gateway = PaymentGateway::where('code', $booking->payment_gateway)
	        ->where('is_active', true)
	        ->firstOrFail();
	
	    $method = PaymentMethod::where('id', $request->payment_method_id)
	        ->where('gateway_code', $gateway->code)
	        ->where('is_active', true)
	        ->firstOrFail();
	
		$baseAmount = (int) ($booking->total_harga ?? 0) + (int) ($booking->late_fee ?? 0);
		
		$feePercent = (int) round($baseAmount * ((float) $method->fee_percent / 100));
		$fee = (int) $method->fee_fixed + $feePercent;
		$paymentTotal = $baseAmount + $fee;
		$methodCodeUpper = strtoupper($method->code ?? '');
		
		$isVaMethod = str_contains($methodCodeUpper, 'VA')
		    || str_contains($methodCodeUpper, 'BCAVA')
		    || str_contains($methodCodeUpper, 'BNIVA')
		    || str_contains($methodCodeUpper, 'BRIVA')
		    || str_contains($methodCodeUpper, 'MANDIRIVA')
		    || str_contains($methodCodeUpper, 'PERMATAVA')
		    || str_contains($methodCodeUpper, 'CIMBVA')
		    || str_contains($methodCodeUpper, 'DANAMONVA')
		    || str_contains($methodCodeUpper, 'BNCVA')
		    || str_contains($methodCodeUpper, 'BSIVA');
		
			if ($gateway->code === 'tokopay' && $isVaMethod && $paymentTotal < 10000) {
			    return redirect()
			        ->route('booking.methods', $booking->invoice)
			        ->with('error', 'Metode Virtual Account minimum pembayaran Rp 10.000. Silakan pilih QRIS/E-Wallet atau naikkan nominal pembayaran.');
			}
				
	    /*
	    |--------------------------------------------------------------------------
	    | Buat reference baru setiap ganti metode
	    |--------------------------------------------------------------------------
	    | Jangan pakai invoice yang sama terus ke TokoPay.
	    | Kalau pakai reff_id lama, TokoPay bisa balikin transaksi/metode lama.
	    */
	    $safeChannel = preg_replace('/[^A-Za-z0-9]/', '', (string) $method->code);
	    $paymentReference = $booking->invoice . '-' . strtoupper($safeChannel) . '-' . time() . random_int(100, 999);
	
	    /*
	    |--------------------------------------------------------------------------
	    | Reset data payment lama
	    |--------------------------------------------------------------------------
	    | Ini mencegah link DANA/QRIS/VA lama kebawa saat user ganti metode.
	    */
	    $booking->update([
	        'reference_id' => $paymentReference,
	
	        'payment_method_code' => $method->code,
	        'payment_method_name' => $method->name,
	        'payment_fee' => $fee,
	        'payment_total' => $paymentTotal,
	
	        'transaction_id' => null,
	        'payment_url' => null,
	        'qr_string' => null,
	    ]);
	
	    $booking->refresh();
	
	    if ($gateway->code === 'cashify') {
	        return $this->createCashifyPayment($booking, $gateway);
	    }
	
	    if ($gateway->code === 'tokopay') {
	        return $this->createTokoPayPayment($booking, $gateway);
	    }
	
	    return back()->with('error', 'Gateway tidak dikenali.');
	}

    private function createCashifyPayment(Booking $booking, PaymentGateway $gateway)
    {
        try {
            $response = Http::withHeaders([
                'x-license-key' => $gateway->cashify_license_key,
                'Content-Type' => 'application/json',
            ])->post('https://cashify.my.id/api/generate/v2/qris', [
                'qr_id' => $gateway->cashify_qr_id,
                'amount' => $booking->payment_total,
                'useUniqueCode' => true,
                'expiredInMinutes' => 1440,
                'qrType' => 'dynamic',
                'paymentMethod' => 'qris',
                'useQris' => true,
            ]);

            $result = $response->json();

            if (($result['status'] ?? null) != 200) {
                Log::error('Cashify error', [
                    'invoice' => $booking->invoice,
                    'response' => $result,
                ]);

                return back()->with('error', 'Gagal membuat pembayaran Cashify.');
            }

            $data = $result['data'] ?? [];

            $booking->update([
                'transaction_id' => $data['transactionId'] ?? null,
                'qr_string' => $data['qr_string'] ?? null,
                'payment_total' => $data['totalAmount'] ?? $booking->payment_total,
            ]);

            return redirect()->route('booking.pay', $booking->invoice);
        } catch (\Throwable $e) {
            Log::error('Cashify payment error: ' . $e->getMessage(), [
                'invoice' => $booking->invoice,
            ]);

            return back()->with('error', 'Terjadi kesalahan saat membuat pembayaran Cashify.');
        }
    }

	private function createTokoPayPayment(Booking $booking, PaymentGateway $gateway)
	{
	    try {
	        $merchantId = $gateway->tokopay_merchant_id;
	        $secretKey = $gateway->tokopay_secret_key;
	
	        $channel = $booking->payment_method_code ?: ($gateway->tokopay_channel ?: 'QRISREALTIME');
	        $reffId = $booking->reference_id ?: $booking->invoice;
	
	        $signature = md5($merchantId . ':' . $secretKey . ':' . $reffId);
	
	        $payload = [
	            'merchant_id' => $merchantId,
	            'kode_channel' => $channel,
	            'reff_id' => $reffId,
	            'amount' => $booking->payment_total,
	
	            'customer_name' => $booking->customer_name ?: (auth()->user()->name ?? 'Pelanggan RafaKost'),
	            'customer_email' => $booking->customer_email ?: (auth()->user()->email ?? 'customer@rafakost.biz.id'),
	            'customer_phone' => $booking->customer_phone ?: (auth()->user()->phone ?? '081234567890'),
	
	            'redirect_url' => route('booking.pay', $booking->invoice),
	            'expired_ts' => 0,
	            'signature' => $signature,
	
	            'items' => [
	                [
	                    'product_code' => 'KAMAR-' . $booking->kamar_id,
	                    'name' => 'Booking Kamar RafaKost - ' . ($booking->payment_method_name ?? 'Pembayaran'),
	                    'price' => $booking->payment_total,
	                    'product_url' => route('booking.pay', $booking->invoice),
	                    'image_url' => asset('images/logo.png'),
	                ],
	            ],
	        ];
	
	        $response = Http::asJson()->post('https://api.tokopay.id/v1/order', $payload);
	        $result = $response->json();
	
	        Log::info('TOKOPAY CREATE RESPONSE', [
	            'invoice' => $booking->invoice,
	            'reference_id' => $reffId,
	            'channel' => $channel,
	            'payload' => $payload,
	            'response' => $result,
	        ]);
	
	        if (! ($result['status'] ?? false)) {
	            Log::error('TokoPay gagal membuat transaksi', [
	                'invoice' => $booking->invoice,
	                'reference_id' => $reffId,
	                'response' => $result,
	            ]);
	
	            return back()->with('error', 'Gagal membuat pembayaran TokoPay.');
	        }
	
	        $data = $result['data'] ?? [];
	
	        $methodCode = strtoupper($booking->payment_method_code ?? '');
	
	        /*
	        |--------------------------------------------------------------------------
	        | Deteksi metode
	        |--------------------------------------------------------------------------
	        | VA harus dicek sebelum E-Wallet.
	        | Karena DANAMONVA mengandung kata DANA.
	        */
	        $isQris = str_contains($methodCode, 'QRIS')
	            || in_array($methodCode, ['11', '17', '23']);
	
	        $isVa = str_contains($methodCode, 'VA')
	            || str_contains($methodCode, 'BCAVA')
	            || str_contains($methodCode, 'BNIVA')
	            || str_contains($methodCode, 'BRIVA')
	            || str_contains($methodCode, 'MANDIRIVA')
	            || str_contains($methodCode, 'PERMATAVA')
	            || str_contains($methodCode, 'CIMBVA')
	            || str_contains($methodCode, 'DANAMONVA')
	            || str_contains($methodCode, 'BNCVA')
	            || str_contains($methodCode, 'BSIVA');
	
	        $isEwallet = ! $isVa && (
	            str_contains($methodCode, 'DANA')
	            || str_contains($methodCode, 'GOPAY')
	            || str_contains($methodCode, 'OVO')
	            || str_contains($methodCode, 'SHOPEE')
	            || str_contains($methodCode, 'LINKAJA')
	            || str_contains($methodCode, 'ASTRAPAY')
	            || str_contains($methodCode, 'VIRGO')
	            || str_contains($methodCode, 'OVOPUSH')
	        );
	
	        /*
	        |--------------------------------------------------------------------------
	        | Ambil semua kemungkinan data pembayaran
	        |--------------------------------------------------------------------------
	        */
	        $noPembayaran = $data['no_pembayaran']
	            ?? $result['no_pembayaran']
	            ?? $data['nomor_va']
	            ?? $data['va_number']
	            ?? $data['payment_code']
	            ?? $data['pay_code']
	            ?? $data['qr_url']
	            ?? $data['qr_link']
	            ?? $data['qr_string']
	            ?? null;
	
	        $paymentUrl = $data['pay_url']
	            ?? $data['payment_url']
	            ?? $data['checkout_url']
	            ?? $data['url']
	            ?? $data['payment_link']
	            ?? $result['pay_url']
	            ?? $result['payment_url']
	            ?? $result['checkout_url']
	            ?? $result['url']
	            ?? $result['payment_link']
	            ?? null;
	
	        $transactionId = $data['trx_id']
	            ?? $data['id']
	            ?? $data['transaction_id']
	            ?? $result['trx_id']
	            ?? $result['transaction_id']
	            ?? null;
	
	        /*
	        |--------------------------------------------------------------------------
	        | Helper URL valid
	        |--------------------------------------------------------------------------
	        */
	        $isValidUrl = function ($url) {
	            if (! $url) {
	                return false;
	            }
	
	            return str_starts_with($url, 'http://')
	                || str_starts_with($url, 'https://');
	        };
	
	        /*
	        |--------------------------------------------------------------------------
	        | Cegah URL balik ke halaman sendiri
	        |--------------------------------------------------------------------------
	        */
	        $isOwnPayPage = function ($url) use ($booking) {
	            if (! $url) {
	                return false;
	            }
	
	            return str_contains($url, '/booking/pay/' . $booking->invoice)
	                || str_contains($url, route('booking.pay', $booking->invoice));
	        };
	
	        /*
	        |--------------------------------------------------------------------------
	        | QRIS
	        |--------------------------------------------------------------------------
	        | QRIS tetap tampil di halaman Rafa Kost.
	        */
	        if ($isQris) {
	            $booking->update([
	                'transaction_id' => $transactionId,
	                'payment_url' => $isValidUrl($paymentUrl) ? $paymentUrl : null,
	                'qr_string' => $noPembayaran,
	            ]);
	
	            return redirect()->route('booking.pay', $booking->invoice);
	        }
	
	        /*
	        |--------------------------------------------------------------------------
	        | VA
	        |--------------------------------------------------------------------------
	        | VA dibuat seperti E-Wallet:
	        | - Jangan simpan nomor VA ke payment_url.
	        | - Hanya simpan URL kalau benar-benar URL.
	        | - Kalau paymentUrl kosong, coba noPembayaran kalau dia URL.
	        */
	        if ($isVa) {
	            $vaUrl = null;
	
	            if ($isValidUrl($paymentUrl) && ! $isOwnPayPage($paymentUrl)) {
	                $vaUrl = $paymentUrl;
	            } elseif ($isValidUrl($noPembayaran) && ! $isOwnPayPage($noPembayaran)) {
	                $vaUrl = $noPembayaran;
	            }
	
	            $booking->update([
	                'transaction_id' => $transactionId,
	                'payment_url' => $vaUrl,
	                'qr_string' => null,
	            ]);
	
	            return redirect()->route('booking.pay', $booking->invoice);
	        }
	
	        /*
	        |--------------------------------------------------------------------------
	        | E-Wallet
	        |--------------------------------------------------------------------------
	        */
	        if ($isEwallet) {
	            $ewalletUrl = null;
	
	            if ($isValidUrl($noPembayaran) && ! $isOwnPayPage($noPembayaran)) {
	                $ewalletUrl = $noPembayaran;
	            } elseif ($isValidUrl($paymentUrl) && ! $isOwnPayPage($paymentUrl)) {
	                $ewalletUrl = $paymentUrl;
	            }
	
	            $booking->update([
	                'transaction_id' => $transactionId,
	                'payment_url' => $ewalletUrl,
	                'qr_string' => null,
	            ]);
	
	            return redirect()->route('booking.pay', $booking->invoice);
	        }
	
	        /*
	        |--------------------------------------------------------------------------
	        | Fallback
	        |--------------------------------------------------------------------------
	        */
	        $fallbackUrl = null;
	
	        if ($isValidUrl($paymentUrl) && ! $isOwnPayPage($paymentUrl)) {
	            $fallbackUrl = $paymentUrl;
	        } elseif ($isValidUrl($noPembayaran) && ! $isOwnPayPage($noPembayaran)) {
	            $fallbackUrl = $noPembayaran;
	        }
	
	        $booking->update([
	            'transaction_id' => $transactionId,
	            'payment_url' => $fallbackUrl,
	            'qr_string' => $noPembayaran,
	        ]);
	
	        return redirect()->route('booking.pay', $booking->invoice);
	
	    } catch (\Throwable $e) {
	        Log::error('TokoPay payment error: ' . $e->getMessage(), [
	            'invoice' => $booking->invoice,
	        ]);
	
	        return back()->with('error', 'Terjadi kesalahan saat membuat pembayaran TokoPay.');
	    }
	}
	
	public function pay($invoice)
	{
	    $booking = Booking::with(['kamar', 'user'])
	        ->where('invoice', $invoice)
	        ->firstOrFail();
	
	    if ((int) $booking->user_id !== (int) auth()->id()) {
	        abort(403);
	    }
	
	    if ($booking->payment_status === 'canceled') {
	        return redirect()
	            ->route('payment-history.index')
	            ->with('error', 'Invoice ini sudah dibatalkan.');
	    }
	
	    $method = null;
	
	    if ($booking->payment_method_code) {
	        $method = PaymentMethod::where('gateway_code', $booking->payment_gateway)
	            ->where('code', $booking->payment_method_code)
	            ->first();
	    }
	
	    return view('booking.pay', compact('booking', 'method'));
	}

    private function sendPaidEmail(Booking $booking): void
    {
        try {
            $booking->loadMissing(['kamar', 'user']);

            $email = $booking->customer_email ?: ($booking->user->email ?? null);

            if (! $email) {
                Log::warning('Email pembayaran tidak dikirim karena email kosong', [
                    'invoice' => $booking->invoice,
                ]);

                return;
            }

            Mail::to($email)->send(new BookingPaidMail($booking));

            Log::info('Email pembayaran berhasil dikirim', [
                'invoice' => $booking->invoice,
                'email' => $email,
            ]);
        } catch (\Throwable $e) {
            Log::error('Gagal mengirim email pembayaran: ' . $e->getMessage(), [
                'invoice' => $booking->invoice,
            ]);
        }
    }

    public function cashifyCallback(Request $request)
    {
        $transactionId = $request->input('transactionId');
        $status = strtolower($request->input('status', ''));

        if (! $transactionId) {
            return response()->json(['message' => 'transactionId kosong'], 400);
        }

        $booking = Booking::where('transaction_id', $transactionId)->first();

        if (! $booking) {
            return response()->json(['message' => 'Booking tidak ditemukan'], 404);
        }

        if ($booking->payment_status === 'canceled') {
            return response()->json([
                'status' => 'ignored',
                'message' => 'Invoice sudah dibatalkan',
            ]);
        }

        if (in_array($status, ['paid', 'success'])) {
            $wasAlreadyPaid = $booking->payment_status === 'paid';

            $booking->update([
                'payment_status' => 'paid',
                'paid_at' => $booking->paid_at ?: now(),
            ]);

            $booking->kamar?->update([
                'status' => 'terisi',
            ]);

            if (! $wasAlreadyPaid) {
                $this->sendPaidEmail($booking);
            }
        }

        return response()->json(['status' => 'ok']);
    }

	  public function tokopayCallback(Request $request)
	{
	    $data = $request->all();
	
	    Log::info('TOKOPAY CALLBACK', $data);
	
	    $reffId = $data['reff_id'] ?? null;
	    $status = $data['status'] ?? null;
	    $signature = $data['signature'] ?? null;
	
	    if (! $reffId || ! $signature) {
	        return response()->json(['message' => 'Data callback tidak lengkap'], 400);
	    }
	
	    /*
	    |--------------------------------------------------------------------------
	    | Cari booking dari reference_id dulu
	    |--------------------------------------------------------------------------
	    | Karena setiap ganti metode kita buat reference_id baru.
	    */
	    $booking = Booking::where('reference_id', $reffId)
	        ->orWhere('invoice', $reffId)
	        ->first();
	
	    if (! $booking) {
	        return response()->json(['message' => 'Booking tidak ditemukan'], 404);
	    }
	
	    if ($booking->payment_status === 'canceled') {
	        return response()->json([
	            'status' => 'ignored',
	            'message' => 'Invoice sudah dibatalkan',
	        ]);
	    }
	
	    $gateway = PaymentGateway::where('code', 'tokopay')->first();
	
	    if (! $gateway) {
	        return response()->json(['message' => 'Gateway TokoPay tidak ditemukan'], 404);
	    }
	
	    $validSignature = md5($gateway->tokopay_merchant_id . ':' . $gateway->tokopay_secret_key . ':' . $reffId);
	
	    if ($signature !== $validSignature) {
	        return response()->json(['message' => 'Invalid signature'], 403);
	    }
	
	    if ($status === 'Success') {
	        $wasAlreadyPaid = $booking->payment_status === 'paid';
	
	        $booking->update([
	            'payment_status' => 'paid',
	            'paid_at' => $booking->paid_at ?: now(),
	        ]);
	
	        $booking->kamar?->update([
	            'status' => 'terisi',
	        ]);
	
	        if (! $wasAlreadyPaid) {
	            $this->sendPaidEmail($booking);
	        }
	    }
	
	    return response()->json(['success' => true]);
	}
}