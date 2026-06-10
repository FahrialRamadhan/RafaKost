<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\PaymentGateway;
use App\Services\SettingService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MyRentalController extends Controller
{
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Card Sewa Aktif
        |--------------------------------------------------------------------------
        | Tampilkan 1 card aktif per kamar.
        */
        $bookings = Booking::with('kamar')
            ->where('user_id', auth()->id())
            ->where('payment_status', 'paid')
            ->get()
            ->sortByDesc(function ($booking) {
                $tanggalMasuk = $booking->tanggal_masuk
                    ? Carbon::parse($booking->tanggal_masuk)->startOfDay()
                    : now()->startOfDay();

                $tanggalHabis = $booking->tanggal_habis_custom
                    ? Carbon::parse($booking->tanggal_habis_custom)->startOfDay()
                    : $tanggalMasuk->copy()->addMonths((int) $booking->durasi);

                return $tanggalHabis->timestamp;
            })
            ->unique('kamar_id')
            ->values();

        /*
        |--------------------------------------------------------------------------
        | Filter Riwayat Pembayaran
        |--------------------------------------------------------------------------
        | Default: tampil invoice hari ini.
        | Kalau user pilih rentang tanggal, tampil sesuai range.
        */
        $startDate = $request->filled('start_date')
            ? Carbon::parse($request->start_date)->startOfDay()
            : now()->startOfDay();

        $endDate = $request->filled('end_date')
            ? Carbon::parse($request->end_date)->endOfDay()
            : now()->endOfDay();

        if ($endDate->lt($startDate)) {
            $temp = $startDate->copy();
            $startDate = $endDate->copy()->startOfDay();
            $endDate = $temp->copy()->endOfDay();
        }

        $paymentHistories = Booking::with('kamar')
            ->where('user_id', auth()->id())
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->get();

        return view('profile.my-rentals', compact(
            'bookings',
            'paymentHistories',
            'startDate',
            'endDate'
        ));
    }

    public function historyData(Request $request)
    {
        $startDate = $request->filled('start_date')
            ? Carbon::parse($request->start_date)->startOfDay()
            : now()->startOfDay();

        $endDate = $request->filled('end_date')
            ? Carbon::parse($request->end_date)->endOfDay()
            : now()->endOfDay();

        if ($endDate->lt($startDate)) {
            $temp = $startDate->copy();
            $startDate = $endDate->copy()->startOfDay();
            $endDate = $temp->copy()->endOfDay();
        }

        $bookings = Booking::with('kamar')
            ->where('user_id', auth()->id())
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->get();

        $data = $bookings->map(function ($booking) {
            $status = $booking->payment_status ?? 'pending';

            $statusText = match ($status) {
                'paid' => 'Lunas',
                'pending' => 'Pending',
                'canceled' => 'Batal',
                default => ucfirst($status),
            };

            $statusStyle = match ($status) {
                'paid' => 'background:#dcfce7; color:#166534;',
                'pending' => 'background:#fef3c7; color:#b45309;',
                'canceled' => 'background:#fee2e2; color:#991b1b;',
                default => 'background:#f1f5f9; color:#64748b;',
            };

            return [
                'id' => $booking->id,
                'tanggal' => $booking->created_at ? $booking->created_at->format('d/m/Y') : '-',
                'invoice' => $booking->invoice ?? '-',
                'kamar' => $booking->kamar->nama ?? 'Kamar',
                'tanggal_masuk' => $booking->tanggal_masuk ?? '-',
                'durasi' => ($booking->durasi ?? '-') . ' Bln',
                'total' => 'Rp ' . number_format($booking->payment_total ?? $booking->total_harga ?? 0, 0, ',', '.'),
                'status' => $status,
                'status_text' => $statusText,
                'status_style' => $statusStyle,

                'invoice_url' => $status === 'paid' && $booking->invoice
                    ? route('booking.invoice', $booking->invoice)
                    : null,

                'payment_url' => $status === 'pending' && $booking->invoice
                    ? route('booking.methods', $booking->invoice)
                    : null,

                'cancel_url' => $status === 'pending'
                    ? route('payment-history.cancel', $booking)
                    : null,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function renew(Request $request, SettingService $settings, $booking)
    {
        $booking = Booking::with('kamar')
            ->where('id', $booking)
            ->where('user_id', auth()->id())
            ->where('payment_status', 'paid')
            ->firstOrFail();

        $request->validate([
            'durasi' => ['required', 'integer', 'min:1', 'max:12'],
            'orang' => ['required', 'integer', 'min:1', 'max:2'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Cegah invoice perpanjangan dobel
        |--------------------------------------------------------------------------
        | Kalau invoice pending sudah kena denda, batalkan otomatis.
        | Kalau invoice pending belum kena denda, tetap pakai logic lama:
        | user harus selesaikan atau batalkan invoice lama dulu.
        */
        $pendingBookings = Booking::where('user_id', auth()->id())
            ->where('payment_status', 'pending')
            ->latest()
            ->get();

        foreach ($pendingBookings as $pendingBooking) {
            if ($this->pendingInvoiceHasLateFee($pendingBooking, $settings)) {
                $this->cancelBookingPayment($pendingBooking);
            }
        }

        $pendingBooking = Booking::where('user_id', auth()->id())
            ->where('payment_status', 'pending')
            ->latest()
            ->first();

        if ($pendingBooking) {
            return redirect()
                ->route('payment-history.index')
                ->with('error', 'Kamu masih punya invoice pembayaran yang belum selesai. Selesaikan atau batalkan invoice lama terlebih dahulu.');
        }

        $gateway = PaymentGateway::where('is_active', true)->first();

        if (! $gateway) {
            return back()->with('error', 'Payment gateway belum diaktifkan oleh admin.');
        }

        $tanggalMasukLama = Carbon::parse($booking->tanggal_masuk)->startOfDay();

        $tanggalHabisLama = $booking->tanggal_habis_custom
            ? Carbon::parse($booking->tanggal_habis_custom)->startOfDay()
            : $tanggalMasukLama->copy()->addMonths((int) $booking->durasi);

        $tanggalMasukBaru = $tanggalHabisLama->copy();

        $durasi = (int) $request->durasi;
        $orang = (int) $request->orang;

        $hargaPerBulan = $orang >= 2
            ? (int) ($booking->kamar->harga_2_orang ?? $booking->kamar->harga ?? 0)
            : (int) ($booking->kamar->harga_1_orang ?? $booking->kamar->harga ?? 0);

        /*
        |--------------------------------------------------------------------------
        | Hitung Denda Telat dari Setting Admin
        |--------------------------------------------------------------------------
        */
        $totalSewa = $hargaPerBulan * $durasi;

        $lateFeeEnabled = (bool) $settings->get('late_fee.enabled', false);
        $dendaPerHari = (int) $settings->get('late_fee.amount_per_day', 10000);
        $graceDays = (int) $settings->get('late_fee.grace_days', 0);

        $hariIni = now()->startOfDay();

        $selisihHari = $hariIni->greaterThan($tanggalHabisLama)
            ? $tanggalHabisLama->diffInDays($hariIni)
            : 0;

        $hariTelat = $lateFeeEnabled
            ? max($selisihHari - $graceDays, 0)
            : 0;

        $dendaTelat = $hariTelat * $dendaPerHari;

        $total = $totalSewa + $dendaTelat;

        $invoice = 'RK-' . time() . '-' . rand(100, 999);

		$newBooking = Booking::create([
		    'invoice' => $invoice,
		    'user_id' => auth()->id(),
		
		    'customer_name' => $booking->customer_name ?: (auth()->user()->name ?? null),
		    'customer_phone' => $booking->customer_phone ?: (auth()->user()->phone ?? null),
		    'customer_email' => $booking->customer_email ?: (auth()->user()->email ?? null),
		    'customer_address' => $booking->customer_address,
		    'customer_note' => 'Perpanjangan dari invoice ' . $booking->invoice .
		        '. Total sewa: Rp ' . number_format($totalSewa, 0, ',', '.') .
		        '. Telat ' . $hariTelat . ' hari. Denda: Rp ' . number_format($dendaTelat, 0, ',', '.') .
		        '. Total bayar: Rp ' . number_format($total, 0, ',', '.'),
		
		    'kamar_id' => $booking->kamar_id,
		    'tanggal_masuk' => $tanggalMasukBaru->format('Y-m-d'),
		    'due_date' => $tanggalHabisLama->format('Y-m-d'),
		    'durasi' => $durasi,
		    'orang' => $orang,
		
		    'total_harga' => $totalSewa,
		    'late_days' => $hariTelat,
		    'late_fee' => $dendaTelat,
		    'payment_fee' => 0,
		    'payment_total' => $total,
		    'payment_gateway' => $gateway->code,
		    'payment_status' => 'pending',
		    'reference_id' => $invoice,
		]);
		$newBooking->forceFill([
		    'total_harga' => $totalSewa,
		    'late_days' => $hariTelat,
		    'late_fee' => $dendaTelat,
		    'payment_fee' => 0,
		    'payment_total' => $total,
		    'due_date' => $tanggalHabisLama->format('Y-m-d'),
		])->save();

        return redirect()
            ->route('booking.invoice', $newBooking->invoice)
            ->with('success', 'Invoice perpanjangan berhasil dibuat.');
    }

    private function pendingInvoiceHasLateFee(Booking $booking, SettingService $settings): bool
    {
        if (! $booking->due_date) {
            return false;
        }

        $lateFeeEnabled = (bool) $settings->get('late_fee.enabled', false);

        if (! $lateFeeEnabled) {
            return false;
        }

        $graceDays = (int) $settings->get('late_fee.grace_days', 0);

        $dueDate = Carbon::parse($booking->due_date)->startOfDay();
        $today = now()->startOfDay();

        if (! $today->greaterThan($dueDate)) {
            return false;
        }

        $selisihHari = $dueDate->diffInDays($today);
        $hariTelat = max($selisihHari - $graceDays, 0);

        return $hariTelat > 0;
    }

    private function cancelBookingPayment(Booking $booking): void
    {
        $booking->forceFill([
            'payment_status' => 'canceled',
            'payment_gateway' => null,
            'payment_method_code' => null,
            'payment_method_name' => null,
            'payment_fee' => 0,
            'payment_total' => $booking->total_harga,
            'payment_url' => null,
            'qr_string' => null,
            'transaction_id' => null,
            'reference_id' => null,
            'paid_at' => null,
        ])->save();
    }
}