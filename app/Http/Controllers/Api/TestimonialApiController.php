<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Http\Request;

class TestimonialApiController extends Controller
{


	public function index()
	{
	    $testimonials = Testimonial::with(['user', 'booking.kamar'])
	        ->where('is_visible', true)
	        ->latest()
	        ->get()
	        ->map(function ($item) {
	            return [
	                'id' => $item->id,
	                'message' => $item->message,
	                'rating' => $item->rating,
	                'name' => $item->user?->name ?? 'Penghuni',
	                'kamar' => $item->booking?->kamar?->nama,
	                'created_at' => $item->created_at,
	            ];
	        });
	
	    return response()->json([
	        'success' => true,
	        'data' => $testimonials,
	    ]);
	}
    public function popup(Request $request)
    {
        $user = $this->userFromToken($request);

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'Token tidak valid.',
            ], 401);
        }

        $booking = Booking::with('kamar')
            ->where('user_id', $user->id)
            ->where('payment_status', 'paid')
            ->latest('paid_at')
            ->first();

        if (! $booking) {
            return response()->json([
                'success' => true,
                'show_popup' => false,
                'message' => 'Belum ada sewa aktif.',
            ]);
        }

        $hasTestimonial = Testimonial::where('user_id', $user->id)
            ->where('booking_id', $booking->id)
            ->exists();

        return response()->json([
            'success' => true,
            'show_popup' => ! $hasTestimonial,
            'booking' => [
                'id' => $booking->id,
                'invoice' => $booking->invoice,
                'kamar' => $booking->kamar ? [
                    'id' => $booking->kamar->id,
                    'nama' => $booking->kamar->nama,
                    'lantai' => $booking->kamar->lantai,
                    'kamar_mandi' => $booking->kamar->kamar_mandi,
                ] : null,
            ],
        ]);
    }

    public function store(Request $request)
    {
        $user = $this->userFromToken($request);

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'Token tidak valid.',
            ], 401);
        }

        $booking = Booking::where('user_id', $user->id)
            ->where('payment_status', 'paid')
            ->latest('paid_at')
            ->first();

        if (! $booking) {
            return response()->json([
                'success' => false,
                'message' => 'Testimoni hanya bisa diisi oleh penyewa aktif.',
            ], 422);
        }

        $request->validate([
            'message' => ['required', 'string', 'min:10', 'max:500'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
        ], [
            'message.required' => 'Kesan wajib diisi.',
            'message.min' => 'Kesan minimal 10 karakter.',
            'message.max' => 'Kesan maksimal 500 karakter.',
            'rating.required' => 'Rating wajib dipilih.',
        ]);

        Testimonial::updateOrCreate(
            [
                'user_id' => $user->id,
                'booking_id' => $booking->id,
            ],
            [
                'message' => $request->message,
                'rating' => $request->rating,
                'is_visible' => true,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Terima kasih! Testimoni kamu berhasil dikirim.',
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