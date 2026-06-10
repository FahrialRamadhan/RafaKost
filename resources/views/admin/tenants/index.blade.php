@extends('layouts.app')

@section('content')
@php
    use Carbon\Carbon;
@endphp

<div style="min-height: 100vh; background: #f8fafc; padding: 2rem 1.5rem; font-family: 'DM Sans', ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, sans-serif;">
    <div style="max-width: 1200px; margin: 0 auto;">

        {{-- Hero Banner --}}
<div class="relative overflow-hidden rounded-2xl mb-8"
     style="background-image: url('{{ asset('images/frame.png') }}'); background-size: cover; background-position: center; background-repeat: no-repeat;">


    <div class="relative px-8 py-10 md:py-12">
        <h1 class="text-3xl md:text-4xl font-bold text-white mb-2">
            Daftar Penyewa
        </h1>
        <p class="text-sm md:text-base mb-5 max-w-2xl" style="color: rgba(255,255,255,0.75);">
            Monitoring penyewa aktif, tanggal masuk, tanggal habis, dan status masa sewa.
        </p>

        <div class="flex flex-wrap items-center gap-3">
            <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-semibold text-white"
                  style="background-color: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.2);">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Waktu server: {{ now()->format('d/m/Y H:i:s') }} WIB
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
        {{-- Section: Ringkasan Status --}}
