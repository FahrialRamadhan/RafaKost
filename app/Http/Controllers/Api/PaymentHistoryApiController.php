<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PaymentHistoryApiController extends Controller
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

        $bookings = Booking::with('kamar')
            ->where('user_id', $user->id)
            ->latest()
            ->get()
            ->map(function ($booking) use ($user) {
                /*
                |--------------------------------------------------------------------------
                | Blokir pembayaran kalau dokumen belum approved
                |--------------------------------------------------------------------------
                | Kalau user belum verifikasi, data payment lama disembunyikan.
                | Ini mencegah APK lama langsung membuka PaymentPage / QRIS lama.
                */
                $isVerified = $user->identity_status === 'approved';

                $canPay = $isVerified && $booking->payment_status === 'pending';

                $paymentMethodCode = $isVerified ? $booking->payment_method_code : null;
                $paymentMethodName = $isVerified ? $booking->payment_method_name : null;
                $transactionId = $isVerified ? $booking->transaction_id : null;
                $paymentUrl = $isVerified ? $booking->payment_url : null;
                $qrString = $isVerified ? $booking->qr_string : null;

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
                    ] : null,

                    'tanggal_masuk' => $booking->tanggal_masuk,
                    'durasi' => (int) $booking->durasi,
                    'orang' => (int) $booking->orang,

                    'total_harga' => (int) $booking->total_harga,
                    'payment_fee' => (int) $booking->payment_fee,
                    'payment_total' => (int) $booking->payment_total,

                    'payment_gateway' => $booking->payment_gateway,
                    'payment_method_code' => $paymentMethodCode,
                    'payment_method_name' => $paymentMethodName,
                    'payment_status' => $booking->payment_status,

                    'transaction_id' => $transactionId,
                    'payment_url' => $paymentUrl,
                    'qr_string' => $qrString,

                    'identity_status' => $user->identity_status,
                    'can_pay' => $canPay,
                    'pay_blocked_message' => $canPay
                        ? null
                        : (
                            $user->identity_status !== 'approved'
                                ? 'Akun kamu belum terverifikasi. Silakan verifikasi dokumen terlebih dahulu sebelum melakukan pembayaran.'
                                : 'Invoice ini tidak bisa dilanjutkan pembayaran.'
                        ),

                    'methods_endpoint' => '/api/invoices/' . $booking->invoice . '/methods',
                    'choose_method_endpoint' => '/api/invoices/' . $booking->invoice . '/choose-method',

                    'paid_at' => $booking->paid_at,
                    'created_at' => $booking->created_at
                        ? Carbon::parse($booking->created_at)->format('Y-m-d H:i:s')
                        : null,
                ];
            });

        return response()->json([
            'success' => true,
            'identity_status' => $user->identity_status,
            'data' => $bookings,
        ]);
    }

    public function cancel(Request $request, $booking)
    {
        $user = $this->userFromToken($request);

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'Token tidak valid.',
            ], 401);
        }

        $booking = Booking::where('id', $booking)
            ->where('user_id', $user->id)
            ->first();

        if (! $booking) {
            return response()->json([
                'success' => false,
                'message' => 'Invoice tidak ditemukan.',
            ], 404);
        }

        if ($booking->payment_status === 'paid') {
            return response()->json([
                'success' => false,
                'message' => 'Invoice yang sudah dibayar tidak bisa dibatalkan.',
            ], 422);
        }

        if ($booking->payment_status === 'canceled') {
            return response()->json([
                'success' => false,
                'message' => 'Invoice ini sudah dibatalkan.',
            ], 422);
        }

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

        return response()->json([
            'success' => true,
            'message' => 'Invoice berhasil dibatalkan.',
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