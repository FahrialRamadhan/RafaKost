<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function myBookings(Request $request)
    {
        $bookings = Booking::with('kamar')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get()
            ->map(function ($booking) {
                return [
                    'id' => $booking->id,
                    'invoice' => $booking->invoice,

                    'kamar' => [
                        'id' => $booking->kamar->id,
                        'nama' => $booking->kamar->nama,
                        'image' => $booking->kamar->image,
                    ],

                    'tanggal_masuk' => $booking->tanggal_masuk,
                    'durasi' => $booking->durasi,
                    'payment_total' => $booking->payment_total,
                    'payment_status' => $booking->payment_status,
                    'paid_at' => $booking->paid_at,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $bookings
        ]);
    }

    public function store(Request $request)
{
    $request->validate([
        'kamar_id' => 'required|exists:kamars,id',
        'tanggal_masuk' => 'required|date',
        'durasi' => 'required|integer|min:1',
        'orang' => 'required|integer|min:1',
    ]);

    $kamar = \App\Models\Kamar::find($request->kamar_id);

    if ($kamar->status !== 'tersedia') {
        return response()->json([
            'success' => false,
            'message' => 'Kamar tidak tersedia'
        ], 400);
    }

    $harga = $request->orang == 1
        ? $kamar->harga_1_orang
        : $kamar->harga_2_orang;

    $total = $harga * $request->durasi;

    $booking = Booking::create([
        'invoice' => 'INV-' . time(),

        'user_id' => $request->user()->id,

        'customer_name' => $request->user()->name,
        'customer_email' => $request->user()->email,
        'customer_phone' => $request->user()->phone,

        'kamar_id' => $kamar->id,

        'tanggal_masuk' => $request->tanggal_masuk,
        'durasi' => $request->durasi,
        'orang' => $request->orang,

        'total_harga' => $total,
        'payment_total' => $total,

        'payment_status' => 'unpaid',
    ]);

    $kamar->update([
        'status' => 'pending'
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Booking berhasil',
        'data' => $booking
    ]);
}
}