<div class="mb-8">
    <div class="flex items-center gap-2 mb-6">
        <svg class="w-5 h-5 text-slate-600" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="4"/><path d="M12 2v2"/><path d="M12 20v2"/><path d="m4.93 4.93 1.41 1.41"/><path d="m17.66 17.66 1.41 1.41"/><path d="M2 12h2"/><path d="M20 12h2"/><path d="m6.34 17.66-1.41 1.41"/><path d="m19.07 4.93-1.41 1.41"/>
        </svg>
        <h2 class="text-sm font-bold text-slate-700">Ringkasan Status</h2>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
        
        {{-- 1. Total Paid --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm flex flex-col items-center text-center">
            <svg class="w-6 h-6 text-slate-800 mb-3" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/>
            </svg>
            <p class="text-sm text-slate-500 font-medium mb-1">Total Paid</p>
            <p class="text-3xl font-bold text-slate-900">{{ $stats['total_paid'] ?? 0 }}</p>
        </div>

        {{-- 2. Aktif --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm flex flex-col items-center text-center">
            <svg class="w-6 h-6 text-slate-800 mb-3" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <rect width="18" height="18" x="3" y="3" rx="2"/><path d="m9 12 2 2 4-4"/>
            </svg>
            <p class="text-sm text-slate-500 font-medium mb-1">Aktif</p>
            <p class="text-3xl font-bold text-slate-900">{{ $stats['active'] ?? 0 }}</p>
        </div>

        {{-- 3. Habis ≤ 7 Hari --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm flex flex-col items-center text-center">
            <svg class="w-6 h-6 text-slate-800 mb-3" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
            </svg>
            <p class="text-sm text-slate-500 font-medium mb-1">Habis ≤ 7 Hari</p>
            <p class="text-3xl font-bold text-slate-900">{{ $stats['soon'] ?? 0 }}</p>
        </div>

        {{-- 4. Expired --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm flex flex-col items-center text-center">
            <svg class="w-6 h-6 text-slate-800 mb-3" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/>
            </svg>
            <p class="text-sm text-slate-500 font-medium mb-1">Expired</p>
            <p class="text-3xl font-bold text-slate-900">{{ $stats['expired'] ?? 0 }}</p>
        </div>

    </div>
</div>
		
        {{-- Search & Filter --}}
        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 1.25rem; margin-bottom: 1.5rem; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);">
            <form method="GET" action="{{ route('admin.tenants.index') }}" style="display: flex; flex-wrap: wrap; gap: 10px;">
                <div style="flex-grow: 1; min-width: 280px; position: relative;">
                    <svg style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%);" width="18" height="18" fill="none" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Cari nama penyewa, invoice, kamar, WA, email..."
                           style="width: 100%; border: 1px solid #cbd5e1; border-radius: 10px; padding: 12px 16px 12px 40px; font-size: 0.875rem; box-sizing: border-box; outline: none;">
                </div>

                <button type="submit"
                        style="border: 0; background: #091413; color: #ffffff; padding: 12px 24px; border-radius: 10px; font-size: 0.875rem; font-weight: 700; cursor: pointer; box-shadow: 0 2px 4px rgba(37, 99, 235, 0.2);">
                    Filter
                </button>

                <a href="{{ route('admin.tenants.index') }}"
                   style="display: flex; align-items: center; background: #f1f5f9; color: #475569; padding: 12px 24px; border-radius: 10px; font-size: 0.875rem; font-weight: 700; text-decoration: none;">
                    Reset
                </a>
            </form>
        </div>

        {{-- Data Table --}}
        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);">
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 1.25rem 1.5rem; border-bottom: 1px solid #f1f5f9;">
                <h2 style="font-size: 1.1rem; font-weight: 800; color: #0f172a; margin: 0;">Daftar Penyewa Aktif / Paid</h2>
                <span style="font-size: 0.75rem; font-weight: 600; color: #64748b; background: #f1f5f9; padding: 4px 10px; border-radius: 999px;">
                    {{ $tenants->total() }} data
                </span>
            </div>

            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; font-size: 0.875rem;">
                    <thead>
                        <tr style="background: #091413; border-bottom: 1px solid #e2e8f0;">
                            <th style="padding: 14px 20px; text-align: left; font-weight: 700; color: #ffffff; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em;">Penyewa</th>
                            <th style="padding: 14px 20px; text-align: left; font-weight: 700; color: #ffffff; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em;">Kamar</th>
                            <th style="padding: 14px 20px; text-align: left; font-weight: 700; color: #ffffff; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em;">Tanggal Masuk</th>
                            <th style="padding: 14px 20px; text-align: left; font-weight: 700; color: #ffffff; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em;">Tanggal Habis</th>
                            <th style="padding: 14px 20px; text-align: left; font-weight: 700; color: #ffffff; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em;">Sisa</th>
                            <th style="padding: 14px 20px; text-align: left; font-weight: 700; color: #ffffff; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em;">Payment</th>
                            <th style="padding: 14px 20px; text-align: left; font-weight: 700; color: #ffffff; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em;">Total</th>
                            <th style="padding: 14px 20px; text-align: left; font-weight: 700; color: #ffffff; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em;">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($tenants as $booking)
                            @php
                                $tanggalMasuk = $booking->tanggal_masuk ? Carbon::parse($booking->tanggal_masuk) : null;

                                $tanggalHabis = $booking->tanggal_habis_custom
                                    ? Carbon::parse($booking->tanggal_habis_custom)
                                    : ($tanggalMasuk ? $tanggalMasuk->copy()->addMonths((int) $booking->durasi) : null);

                                $today = now()->startOfDay();

                                $isExpired = $tanggalHabis ? $tanggalHabis->lessThan($today) : false;
                                $daysLeft = $tanggalHabis ? $today->diffInDays($tanggalHabis, false) : null;

                                if ($daysLeft === null) {
                                    $statusText = '-';
                                    $badgeBg = '#f1f5f9';
                                    $badgeColor = '#64748b';
                                } elseif ($daysLeft < 0) {
                                    $statusText = 'Expired ' . abs($daysLeft) . ' hari';
                                    $badgeBg = '#fee2e2';
                                    $badgeColor = '#dc2626';
                                } elseif ($daysLeft === 0) {
                                    $statusText = 'Habis hari ini';
                                    $badgeBg = '#fef3c7';
                                    $badgeColor = '#d97706';
                                } elseif ($daysLeft <= 7) {
                                    $statusText = $daysLeft . ' hari lagi';
                                    $badgeBg = '#fef3c7';
                                    $badgeColor = '#d97706';
                                } else {
                                    $statusText = $daysLeft . ' hari lagi';
                                    $badgeBg = '#dcfce7';
                                    $badgeColor = '#16a34a';
                                }
                            @endphp

                            <tr style="border-bottom: 1px solid #f1f5f9;">
                                <td style="padding: 16px 20px; vertical-align: top;">
                                    <div style="font-weight: 800; color: #0f172a; margin-bottom: 2px;">
                                        {{ $booking->customer_name ?: ($booking->user->name ?? '-') }}
                                    </div>
                                    <div style="font-size: 0.75rem; color: #64748b; margin-bottom: 2px;">
                                        {{ $booking->customer_phone ?: '-' }}
                                    </div>
                                    <div style="font-size: 0.75rem; color: #94a3b8;">
                                        {{ $booking->customer_email ?: ($booking->user->email ?? '-') }}
                                    </div>
                                    <div style="font-size: 0.7rem; font-weight: 700; color: #64748b; margin-top: 6px; padding: 2px 6px; background: #f1f5f9; border-radius: 4px; display: inline-block;">
                                        INV: {{ $booking->invoice }}
                                    </div>
                                </td>

                                <td style="padding: 16px 20px; vertical-align: top;">
                                    <div style="font-weight: 800; color: #0f172a; margin-bottom: 2px;">{{ $booking->kamar->nama ?? '-' }}</div>
                                    <div style="font-size: 0.75rem; color: #64748b;">{{ $booking->durasi }} bulan · {{ $booking->orang }} orang</div>
                                </td>

                                <td style="padding: 16px 20px; vertical-align: top;">
                                    <div style="font-weight: 700; color: #0f172a; margin-bottom: 2px;">
                                        {{ $tanggalMasuk ? $tanggalMasuk->format('d/m/Y') : '-' }}
                                    </div>
                                    <div style="font-size: 0.75rem; color: #64748b;">
                                        Paid: {{ $booking->paid_at ? $booking->paid_at->format('d/m/Y H:i') : '-' }}
                                    </div>
                                </td>

                                <td style="padding: 16px 20px; vertical-align: top;">
                                    <div style="font-weight: 800; color: #0f172a;">
                                        {{ $tanggalHabis ? $tanggalHabis->format('d/m/Y') : '-' }}
                                    </div>
                                    @if($booking->tanggal_habis_custom)
                                        <div style="display: inline-block; font-size: 0.65rem; color: #0284c7; background: #e0f2fe; padding: 2px 6px; border-radius: 4px; font-weight: 700; margin-top: 4px;">
                                            Custom
                                        </div>
                                    @endif
                                </td>

                                <td style="padding: 16px 20px; vertical-align: top;">
                                    <span style="display: inline-block; padding: 6px 12px; border-radius: 9999px; background: {{ $badgeBg }}; color: {{ $badgeColor }}; font-size: 0.75rem; font-weight: 800; white-space: nowrap;">
                                        {{ $statusText }}
                                    </span>
                                </td>

                                <td style="padding: 16px 20px; vertical-align: top;">
                                    <div style="font-weight: 800; color: #0f172a; margin-bottom: 2px;">{{ strtoupper($booking->payment_gateway ?? '-') }}</div>
                                    <div style="font-size: 0.75rem; color: #64748b;">{{ $booking->payment_method_name ?? '-' }}</div>
                                </td>

                                <td style="padding: 16px 20px; vertical-align: top;">
                                    <div style="font-weight: 800; color: #0f172a;">
                                        Rp {{ number_format($booking->payment_total ?: $booking->total_harga, 0, ',', '.') }}
                                    </div>
                                </td>

                                <td style="padding: 16px 20px; vertical-align: top;">
                                    <a href="{{ route('admin.tenants.edit', $booking) }}"
                                       style="display: inline-flex; align-items: center; gap: 4px; background: #091413; color: #ffffff; padding: 8px 12px; border-radius: 8px; text-decoration: none; font-size: 0.75rem; font-weight: 800; white-space: nowrap; transition: background-color 0.2s;">
                                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                        Edit
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" style="padding: 40px 20px; text-align: center; color: #64748b;">
                                    <div style="display: flex; flex-direction: column; align-items: center; gap: 12px;">
                                        <svg width="48" height="48" fill="none" viewBox="0 0 24 24"><path d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" stroke="#cbd5e1" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                        <span style="font-weight: 600;">Belum ada penyewa dengan status paid.</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div style="padding: 16px 24px; border-top: 1px solid #f1f5f9; background: #ffffff;">
                {{ $tenants->links() }}
            </div>

        </div>

    </div>
</div>

{{-- Flash Messages --}}
@if(session('success'))
    <div id="toast-success" style="position: fixed; bottom: 40px; left: 50%; transform: translateX(-50%); z-index: 9999; background: #dcfce7; color: #16a34a; padding: 8px 24px 8px 8px; border-radius: 9999px; font-size: 0.875rem; font-weight: 600; display: flex; align-items: center; gap: 12px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); transition: opacity 0.5s ease-out;">
        
        {{-- Ikon Lingkaran Hijau Solid --}}
        <div style="background: #22c55e; color: white; width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; border-radius: 50%; flex-shrink: 0;">
            {{-- Lucide: Check --}}
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
        </div>
        
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div id="toast-error" style="position: fixed; bottom: 40px; left: 50%; transform: translateX(-50%); z-index: 9999; background: #fee2e2; color: #dc2626; padding: 8px 24px 8px 8px; border-radius: 9999px; font-size: 0.875rem; font-weight: 600; display: flex; align-items: center; gap: 12px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); transition: opacity 0.5s ease-out;">
        
        {{-- Ikon Lingkaran Merah Solid --}}
        <div style="background: #ef4444; color: white; width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; border-radius: 50%; flex-shrink: 0;">
            {{-- Lucide: X (Silang) --}}
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
        </div>
        
        {{ session('error') }}
    </div>
@endif

{{-- Script untuk menghilangkan notif secara otomatis setelah 3 detik --}}
<script>
    setTimeout(function() {
        let successToast = document.getElementById('toast-success');
        let errorToast = document.getElementById('toast-error');
        
        if (successToast) {
            successToast.style.opacity = '0';
            setTimeout(() => successToast.remove(), 500); // Hapus elemen dari DOM setelah animasi fade
        }
        
        if (errorToast) {
            errorToast.style.opacity = '0';
            setTimeout(() => errorToast.remove(), 500);
        }
    }, 3000); // 3000ms = 3 detik
</script>
@endsection