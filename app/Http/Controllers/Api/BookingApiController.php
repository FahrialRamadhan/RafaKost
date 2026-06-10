<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Kamar;
use App\Models\PaymentGateway;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BookingApiController extends Controller
{
    public function store(Request $request)
    {
        $user = $this->userFromToken($request);

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'Token tidak valid.',
            ], 401);
        }
		if ($user->identity_status !== 'approved') {
		    return response()->json([
		        'success' => false,
		        'message' => 'Akun kamu belum terverifikasi. Silakan verifikasi dokumen terlebih dahulu sebelum booking kamar.',
		        'identity_status' => $user->identity_status,
		    ], 403);
		}

        $request->validate([
            'kamar_id' => ['required', 'exists:kamars,id'],
            'tanggal_masuk' => ['required', 'date'],
            'durasi' => ['required', 'integer', 'min:1', 'max:12'],
            'orang' => ['required', 'integer', 'min:1', 'max:2'],

            'customer_name' => ['required', 'string', 'max:150'],
            'customer_phone' => ['required', 'string', 'max:30'],
            'customer_email' => ['nullable', 'email', 'max:150'],
            'customer_address' => ['nullable', 'string', 'max:1000'],
            'customer_note' => ['nullable', 'string', 'max:1000'],
        ]);
		$pendingBooking = Booking::where('user_id', $user->id)
		    ->where('payment_status', 'pending')
		    ->latest()
		    ->first();
		
		if ($pendingBooking) {
		    return response()->json([
		        'success' => false,
		        'message' => 'Kamu masih punya pembayaran yang belum selesai. Batalkan invoice lama terlebih dahulu sebelum booking kamar baru.',
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

        $kamar = Kamar::where('id', $request->kamar_id)
            ->where('status', 'tersedia')
            ->first();

        if (! $kamar) {
            return response()->json([
                'success' => false,
                'message' => 'Kamar tidak tersedia.',
            ], 422);
        }

        $hasActiveRental = Booking::where('user_id', $user->id)
            ->where('payment_status', 'paid')
            ->whereHas('kamar')
            ->get()
            ->contains(function ($booking) {
                $tanggalMasuk = $booking->tanggal_masuk
                    ? Carbon::parse($booking->tanggal_masuk)->startOfDay()
                    : null;

                if (! $tanggalMasuk) {
                    return false;
                }

                $tanggalHabis = $booking->tanggal_habis_custom
                    ? Carbon::parse($booking->tanggal_habis_custom)->startOfDay()
                    : $tanggalMasuk->copy()->addMonths((int) $booking->durasi);

                return $tanggalHabis->greaterThanOrEqualTo(now()->startOfDay());
            });

        if ($hasActiveRental) {
            return response()->json([
                'success' => false,
                'message' => 'Kamu masih memiliki kamar aktif. Satu akun hanya bisa menyewa satu kamar aktif.',
            ], 422);
        }

        $gateway = PaymentGateway::where('is_active', true)->first();

        if (! $gateway) {
            return response()->json([
                'success' => false,
                'message' => 'Payment gateway belum diaktifkan oleh admin.',
            ], 422);
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
            'user_id' => $user->id,

            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'customer_email' => $request->customer_email ?: $user->email,
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

        return response()->json([
            'success' => true,
            'message' => 'Invoice booking berhasil dibuat.',
            'invoice' => $booking->invoice,
            'booking' => [
                'id' => $booking->id,
                'invoice' => $booking->invoice,
                'kamar_id' => $booking->kamar_id,
                'tanggal_masuk' => $booking->tanggal_masuk,
                'durasi' => (int) $booking->durasi,
                'orang' => (int) $booking->orang,
                'total_harga' => (int) $booking->total_harga,
                'payment_total' => (int) $booking->payment_total,
                'payment_status' => $booking->payment_status,
            ],
        ]);
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