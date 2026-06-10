<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\PaymentGateway;
use App\Models\PaymentMethod;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymentApiController extends Controller
{
    public function invoice(Request $request, $invoice)
    {
        $user = $this->userFromToken($request);

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'Token tidak valid.',
            ], 401);
        }

        if ($user->identity_status !== 'approved') {
            return $this->identityNotApprovedResponse($user);
        }

        $booking = Booking::with(['kamar', 'user'])
            ->where('invoice', $invoice)
            ->where('user_id', $user->id)
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => $this->bookingData($booking),
        ]);
    }

    public function methods(Request $request, $invoice)
    {
        $user = $this->userFromToken($request);

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'Token tidak valid.',
            ], 401);
        }

        if ($user->identity_status !== 'approved') {
            return $this->identityNotApprovedResponse($user);
        }

        $booking = Booking::with(['kamar', 'user'])
            ->where('invoice', $invoice)
            ->where('user_id', $user->id)
            ->firstOrFail();

        if ($booking->payment_status === 'canceled') {
            return response()->json([
                'success' => false,
                'message' => 'Invoice ini sudah dibatalkan. Silakan booking ulang jika ingin menyewa kamar.',
                'booking' => $this->bookingData($booking),
            ], 422);
        }

        if ($booking->payment_status === 'paid') {
            return response()->json([
                'success' => true,
                'message' => 'Invoice sudah dibayar.',
                'booking' => $this->bookingData($booking),
                'data' => [],
            ]);
        }

        $gateway = PaymentGateway::where('code', $booking->payment_gateway)
            ->where('is_active', true)
            ->firstOrFail();

        $methods = PaymentMethod::where('gateway_code', $gateway->code)
            ->where('is_active', true)
            ->orderByRaw("FIELD(category, 'qris', 'e-wallet', 'virtual-account', 'saldo', 'lainnya')")
            ->orderBy('name')
            ->get()
            ->map(function ($method) use ($booking) {
                $feePercent = (int) round($booking->total_harga * ((float) $method->fee_percent / 100));
                $fee = (int) $method->fee_fixed + $feePercent;
                $paymentTotal = (int) $booking->total_harga + $fee;

                return [
                    'id' => $method->id,
                    'gateway_code' => $method->gateway_code,
                    'code' => $method->code,
                    'name' => $method->name,
                    'category' => $method->category,
                    'fee_fixed' => (int) $method->fee_fixed,
                    'fee_percent' => (float) $method->fee_percent,
                    'fee' => $fee,
                    'payment_total' => $paymentTotal,
                ];
            });

        return response()->json([
            'success' => true,
            'gateway' => [
                'code' => $gateway->code,
                'name' => $gateway->name ?? $gateway->code,
            ],
            'booking' => $this->bookingData($booking),
            'data' => $methods,
        ]);
    }

    public function chooseMethod(Request $request, $invoice)
    {
        $user = $this->userFromToken($request);

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'Token tidak valid.',
            ], 401);
        }

        if ($user->identity_status !== 'approved') {
            return $this->identityNotApprovedResponse($user);
        }

        $request->validate([
            'payment_method_id' => ['required', 'exists:payment_methods,id'],
        ]);

        $booking = Booking::with(['kamar', 'user'])
            ->where('invoice', $invoice)
            ->where('user_id', $user->id)
            ->firstOrFail();

        if ($booking->payment_status === 'canceled') {
            return response()->json([
                'success' => false,
                'message' => 'Invoice ini sudah dibatalkan.',
                'booking' => $this->bookingData($booking),
            ], 422);
        }

        if ($booking->payment_status === 'paid') {
            return response()->json([
                'success' => false,
                'message' => 'Invoice sudah dibayar.',
                'booking' => $this->bookingData($booking),
            ], 422);
        }

        $gateway = PaymentGateway::where('code', $booking->payment_gateway)
            ->where('is_active', true)
            ->firstOrFail();

        $method = PaymentMethod::where('id', $request->payment_method_id)
            ->where('gateway_code', $gateway->code)
            ->where('is_active', true)
            ->firstOrFail();

        $feePercent = (int) round($booking->total_harga * ((float) $method->fee_percent / 100));
        $fee = (int) $method->fee_fixed + $feePercent;
        $paymentTotal = (int) $booking->total_harga + $fee;

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
            return response()->json([
                'success' => false,
                'message' => 'Metode Virtual Account minimum pembayaran Rp 10.000. Silakan pilih QRIS/E-Wallet atau naikkan nominal pembayaran.',
                'booking' => $this->bookingData($booking),
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | Buat reference baru setiap ganti metode
        |--------------------------------------------------------------------------
        */
        $safeChannel = preg_replace('/[^A-Za-z0-9]/', '', (string) $method->code);
        $paymentReference = $booking->invoice . '-' . strtoupper($safeChannel) . '-' . time() . random_int(100, 999);

        /*
        |--------------------------------------------------------------------------
        | Reset data payment lama
        |--------------------------------------------------------------------------
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

        $booking = $booking->fresh(['kamar', 'user']);

        if ($gateway->code === 'cashify') {
            return $this->createCashifyPayment($booking, $gateway);
        }

        if ($gateway->code === 'tokopay') {
            return $this->createTokoPayPayment($booking, $gateway);
        }

        return response()->json([
            'success' => false,
            'message' => 'Gateway tidak dikenali.',
        ], 422);
    }

    public function status(Request $request, $invoice)
    {
        $user = $this->userFromToken($request);

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'Token tidak valid.',
            ], 401);
        }

        if ($user->identity_status !== 'approved') {
            return $this->identityNotApprovedResponse($user);
        }

        $booking = Booking::with(['kamar', 'user'])
            ->where('invoice', $invoice)
            ->where('user_id', $user->id)
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'booking' => $this->bookingData($booking),
            'payment' => $this->paymentData($booking),
        ]);
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
                Log::error('Cashify API error', [
                    'invoice' => $booking->invoice,
                    'reference_id' => $booking->reference_id,
                    'response' => $result,
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Gagal membuat pembayaran Cashify.',
                    'response' => $result,
                ], 422);
            }

            $data = $result['data'] ?? [];

            $booking->update([
                'transaction_id' => $data['transactionId'] ?? null,
                'qr_string' => $data['qr_string'] ?? null,
                'payment_url' => null,
                'payment_total' => $data['totalAmount'] ?? $booking->payment_total,
            ]);

            $booking = $booking->fresh(['kamar', 'user']);

            return response()->json([
                'success' => true,
                'message' => 'Pembayaran Cashify berhasil dibuat.',
                'payment' => $this->paymentData($booking),
                'booking' => $this->bookingData($booking),
            ]);
        } catch (\Throwable $e) {
            Log::error('Cashify API payment error: ' . $e->getMessage(), [
                'invoice' => $booking->invoice,
                'reference_id' => $booking->reference_id,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membuat pembayaran Cashify.',
            ], 500);
        }
    }

    private function createTokoPayPayment(Booking $booking, PaymentGateway $gateway)
    {
        try {
            $booking->loadMissing('user');

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

                'customer_name' => $booking->customer_name ?: ($booking->user->name ?? 'Pelanggan RafaKost'),
                'customer_email' => $booking->customer_email ?: ($booking->user->email ?? 'customer@rafakost.biz.id'),
                'customer_phone' => $booking->customer_phone ?: ($booking->user->phone ?? '081234567890'),

                'redirect_url' => url('/'),
                'expired_ts' => 0,
                'signature' => $signature,

                'items' => [
                    [
                        'product_code' => 'KAMAR-' . $booking->kamar_id,
                        'name' => 'Booking Kamar RafaKost - ' . ($booking->payment_method_name ?? 'Pembayaran'),
                        'price' => $booking->payment_total,
                        'product_url' => url('/'),
                        'image_url' => asset('favicon.ico'),
                    ],
                ],
            ];

            $response = Http::asJson()->post('https://api.tokopay.id/v1/order', $payload);
            $result = $response->json();

            Log::info('TOKOPAY API CREATE RESPONSE', [
                'invoice' => $booking->invoice,
                'reference_id' => $reffId,
                'channel' => $channel,
                'payload' => $payload,
                'response' => $result,
            ]);

            if (! ($result['status'] ?? false)) {
                Log::error('TokoPay API gagal membuat transaksi', [
                    'invoice' => $booking->invoice,
                    'reference_id' => $reffId,
                    'response' => $result,
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Gagal membuat pembayaran TokoPay.',
                    'response' => $result,
                ], 422);
            }

            $data = $result['data'] ?? [];

            $methodCode = strtoupper($booking->payment_method_code ?? '');

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

		$qrString = $data['qr_string']
		    ?? $result['qr_string']
		    ?? null;
		
		$qrLink = $data['qr_link']
		    ?? $data['qr_url']
		    ?? $result['qr_link']
		    ?? $result['qr_url']
		    ?? null;
		
		$noPembayaran = $data['no_pembayaran']
		    ?? $result['no_pembayaran']
		    ?? $data['nomor_va']
		    ?? $data['va_number']
		    ?? $data['payment_code']
		    ?? $data['pay_code']
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
            $isValidUrl = function ($url) {
                if (! $url) {
                    return false;
                }

                return str_starts_with($url, 'http://')
                    || str_starts_with($url, 'https://');
            };

            $isOwnRedirectUrl = function ($url) {
                if (! $url) {
                    return false;
                }

                return rtrim($url, '/') === rtrim(url('/'), '/');
            };

			if ($isQris) {
			    $validPaymentUrl = null;
			
			    if ($isValidUrl($paymentUrl) && ! $isOwnRedirectUrl($paymentUrl)) {
			        $validPaymentUrl = $paymentUrl;
			    } elseif ($isValidUrl($qrLink) && ! $isOwnRedirectUrl($qrLink)) {
			        $validPaymentUrl = $qrLink;
			    }
			
			    $booking->update([
			        'transaction_id' => $transactionId,
			        'payment_url' => $validPaymentUrl,
			        'qr_string' => $qrString,
			    ]);
			} elseif ($isVa) {
                $vaUrl = null;
                $vaCode = null;

                if ($isValidUrl($paymentUrl) && ! $isOwnRedirectUrl($paymentUrl)) {
                    $vaUrl = $paymentUrl;
                } elseif ($isValidUrl($noPembayaran) && ! $isOwnRedirectUrl($noPembayaran)) {
                    $vaUrl = $noPembayaran;
                } else {
                    $vaCode = $noPembayaran;
                }

                $booking->update([
                    'transaction_id' => $transactionId,
                    'payment_url' => $vaUrl,
                    'qr_string' => $vaCode,
                ]);
            } elseif ($isEwallet) {
                $ewalletUrl = null;

                if ($isValidUrl($noPembayaran) && ! $isOwnRedirectUrl($noPembayaran)) {
                    $ewalletUrl = $noPembayaran;
                } elseif ($isValidUrl($paymentUrl) && ! $isOwnRedirectUrl($paymentUrl)) {
                    $ewalletUrl = $paymentUrl;
                }

                $booking->update([
                    'transaction_id' => $transactionId,
                    'payment_url' => $ewalletUrl,
                    'qr_string' => null,
                ]);
            } else {
                $fallbackUrl = null;
                $fallbackCode = null;

                if ($isValidUrl($paymentUrl) && ! $isOwnRedirectUrl($paymentUrl)) {
                    $fallbackUrl = $paymentUrl;
                } elseif ($isValidUrl($noPembayaran) && ! $isOwnRedirectUrl($noPembayaran)) {
                    $fallbackUrl = $noPembayaran;
                } else {
                    $fallbackCode = $noPembayaran;
                }

                $booking->update([
                    'transaction_id' => $transactionId,
                    'payment_url' => $fallbackUrl,
                    'qr_string' => $fallbackCode,
                ]);
            }

            $booking = $booking->fresh(['kamar', 'user']);

            return response()->json([
                'success' => true,
                'message' => 'Pembayaran TokoPay berhasil dibuat.',
                'payment' => $this->paymentData($booking),
                'booking' => $this->bookingData($booking),
            ]);
        } catch (\Throwable $e) {
            Log::error('TokoPay API payment error: ' . $e->getMessage(), [
                'invoice' => $booking->invoice,
                'reference_id' => $booking->reference_id,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membuat pembayaran TokoPay.',
            ], 500);
        }
    }

    private function identityNotApprovedResponse(User $user)
    {
        return response()->json([
            'success' => false,
            'message' => 'Akun kamu belum terverifikasi. Silakan verifikasi dokumen terlebih dahulu sebelum melakukan pembayaran.',
            'identity_status' => $user->identity_status,
        ], 403);
    }

    private function paymentData(Booking $booking): array
    {
        return [
            'invoice' => $booking->invoice,
            'reference_id' => $booking->reference_id,
            'payment_gateway' => $booking->payment_gateway,
            'payment_method_code' => $booking->payment_method_code,
            'payment_method_name' => $booking->payment_method_name,
            'transaction_id' => $booking->transaction_id,
            'payment_url' => $booking->payment_url,
            'qr_string' => $booking->qr_string,
            'payment_fee' => (int) $booking->payment_fee,
            'payment_total' => (int) $booking->payment_total,
            'payment_status' => $booking->payment_status,
        ];
    }

    private function bookingData(Booking $booking): array
    {
        return [
            'id' => $booking->id,
            'invoice' => $booking->invoice,
            'reference_id' => $booking->reference_id,
            'kamar_id' => $booking->kamar_id,
            'kamar' => $booking->kamar ? [
                'id' => $booking->kamar->id,
                'nama' => $booking->kamar->nama,
                'lantai' => $booking->kamar->lantai,
                'kamar_mandi' => $booking->kamar->kamar_mandi,
                'image' => $booking->kamar->image
                    ? asset('storage/' . $booking->kamar->image)
                    : null,
            ] : null,
            'tanggal_masuk' => $booking->tanggal_masuk,
            'durasi' => (int) $booking->durasi,
            'orang' => (int) $booking->orang,
            'total_harga' => (int) $booking->total_harga,
            'payment_fee' => (int) $booking->payment_fee,
            'payment_total' => (int) $booking->payment_total,
            'payment_gateway' => $booking->payment_gateway,
            'payment_method_code' => $booking->payment_method_code,
            'payment_method_name' => $booking->payment_method_name,
            'payment_status' => $booking->payment_status,
            'transaction_id' => $booking->transaction_id,
            'payment_url' => $booking->payment_url,
            'qr_string' => $booking->qr_string,
        ];
    }

    private function userFromToken(Request $request): ?User
    {
        $header = $request->header('Authorization');

        if (! $header || ! str_starts_with($header, 'Bearer ')) {
            return null;
        }

        $plainToken = substr($header, 7);

        return User::where('api_token_hash', hash('sha256', $plainToken))->first();
    }
}