<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function store(Request $request)
    {
        $user = $request->user();

        $booking = Booking::where('user_id', $user->id)
            ->where('payment_status', 'paid')
            ->latest('paid_at')
            ->first();

        if (! $booking) {
            return back()->with('error', 'Testimoni hanya bisa diisi oleh penyewa aktif.');
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

        return back()->with('success', 'Terima kasih! Testimoni kamu berhasil dikirim.');
    }
}