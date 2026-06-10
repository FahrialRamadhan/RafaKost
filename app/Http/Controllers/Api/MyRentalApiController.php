<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\PaymentGateway;
use App\Models\User;
use App\Services\SettingService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MyRentalApiController extends Controller
{
    public function index(Request $request)
    {
        $user = $this->userFromToken($request);

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'Token tidak valid.',
            ], 401);
        }

        /*
        |--------------------------------------------------------------------------
        | Card Sewa Aktif
        |--------------------------------------------------------------------------
        | Sama seperti website:
        | - hanya booking paid
        | - tampil 1 card aktif per kamar
        | - ambil masa sewa dengan tanggal habis paling akhir
        */
        $bookings = Booking::with('kamar')
            ->where('user_id', $user->id)
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
            ->values()
            ->map(function ($booking) {
                $tanggalMasuk = $booking->tanggal_masuk
                    ? Carbon::parse($booking->tanggal_masuk)->startOfDay()
                    : now()->startOfDay();

                $tanggalHabis = $booking->tanggal_habis_custom
                    ? Carbon::parse($booking->tanggal_habis_custom)->startOfDay()
                    : $tanggalMasuk->copy()->addMonths((int) $booking->durasi);

                $today = now()->startOfDay();
                $daysLeft = $today->diffInDays($tanggalHabis, false);

                return [
                    'id' => $booking->id,
                    'invoice' => $booking->invoice,
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
                    'tanggal_masuk' => $tanggalMasuk->format('Y-m-d'),
                    'tanggal_habis' => $tanggalHabis->format('Y-m-d'),
                    'durasi' => (int) $booking->durasi,
                    'orang' => (int) $booking->orang,
                    'days_left' => $daysLeft,
                    'status_masa_sewa' => $this->rentalStatus($daysLeft),
                    'rent_end_action' => $booking->rent_end_action,
                    'total_harga' => (int) $booking->total_harga,
                    'payment_total' => (int) $booking->payment_total,
                    'paid_at' => $booking->paid_at,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $bookings,
        ]);
    }

    public function historyData(Request $request)
    {
        $user = $this->userFromToken($request);

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'Token tidak valid.',
            ], 401);
        }

        /*
        |--------------------------------------------------------------------------
        | Riwayat Pembayaran
        |--------------------------------------------------------------------------
        | Sama seperti website:
        | - default invoice hari ini
        | - kalau user pilih rentang tanggal, tampil sesuai range
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

        $bookings = Booking::with('kamar')
            ->where('user_id', $user->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->get()
            ->map(function ($booking) {
                return $this->formatPaymentHistory($booking);
            });

        return response()->json([
            'success' => true,
            'data' => $bookings,
            'filter' => [
                'start_date' => $startDate->format('Y-m-d'),
                'end_date' => $endDate->format('Y-m-d'),
            ],
        ]);
    }

    public function renew(Request $request, SettingService $settings, $booking)
    {
        $user = $this->userFromToken($request);

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'Token tidak valid.',
            ], 401);
        }

        $booking = Booking::with('kamar')
            ->where('id', $booking)
            ->where('user_id', $user->id)
            ->where('payment_status', 'paid')
            ->first();

        if (! $booking) {
            return response()->json([
                'success' => false,
                'message' => 'Data sewa tidak ditemukan.',
            ], 404);
        }

        $request->validate([
            'durasi' => ['required', 'integer', 'min:1', 'max:12'],
            'orang' => ['required', 'integer', 'min:1', 'max:2'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Cegah invoice perpanjangan dobel
        |--------------------------------------------------------------------------
        | Kalau user masih punya invoice pending, jangan buat invoice baru.
        */
        $pendingBooking = Booking::where('user_id', $user->id)
            ->where('payment_status', 'pending')
            ->latest()
            ->first();

        if ($pendingBooking) {
            return response()->json([
                'success' => false,
                'message' => 'Kamu masih punya invoice pembayaran yang belum selesai. Selesaikan atau batalkan invoice lama terlebih dahulu.',
                'pending_invoice' => $pendingBooking->invoice,
                'booking' => [
                    'id' => $pendingBooking->id,
                    'invoice' => $pendingBooking->invoice,
                    'kamar_id' => $pendingBooking->kamar_id,
                    'tanggal_masuk' => $pendingBooking->tanggal_masuk,
                    'durasi' => (int) $pendingBooking->durasi,
                    'orang' => (int) $pendingBooking->orang,
                    'total_harga' => (int) $pendingBooking->total_harga,
                    'payment_total' => (int) $pendingBooking->payment_total,
                    'payment_status' => $pendingBooking->payment_status,
                ],
            ], 422);
        }

        $gateway = PaymentGateway::where('is_active', true)->first();

        if (! $gateway) {
            return response()->json([
                'success' => false,
                'message' => 'Payment gateway belum diaktifkan oleh admin.',
            ], 422);
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
            'user_id' => $user->id,

            'customer_name' => $booking->customer_name ?: ($user->name ?? null),
            'customer_phone' => $booking->customer_phone ?: ($user->phone ?? null),
            'customer_email' => $booking->customer_email ?: ($user->email ?? null),
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

            'total_harga' => $total,
            'payment_fee' => 0,
            'payment_total' => $total,
            'payment_gateway' => $gateway->code,
            'payment_status' => 'pending',
            'reference_id' => $invoice,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Invoice perpanjangan berhasil dibuat.',
            'renewal' => [
                'from_invoice' => $booking->invoice,
                'new_invoice' => $newBooking->invoice,
                'tanggal_mulai_baru' => $newBooking->tanggal_masuk,
                'durasi' => (int) $newBooking->durasi,
                'orang' => (int) $newBooking->orang,
                'harga_per_bulan' => $hargaPerBulan,
                'total_sewa' => $totalSewa,
                'late_fee_enabled' => $lateFeeEnabled,
                'selisih_hari' => $selisihHari,
                'grace_days' => $graceDays,
                'hari_telat' => $hariTelat,
                'denda_per_hari' => $dendaPerHari,
                'denda_telat' => $dendaTelat,
                'total_bayar' => $total,
            ],
            'booking' => [
                'id' => $newBooking->id,
                'invoice' => $newBooking->invoice,
                'kamar_id' => $newBooking->kamar_id,
                'tanggal_masuk' => $newBooking->tanggal_masuk,
                'due_date' => $newBooking->due_date,
                'durasi' => (int) $newBooking->durasi,
                'orang' => (int) $newBooking->orang,
                'total_harga' => (int) $newBooking->total_harga,
                'payment_fee' => (int) $newBooking->payment_fee,
                'payment_total' => (int) $newBooking->payment_total,
                'payment_gateway' => $newBooking->payment_gateway,
                'payment_status' => $newBooking->payment_status,
            ],
            'invoice_url' => route('booking.invoice', $newBooking->invoice),
            'payment_url' => route('booking.methods', $newBooking->invoice),
        ]);
    }

    private function formatPaymentHistory($booking): array
    {
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
            'kamar_id' => $booking->kamar_id,
            'tanggal_masuk' => $booking->tanggal_masuk ?? '-',
            'durasi' => ($booking->durasi ?? '-') . ' Bln',
            'orang' => (int) $booking->orang,
            'total' => 'Rp ' . number_format($booking->payment_total ?? $booking->total_harga ?? 0, 0, ',', '.'),
            'total_raw' => (int) ($booking->payment_total ?? $booking->total_harga ?? 0),
            'status' => $status,
            'status_text' => $statusText,
            'status_style' => $statusStyle,

            'invoice_url' => $status === 'paid' && $booking->invoice
                ? route('booking.invoice', $booking->invoice)
                : null,

            'payment_url' => $status === 'pending' && $booking->invoice
                ? route('booking.methods', $booking->invoice)
                : null,
        ];
    }

    private function rentalStatus($daysLeft): string
    {
        if ($daysLeft === null) {
            return 'unknown';
        }

        if ($daysLeft < 0) {
            return 'expired';
        }

        if ($daysLeft === 0) {
            return 'ends_today';
        }

        if ($daysLeft <= 7) {
            return 'ending_soon';
        }

        return 'active';
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