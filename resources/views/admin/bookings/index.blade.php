@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 py-8 px-4">
    <div class="max-w-7xl mx-auto">

        {{-- Hero Banner --}}
<div class="relative overflow-hidden rounded-2xl mb-8"
     style="background-image: url('{{ asset('images/frame.png') }}'); background-size: cover; background-position: center; background-repeat: no-repeat;">
    <div class="relative px-8 py-10 md:py-12">
        <h1 class="text-3xl md:text-4xl font-bold text-white mb-2">
            Status Order / Booking
        </h1>
        <p class="text-sm md:text-base mb-5 max-w-2xl" style="color: rgba(255,255,255,0.75);">
            Pantau status pembayaran dan konfirmasi manual jika callback payment gateway gagal.
        </p>

        <div class="flex flex-wrap items-center gap-3">
            <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-semibold text-white"
                  style="background-color: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.2);">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Total {{ $stats['total'] ?? 8 }} Booking
            </span>

            <a href="{{ route('admin.dashboard') }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-semibold text-white transition"
               style="background-color: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.2);"
               onmouseover="this.style.backgroundColor='rgba(255,255,255,0.18)'"
               onmouseout="this.style.backgroundColor='rgba(255,255,255,0.08)'">
                ← Kembali ke Dashboard
            </a>
        </div>
    </div>
</div>

        @if(session('success'))
            <div class="mb-6 p-4 rounded-2xl text-sm font-medium"
                 style="background-color: #f0fdf4; border: 1px solid #bbf7d0; color: #15803d;">
                {{ session('success') }}
            </div>
        @endif

