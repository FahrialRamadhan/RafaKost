<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PaymentHistoryController extends Controller
{
    public function index(Request $request): View
    {
        $bookings = Booking::with('kamar')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return view('payment-history.index', [
            'bookings' => $bookings,
        ]);
    }

    public function cancel(Request $request, Booking $booking): RedirectResponse
    {
        if ((int) $booking->user_id !== (int) $request->user()->id) {
            abort(403);
        }

        if ($booking->payment_status === 'paid') {
            return back()->with('error', 'Invoice yang sudah dibayar tidak bisa dibatalkan.');
        }

        if ($booking->payment_status === 'canceled') {
            return back()->with('error', 'Invoice ini sudah dibatalkan.');
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

        return back()->with('success', 'Invoice berhasil dibatalkan.');
    }
}