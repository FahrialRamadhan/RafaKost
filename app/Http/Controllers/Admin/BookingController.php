<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\BookingPaidMail;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['user', 'kamar'])->latest();

        if ($request->filled('status')) {
            $query->where('payment_status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('invoice', 'like', '%' . $search . '%')
                    ->orWhere('payment_gateway', 'like', '%' . $search . '%')
                    ->orWhere('payment_method_name', 'like', '%' . $search . '%')
                    ->orWhere('payment_method_code', 'like', '%' . $search . '%')
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', '%' . $search . '%')
                            ->orWhere('email', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('kamar', function ($kamarQuery) use ($search) {
                        $kamarQuery->where('nama', 'like', '%' . $search . '%');
                    });
            });
        }

        $bookings = $query->paginate(15)->withQueryString();

        $stats = [
            'total' => Booking::count(),
            'pending' => Booking::where('payment_status', 'pending')->count(),
            'paid' => Booking::where('payment_status', 'paid')->count(),
            'failed' => Booking::where('payment_status', 'failed')->count(),
            'expired' => Booking::where('payment_status', 'expired')->count(),
            'canceled' => Booking::where('payment_status', 'canceled')->count(),
        ];

        return view('admin.bookings.index', compact('bookings', 'stats'));
    }

    public function show(Booking $booking)
    {
        $booking->load(['user', 'kamar']);

        return view('admin.bookings.show', compact('booking'));
    }

    private function sendPaidEmail(Booking $booking): void
    {
        try {
            $booking->loadMissing(['kamar', 'user']);

            $email = $booking->customer_email ?: ($booking->user->email ?? null);

            if (! $email) {
                Log::warning('Email invoice tidak dikirim karena email kosong', [
                    'invoice' => $booking->invoice,
                ]);

                return;
            }

            Mail::to($email)->send(new BookingPaidMail($booking));

            Log::info('Email invoice PAID berhasil dikirim dari admin manual', [
                'invoice' => $booking->invoice,
                'email' => $email,
            ]);
        } catch (\Throwable $e) {
            Log::error('Gagal mengirim email invoice PAID dari admin manual: ' . $e->getMessage(), [
                'invoice' => $booking->invoice,
            ]);
        }
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'payment_status' => ['required', 'in:pending,paid,failed,expired,canceled'],
        ]);

        $status = $request->payment_status;
        $wasAlreadyPaid = $booking->payment_status === 'paid';

        $data = [
            'payment_status' => $status,
        ];

        if ($status === 'paid') {
            $data['paid_at'] = $booking->paid_at ?: now();

            if ($booking->kamar) {
                $booking->kamar->update([
                    'status' => 'terisi',
                ]);
            }
        } else {
            $data['paid_at'] = null;
        }

        if ($status === 'canceled') {
            $data['payment_url'] = null;
            $data['qr_string'] = null;
            $data['transaction_id'] = null;
            $data['reference_id'] = null;
        }

        $booking->update($data);

        if ($status === 'paid' && ! $wasAlreadyPaid) {
            $this->sendPaidEmail($booking);
        }

        return back()->with(
            'success',
            'Status booking berhasil diubah menjadi ' . strtoupper($status) . '. Email invoice dikirim jika status PAID dan email tersedia.'
        );
    }

    public function markPaid(Booking $booking)
    {
        $wasAlreadyPaid = $booking->payment_status === 'paid';

        $booking->update([
            'payment_status' => 'paid',
            'paid_at' => $booking->paid_at ?: now(),
        ]);

        if ($booking->kamar) {
            $booking->kamar->update([
                'status' => 'terisi',
            ]);
        }

        if (! $wasAlreadyPaid) {
            $this->sendPaidEmail($booking);
        }

        return back()->with('success', 'Booking berhasil dikonfirmasi manual sebagai PAID. Email invoice dikirim jika email tersedia.');
    }

    public function destroy(Booking $booking)
    {
        $booking->delete();

        return redirect()
            ->route('admin.bookings.index')
            ->with('success', 'Booking berhasil dihapus.');
    }
}