{{-- Section: Ringkasan Status --}}
<div class="mb-8">
    <div class="flex items-center gap-2 mb-6">
        <svg class="w-5 h-5 text-slate-600" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="4"/><path d="M12 2v2"/><path d="M12 20v2"/><path d="m4.93 4.93 1.41 1.41"/><path d="m17.66 17.66 1.41 1.41"/><path d="M2 12h2"/><path d="M20 12h2"/><path d="m6.34 17.66-1.41 1.41"/><path d="m19.07 4.93-1.41 1.41"/>
        </svg>
        <h2 class="text-sm font-bold text-slate-700">Ringkasan Status</h2>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-5">
        
        {{-- 1. Total Order --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm flex flex-col items-center text-center">
            <svg class="w-6 h-6 text-slate-800 mb-3" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="22 12 16 12 14 15 10 15 8 12 2 12"/><path d="M5.45 5.11 2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/>
            </svg>
            <p class="text-sm text-slate-500 font-medium mb-1">Total Order</p>
            <p class="text-3xl font-bold text-slate-900 mb-2">{{ $stats['total'] ?? 0 }}</p>
            <p class="text-xs text-slate-400">Seluruh transaksi</p>
        </div>

        {{-- 2. Pending --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm flex flex-col items-center text-center">
            <svg class="w-6 h-6 text-slate-800 mb-3" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
            </svg>
            <p class="text-sm text-slate-500 font-medium mb-1">Pending</p>
            <p class="text-3xl font-bold text-slate-900 mb-2">{{ $stats['pending'] ?? 0 }}</p>
            <p class="text-xs text-slate-400">Menunggu bayar</p>
        </div>

        {{-- 3. Paid --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm flex flex-col items-center text-center">
            <svg class="w-6 h-6 text-slate-800 mb-3" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <rect width="20" height="12" x="2" y="6" rx="2"/><circle cx="12" cy="12" r="2"/><path d="M6 12h.01M18 12h.01"/>
            </svg>
            <p class="text-sm text-slate-500 font-medium mb-1">Paid</p>
            <p class="text-3xl font-bold text-slate-900 mb-2">{{ $stats['paid'] ?? 0 }}</p>
            <p class="text-xs text-slate-400">Lunas dibayar</p>
        </div>

        {{-- 4. Failed --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm flex flex-col items-center text-center">
            <svg class="w-6 h-6 text-slate-800 mb-3" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z"/><line x1="15" x2="9" y1="9" y2="15"/><line x1="9" x2="15" y1="9" y2="15"/>
            </svg>
            <p class="text-sm text-slate-500 font-medium mb-1">Failed</p>
            <p class="text-3xl font-bold text-slate-900 mb-2">{{ $stats['failed'] ?? 0 }}</p>
            <p class="text-xs text-slate-400">Gagal bayar</p>
        </div>

        {{-- 5. Expired --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm flex flex-col items-center text-center">
            <svg class="w-6 h-6 text-slate-800 mb-3" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2-1 4-2.8 5.2-4.14a1 1 0 0 1 1.6 0C13 2.2 15 4 17 5a1 1 0 0 1 1 1z"/><path d="M12 8v4"/><path d="M12 16h.01"/>
            </svg>
            <p class="text-sm text-slate-500 font-medium mb-1">Expired</p>
            <p class="text-3xl font-bold text-slate-900 mb-2">{{ $stats['expired'] ?? 0 }}</p>
            <p class="text-xs text-slate-400">Kedaluwarsa</p>
        </div>

        {{-- 6. Canceled --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm flex flex-col items-center text-center">
            <svg class="w-6 h-6 text-slate-800 mb-3" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/><path d="m15 9-6 6"/><path d="m9 9 6 6"/>
            </svg>
            <p class="text-sm text-slate-500 font-medium mb-1">Canceled</p>
            <p class="text-3xl font-bold text-slate-900 mb-2">{{ $stats['canceled'] ?? 0 }}</p>
            <p class="text-xs text-slate-400">Dibatalkan user</p>
        </div>

    </div>
</div>
         {{-- Section: Filter Pencarian --}}
        <div class="mb-8">
            <div class="flex items-center gap-2 mb-4">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                </svg>
                <h2 class="text-sm font-semibold text-slate-600">Filter Pencarian</h2>
            </div>
 
            <div class="bg-white rounded-2xl border border-slate-100 p-5">
                <form method="GET" action="{{ route('admin.bookings.index') }}"
                      class="flex flex-col md:flex-row gap-3 md:items-end max-w-3xl">
 
                    <div class="w-full md:w-80">
                        <label class="block text-xs font-medium text-slate-500 mb-1.5">Pencarian</label>
                        <div class="relative">
                            <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input type="text"
                                   name="search"
                                   value="{{ request('search') }}"
                                   placeholder="Cari invoice, nama user, kamar, gateway..."
                                   class="w-full rounded-xl pl-10 pr-3 py-2.5 text-sm transition focus:outline-none"
                                   style="border: 1px solid #e2e8f0;"
                                   onfocus="this.style.borderColor='#0f172a'; this.style.boxShadow='0 0 0 3px rgba(15,23,42,0.1)'"
                                   onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">
                        </div>
                    </div>
 
                    <div class="md:w-48">
                        <label class="block text-xs font-medium text-slate-500 mb-1.5">Status</label>
                        <select name="status"
                                class="w-full rounded-xl px-3 py-2.5 text-sm bg-white transition focus:outline-none"
                                style="border: 1px solid #e2e8f0;"
                                onfocus="this.style.borderColor='#0f172a'; this.style.boxShadow='0 0 0 3px rgba(15,23,42,0.1)'"
                                onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
                            <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expired</option>
							<option value="canceled" {{ request('status') === 'canceled' ? 'selected' : '' }}>Canceled</option>
                        </select>
                    </div>
 
                    <div class="flex gap-2">
                        <button type="submit"
                                class="px-5 py-2.5 rounded-xl text-sm font-semibold text-white transition inline-flex items-center gap-1.5"
                                style="background-color: #0f172a; border: none; cursor: pointer;"
                                onmouseover="this.style.backgroundColor='#1e293b'"
                                onmouseout="this.style.backgroundColor='#0f172a'">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Filter
                        </button>
                        <a href="{{ route('admin.bookings.index') }}"
                           class="px-4 py-2.5 rounded-xl text-sm font-semibold transition inline-flex items-center justify-center"
                           style="background-color: #ffffff; color: #475569; border: 1px solid #e2e8f0;"
                           onmouseover="this.style.backgroundColor='#f8fafc'"
                           onmouseout="this.style.backgroundColor='#ffffff'">
                            Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>

        {{-- Section: Daftar Booking --}}
        <div class="mb-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <h2 class="text-sm font-semibold text-slate-600">Daftar Booking</h2>
                </div>
                <p class="text-xs text-slate-400">{{ method_exists($bookings, 'total') ? $bookings->total() : count($bookings) }} entri ditemukan</p>
            </div>

            <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead style="background-color: #0f172a;">
                            <tr>
                                <th class="px-5 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider"style="color: #ffff">Invoice</th>
                                <th class="px-5 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider"style="color: #ffff">Penyewa</th>
                                <th class="px-5 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider"style="color: #ffff">Kamar</th>
                                <th class="px-5 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider"style="color: #ffff">Payment</th>
                                <th class="px-5 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider"style="color: #ffff">Total</th>
                                <th class="px-5 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider"style="color: #ffff">Status</th>
                                <th class="px-5 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider"style="color: #ffff">Tanggal</th>
                                <th class="px-5 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider"style="color: #ffff">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            @forelse($bookings as $booking)
                                <tr class="transition" onmouseover="this.style.backgroundColor='#f8fafc'" onmouseout="this.style.backgroundColor='transparent'">
                                    <td class="px-5 py-4">
                                        <div class="font-bold text-slate-900">{{ $booking->invoice }}</div>
                                        <div class="text-xs text-slate-400 mt-0.5">ID: {{ $booking->id }}</div>
                                    </td>

                                    <td class="px-5 py-4">
                                        <div class="font-semibold text-slate-800">
                                            {{ $booking->user->name ?? '-' }}
                                        </div>
                                        <div class="text-xs text-slate-400 mt-0.5">
                                            {{ $booking->user->email ?? '-' }}
                                        </div>
                                    </td>

                                    <td class="px-5 py-4">
                                        <div class="font-semibold text-slate-800">
                                            {{ $booking->kamar->nama ?? '-' }}
                                        </div>
                                        <div class="text-xs text-slate-400 mt-0.5">
                                            Durasi {{ $booking->durasi }} bulan
                                        </div>
                                    </td>

                                    <td class="px-5 py-4">
                                        <div class="font-semibold text-slate-800">
                                            {{ strtoupper($booking->payment_gateway ?? '-') }}
                                        </div>
                                        <div class="text-xs text-slate-400 mt-0.5">
                                            {{ $booking->payment_method_name ?? '-' }}
                                        </div>
                                    </td>

                                    <td class="px-5 py-4">
                                        <div class="font-bold text-slate-900">
                                            Rp {{ number_format($booking->payment_total ?: $booking->total_harga, 0, ',', '.') }}
                                        </div>
                                        <div class="text-xs text-slate-400 mt-0.5">
                                            Fee Rp {{ number_format($booking->payment_fee ?? 0, 0, ',', '.') }}
                                        </div>
                                    </td>

                                    <td class="px-5 py-4">
                                        @if($booking->payment_status === 'paid')
                                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold"
                                                  style="background-color: #f0fdf4; color: #15803d; border: 1px solid #dcfce7;">
                                                <span class="w-1.5 h-1.5 rounded-full" style="background-color: #22c55e;"></span>
                                                PAID
                                            </span>
                                        @elseif($booking->payment_status === 'failed')
                                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold"
                                                  style="background-color: #fef2f2; color: #b91c1c; border: 1px solid #fee2e2;">
                                                <span class="w-1.5 h-1.5 rounded-full" style="background-color: #ef4444;"></span>
                                                FAILED
                                            </span>
                                        @elseif($booking->payment_status === 'expired')
                                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold"
                                                  style="background-color: #f1f5f9; color: #334155; border: 1px solid #e2e8f0;">
                                                <span class="w-1.5 h-1.5 rounded-full" style="background-color: #64748b;"></span>
                                                EXPIRED
                                            </span>
										@elseif($booking->payment_status === 'canceled')
										    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold"
										          style="background-color: #fee2e2; color: #dc2626; border: 1px solid #fecaca;">
										        <span class="w-1.5 h-1.5 rounded-full" style="background-color: #ef4444;"></span>
										        CANCELED
										    </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold"
                                                  style="background-color: #fefce8; color: #a16207; border: 1px solid #fef9c3;">
                                                <span class="w-1.5 h-1.5 rounded-full" style="background-color: #eab308;"></span>
                                                PENDING
                                            </span>
                                        @endif
										

                                        @if($booking->paid_at)
                                            <div class="text-xs text-slate-400 mt-1.5">
                                                Paid: {{ $booking->paid_at->format('d/m/Y H:i') }}
                                            </div>
                                        @endif
                                    </td>

                                    <td class="px-5 py-4">
                                        <div class="text-slate-700 font-medium">
                                            {{ $booking->created_at ? $booking->created_at->format('d/m/Y H:i') : '-' }}
                                        </div>
                                        <div class="text-xs text-slate-400 mt-0.5">
                                            Masuk: {{ $booking->tanggal_masuk ? $booking->tanggal_masuk->format('d/m/Y') : '-' }}
                                        </div>
                                    </td>

                                    <td class="px-5 py-4">
                                        <a href="{{ route('admin.bookings.show', $booking) }}"
                                           class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-xs font-bold text-white transition"
                                           style="background-color: #0f172a;"
                                           onmouseover="this.style.backgroundColor='#1e293b'"
                                           onmouseout="this.style.backgroundColor='#0f172a'">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-5 py-16 text-center">
                                        <div class="flex flex-col items-center gap-3">
                                            <div class="w-16 h-16 rounded-full flex items-center justify-center" style="background-color: #f1f5f9;">
                                                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-slate-700 font-semibold">Belum ada data booking</p>
                                                <p class="text-xs text-slate-400 mt-1">Data booking akan muncul di sini setelah ada transaksi.</p>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if(method_exists($bookings, 'hasPages') && $bookings->hasPages())
                    <div class="px-5 py-4 border-t border-slate-100" style="background-color: rgba(248, 250, 252, 0.5);">
                        {{ $bookings->links() }}
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection