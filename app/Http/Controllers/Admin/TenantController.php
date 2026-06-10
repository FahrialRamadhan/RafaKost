<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Services\SettingService;

class TenantController extends Controller
{
	public function index(Request $request)
	{
	    $query = Booking::with(['user', 'kamar'])
	        ->where('payment_status', 'paid');
	
	    if ($request->filled('search')) {
	        $search = $request->search;
	
	        $query->where(function ($q) use ($search) {
	            $q->where('invoice', 'like', '%' . $search . '%')
	                ->orWhere('customer_name', 'like', '%' . $search . '%')
	                ->orWhere('customer_phone', 'like', '%' . $search . '%')
	                ->orWhere('customer_email', 'like', '%' . $search . '%')
	                ->orWhereHas('user', function ($userQuery) use ($search) {
	                    $userQuery->where('name', 'like', '%' . $search . '%')
	                        ->orWhere('email', 'like', '%' . $search . '%');
	                })
	                ->orWhereHas('kamar', function ($kamarQuery) use ($search) {
	                    $kamarQuery->where('nama', 'like', '%' . $search . '%');
	                });
	        });
	    }
	
	    /*
	    |--------------------------------------------------------------------------
	    | Tampilkan 1 data terakhir per user + kamar
	    |--------------------------------------------------------------------------
	    | Kalau user memperpanjang kamar yang sama berkali-kali,
	    | yang muncul hanya invoice paid terbaru.
	    */
	    $tenantCollection = $query
	        ->latest('paid_at')
	        ->get()
	        ->unique(function ($booking) {
	            $userKey = $booking->user_id
	                ?: 'guest-' . ($booking->customer_email ?? '-') . '-' . ($booking->customer_phone ?? '-');
	
	            return $userKey . '-' . $booking->kamar_id;
	        })
	        ->values();
	
	    /*
	    |--------------------------------------------------------------------------
	    | Manual Pagination
	    |--------------------------------------------------------------------------
	    */
	    $perPage = 15;
	    $page = (int) $request->get('page', 1);
	
	    $tenants = new \Illuminate\Pagination\LengthAwarePaginator(
	        $tenantCollection->forPage($page, $perPage)->values(),
	        $tenantCollection->count(),
	        $perPage,
	        $page,
	        [
	            'path' => $request->url(),
	            'query' => $request->query(),
	        ]
	    );
	
	    /*
	    |--------------------------------------------------------------------------
	    | Statistik juga dihitung unik per user + kamar
	    |--------------------------------------------------------------------------
	    */
	    $paidBookings = Booking::where('payment_status', 'paid')
	        ->latest('paid_at')
	        ->get()
	        ->unique(function ($booking) {
	            $userKey = $booking->user_id
	                ?: 'guest-' . ($booking->customer_email ?? '-') . '-' . ($booking->customer_phone ?? '-');
	
	            return $userKey . '-' . $booking->kamar_id;
	        })
	        ->values();
	
	    $today = now()->startOfDay();
	
	    $activeCount = $paidBookings->filter(function ($booking) use ($today) {
	        $tanggalHabis = $this->getTanggalHabis($booking);
	
	        return $tanggalHabis && $tanggalHabis->greaterThanOrEqualTo($today);
	    })->count();
	
	    $expiredCount = $paidBookings->filter(function ($booking) use ($today) {
	        $tanggalHabis = $this->getTanggalHabis($booking);
	
	        return $tanggalHabis && $tanggalHabis->lessThan($today);
	    })->count();
	
	    $willExpireSoonCount = $paidBookings->filter(function ($booking) use ($today) {
	        $tanggalHabis = $this->getTanggalHabis($booking);
	
	        if (! $tanggalHabis) {
	            return false;
	        }
	
	        $daysLeft = $today->diffInDays($tanggalHabis, false);
	
	        return $daysLeft >= 0 && $daysLeft <= 7;
	    })->count();
	
	    $stats = [
	        'total_paid' => $paidBookings->count(),
	        'active' => $activeCount,
	        'soon' => $willExpireSoonCount,
	        'expired' => $expiredCount,
	    ];
	
	    return view('admin.tenants.index', compact('tenants', 'stats'));
	}

