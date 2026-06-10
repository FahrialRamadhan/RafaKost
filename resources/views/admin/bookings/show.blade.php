@extends('layouts.app')

@section('content')
{{-- Script CDN Tailwind --}}
<script src="https://cdn.tailwindcss.com"></script>

{{-- SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- Style popup --}}
<link rel="stylesheet" href="{{ asset('css/popup-style.css') }}">

<div class="bg-[#f8fafc] min-h-screen py-8 px-4 font-sans text-gray-800">
    <div class="max-w-[1200px] mx-auto">

        {{-- Top Navigation & Header --}}
        <div class="mb-6">
            <a href="{{ route('admin.bookings.index') }}" class="inline-flex items-center gap-1.5 text-[13px] font-semibold text-gray-800 hover:text-black mb-5 transition">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M15 18l-6-6 6-6"/>
                </svg>
                Kembali
            </a>
            
            <div class="flex items-center gap-2">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-700">
                    <circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/>
                </svg>
                <h1 class="text-[17px] font-bold text-gray-900">Detail booking</h1>
            </div>
            <p class="text-[11px] text-gray-400 font-medium ml-7 mt-0.5">{{ $booking->invoice }}</p>
        </div>

        {{-- Alert Success --}}
        @if(session('success'))
            <div class="flex items-center gap-2 px-4 py-3 bg-green-50 border border-green-200 rounded-xl text-green-700 text-[13px] font-medium mb-5">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                {{ session('success') }}
            </div>
        @endif

        {{-- Grid Atas (Info Booking & Status Payment) --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-5">
            
            {{-- Card 1: Informasi Booking --}}
            <div class="md:col-span-2 bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                <div class="flex items-center gap-2 mb-6">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-sky-500">
                        <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1 0-5H20"/>
                    </svg>
                    <h2 class="text-[13px] font-bold text-gray-600 uppercase tracking-wide">INFORMASI BOOKING</h2>
                </div>

                <div class="space-y-4">
                    <div class="flex justify-between items-center text-[12px] border-b border-gray-100 pb-3">
                        <span class="text-gray-500 font-semibold">Invoice</span>
                        <span class="text-gray-900 font-bold">{{ $booking->invoice }}</span>
                    </div>
                    <div class="flex justify-between items-center text-[12px] border-b border-gray-100 pb-3">
                        <span class="text-gray-500 font-semibold">Penyewa</span>
                        <span class="text-gray-900 font-bold">{{ $booking->user->name ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between items-center text-[12px] border-b border-gray-100 pb-3">
                        <span class="text-gray-500 font-semibold">Email</span>
                        <span class="text-gray-900 font-bold">{{ $booking->user->email ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between items-center text-[12px] border-b border-gray-100 pb-3">
                        <span class="text-gray-500 font-semibold">Kamar</span>
                        <span class="text-gray-900 font-bold uppercase">{{ $booking->kamar->nama ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between items-center text-[12px] border-b border-gray-100 pb-3">
                        <span class="text-gray-500 font-semibold">Tanggal Masuk</span>
                        <span class="text-gray-900 font-bold">{{ $booking->tanggal_masuk ? $booking->tanggal_masuk->format('d/m/Y') : '-' }}</span>
                    </div>
                    <div class="flex justify-between items-center text-[12px] border-b border-gray-100 pb-3">
                        <span class="text-gray-500 font-semibold">Orang</span>
                        <span class="text-gray-900 font-bold">{{ $booking->orang }} Orang</span>
                    </div>
                    <div class="flex justify-between items-center text-[12px]">
                        <span class="text-gray-500 font-semibold">Durasi</span>
                        <span class="text-gray-900 font-bold">{{ $booking->durasi }} Bulan</span>
                    </div>
                </div>
            </div>

            {{-- Card 2: Status Payment --}}
            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                <div class="flex items-center gap-2 mb-6">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-sky-500">
                        <rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/>
                    </svg>
                    <h2 class="text-[13px] font-bold text-gray-600 uppercase tracking-wide">STATUS PAYMENT</h2>
                </div>

                <div class="space-y-4">
                    <div>
                        <div class="text-[11px] font-semibold text-gray-500 mb-1.5">Status</div>
                        @php
                            $badgeClass = match($booking->payment_status) {
                                'paid'    => 'bg-green-100 text-green-700',
                                'pending' => 'bg-[#fef9c3] text-[#ca8a04]',
                                'failed'  => 'bg-red-100 text-red-600',
                                default   => 'bg-gray-100 text-gray-600',
                            };
                            $dotClass = match($booking->payment_status) {
                                'paid'    => 'bg-green-600',
                                'pending' => 'bg-[#ca8a04]',
                                'failed'  => 'bg-red-600',
                                default   => 'bg-gray-500',
                            };
                        @endphp
                        <span class="{{ $badgeClass }} px-2 py-1 rounded text-[9px] font-bold tracking-wider uppercase inline-flex items-center gap-1.5">
                            <span class="w-1 h-1 {{ $dotClass }} rounded-full"></span>
                            {{ $booking->payment_status }}
                        </span>
                    </div>
                    <div>
                        <div class="text-[11px] font-semibold text-gray-500 mb-0.5">Gateway</div>
                        <div class="text-[12px] font-bold text-gray-900 uppercase">{{ $booking->payment_gateway ?? '-' }}</div>
                    </div>
                    <div>
                        <div class="text-[11px] font-semibold text-gray-500 mb-0.5">Metode</div>
                        <div class="text-[12px] font-bold text-gray-900 uppercase">{{ $booking->payment_method_name ?? '-' }}</div>
                    </div>
                    <div>
                        <div class="text-[11px] font-semibold text-gray-500 mb-0.5">Reference ID</div>
                        <div class="text-[12px] font-bold text-gray-900 break-all">{{ $booking->reference_id ?? '-' }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 3: Total Pembayaran --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm mb-5">
            <div class="flex items-center gap-2 mb-5">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-sky-500">
                    <line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/>
                </svg>
                <h2 class="text-[13px] font-bold text-gray-600 uppercase tracking-wide">TOTAL PEMBAYARAN</h2>
            </div>

            <div class="space-y-3">
                <div class="flex justify-between items-center text-[12px]">
                    <span class="text-gray-500 font-semibold">Harga Sewa</span>
                    <span class="text-gray-900 font-bold">Rp{{ number_format($booking->total_harga, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center text-[12px] border-b border-gray-100 pb-3">
                    <span class="text-gray-500 font-semibold">Fee</span>
                    <span class="text-gray-900 font-bold">Rp{{ number_format($booking->payment_fee ?? 0, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center pt-1">
                    <span class="text-[12px] text-gray-500 font-semibold">Total Bayar</span>
                    <span class="text-[12.5px] font-bold text-gray-900">Rp{{ number_format($booking->payment_total ?: $booking->total_harga, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        {{-- Card 4: Ubah Status Manual --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
            <div class="flex items-center gap-2 mb-5">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-sky-500">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/>
                </svg>
                <h2 class="text-[13px] font-bold text-gray-600 uppercase tracking-wide">UBAH STATUS MANUAL</h2>
            </div>

            <form id="statusForm" method="POST" action="{{ route('admin.bookings.update-status', $booking) }}">
                @csrf
                @method('PATCH')

                <label class="block text-[11px] font-semibold text-gray-500 mb-2">Status Manual</label>
                <div class="relative mb-4">
                    <select id="statusSelect" name="payment_status" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-[13px] text-gray-700 bg-[#f8fafc] focus:border-sky-400 focus:ring-0 outline-none transition appearance-none cursor-pointer">
                        <option value="pending" {{ $booking->payment_status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="paid"    {{ $booking->payment_status === 'paid'    ? 'selected' : '' }}>Paid</option>
                        <option value="failed"  {{ $booking->payment_status === 'failed'  ? 'selected' : '' }}>Failed</option>
                        <option value="expired" {{ $booking->payment_status === 'expired' ? 'selected' : '' }}>Expired</option>
                    </select>
                    <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-gray-500">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                    </div>
                </div>

                <button type="button" id="btnSimpan" class="w-full py-2.5 bg-[#0ea5e9] hover:bg-[#0284c7] text-white rounded-lg text-[13px] font-semibold transition flex items-center justify-center gap-2">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/>
                    </svg>
                    Simpan Status
                </button>
            </form>
        </div>

    </div>
</div>

{{-- Script popup --}}
<script src="{{ asset('js/popup-script.js') }}"></script>

@endsection