    public function edit(Booking $booking)
    {
        if (strtolower($booking->payment_status) !== 'paid') {
            abort(404);
        }

        $tanggalHabisDefault = $this->getTanggalHabis($booking);

        return view('admin.tenants.edit', compact('booking', 'tanggalHabisDefault'));
    }

	public function update(Request $request, Booking $booking, SettingService $settings)
	{
	    if (strtolower($booking->payment_status) !== 'paid') {
	        abort(404);
	    }
	
	    $request->validate([
	        'tanggal_habis_custom' => ['required', 'date'],
	    ]);
	
	    $tanggalMasuk = Carbon::parse($booking->tanggal_masuk)->startOfDay();
	    $tanggalHabisBaru = Carbon::parse($request->tanggal_habis_custom)->startOfDay();
	
	    if ($tanggalHabisBaru->lessThan($tanggalMasuk)) {
	        return back()
	            ->withInput()
	            ->with('error', 'Tanggal habis tidak boleh lebih awal dari tanggal masuk.');
	    }
	
	    $booking->forceFill([
	        'tanggal_habis_custom' => $tanggalHabisBaru->toDateString(),
	    ])->save();
	
	    $this->refreshPendingRenewalLateFee($booking, $tanggalHabisBaru, $settings);
	
	    return redirect()
	        ->route('admin.tenants.index')
	        ->with('success', 'Masa sewa penyewa berhasil diperbarui dan denda invoice pending dihitung ulang.');
	}
	private function refreshPendingRenewalLateFee(
	    Booking $paidBooking,
	    Carbon $tanggalHabisBaru,
	    SettingService $settings
	): void {
	    $lateFeeEnabled = (bool) $settings->get('late_fee.enabled', false);
	
	    if (! $lateFeeEnabled) {
	        return;
	    }
	
	    $graceDays = (int) $settings->get('late_fee.grace_days', 0);
	    $lateFeePerDay = (int) $settings->get('late_fee.amount_per_day', 10000);
	
	    $today = now()->startOfDay();
	
	    $dueDate = $tanggalHabisBaru->copy()->startOfDay();
	    $effectiveDueDate = $dueDate->copy()->addDays($graceDays);
	
	    $lateDays = $effectiveDueDate->diffInDays($today, false);
	
	    if ($lateDays < 0) {
	        $lateDays = 0;
	    }
	
	    $lateFee = $lateDays * $lateFeePerDay;
	
	    $pendingQuery = Booking::where('payment_status', 'pending')
	        ->where('kamar_id', $paidBooking->kamar_id)
	        ->whereNotNull('due_date');
	
	    if ($paidBooking->user_id) {
	        $pendingQuery->where('user_id', $paidBooking->user_id);
	    } else {
	        $pendingQuery
	            ->where('customer_email', $paidBooking->customer_email)
	            ->where('customer_phone', $paidBooking->customer_phone);
	    }
	
	    $pendingBookings = $pendingQuery->get();
	
	    foreach ($pendingBookings as $pendingBooking) {
	        $hargaSewa = (int) ($pendingBooking->total_harga ?? 0);
	        $paymentFee = (int) ($pendingBooking->payment_fee ?? 0);
	
	        $pendingBooking->forceFill([
	            'due_date' => $dueDate->toDateString(),
	            'late_days' => $lateDays,
	            'late_fee' => $lateFee,
	            'payment_total' => $hargaSewa + $paymentFee + $lateFee,
	        ])->save();
	    }
	}

    private function getTanggalHabis(Booking $booking): ?Carbon
    {
        if ($booking->tanggal_habis_custom) {
            return Carbon::parse($booking->tanggal_habis_custom)->startOfDay();
        }

        if (! $booking->tanggal_masuk || ! $booking->durasi) {
            return null;
        }

        return Carbon::parse($booking->tanggal_masuk)
            ->addMonths((int) $booking->durasi)
            ->startOfDay();
    }
}