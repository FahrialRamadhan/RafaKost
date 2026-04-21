@extends('layouts.app')

@section('content')

@include('layouts.navigation-user')

@php
    if (empty($kamar)) abort(404);
@endphp

<style>
    
    nav {
    position: relative;
    z-index: 9999;
}
    /* ─── Scoped ke .detail-page saja ─── */
    .detail-page {
        --dp-primary:        #2563EB;
        --dp-primary-light:  #EFF6FF;
        --dp-primary-hover:  #1D4ED8;
        --dp-accent:         #F59E0B;
        --dp-success:        #10B981;
        --dp-surface:        #FFFFFF;
        --dp-surface-2:      #F8FAFC;
        --dp-surface-3:      #F1F5F9;
        --dp-border:         #E2E8F0;
        --dp-border-focus:   #93C5FD;
        --dp-text-primary:   #0F172A;
        --dp-text-secondary: #475569;
        --dp-text-muted:     #94A3B8;
        --dp-shadow-sm:      0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
        --dp-shadow-md:      0 4px 16px rgba(0,0,0,0.08), 0 2px 6px rgba(0,0,0,0.05);
        --dp-shadow-lg:      0 12px 40px rgba(0,0,0,0.1), 0 4px 12px rgba(0,0,0,0.06);
        --dp-radius:         14px;
        --dp-radius-sm:      8px;
        --dp-radius-lg:      20px;

        background: var(--dp-surface-2);
        color: var(--dp-text-primary);
        -webkit-font-smoothing: antialiased;
    }

    
    .detail-page {
        padding-top: 5%;
        background: #E5E7EB;
    }
    .detail-page *::before,
    .detail-page *::after {
        box-sizing: border-box;
    }

    /* ─── Page Wrapper ─── */
    .detail-page .detail-wrapper {
        max-width: 1180px;
        margin: 0 auto;
        padding: 32px 20px 80px;
        display: grid;
        grid-template-columns: 1fr 360px;
        gap: 32px;
        align-items: start;
    }

    /* ─── Left Column ─── */
    .detail-page .left-col {
        display: flex;
        flex-direction: column;
        gap: 28px;
    }

    /* ─── Gallery ─── */
    .detail-page .gallery {
        border-radius: var(--dp-radius-lg);
        overflow: hidden;
        display: grid;
        grid-template-columns: 1fr 1fr;
        grid-template-rows: 220px 160px;
        gap: 6px;
        background: var(--dp-border);
    }
    .detail-page .gallery-main {
        grid-row: 1 / 3;
        background: linear-gradient(135deg, #CBD5E1 0%, #94A3B8 100%);
        position: relative;
        overflow: hidden;
        cursor: pointer;
    }
    .detail-page .gallery-main img,
    .detail-page .gallery-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform .4s ease;
    }
    .detail-page .gallery-main:hover img,
    .detail-page .gallery-thumb:hover img {
        transform: scale(1.04);
    }
    .detail-page .gallery-thumb {
        background: linear-gradient(135deg, #E2E8F0 0%, #CBD5E1 100%);
        overflow: hidden;
        cursor: pointer;
        position: relative;
    }
    .detail-page .gallery-thumb:last-child::after {
        content: attr(data-more);
        position: absolute;
        inset: 0;
        background: rgba(15,23,42,.45);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        font-weight: 700;
        backdrop-filter: blur(2px);
    }
    .detail-page .gallery-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 8px;
        color: #94A3B8;
    }
    .detail-page .gallery-placeholder svg { opacity: .5; }
    .detail-page .gallery-placeholder span { font-size: 12px; }

    /* ─── Room Header ─── */
    .detail-page .room-header-card {
        background: var(--dp-surface);
        border-radius: var(--dp-radius);
        padding: 24px 28px;
        box-shadow: var(--dp-shadow-sm);
        border: 1px solid var(--dp-border);
    }
    .detail-page .room-meta-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 20px;
    }
    .detail-page .room-name {
        font-size: 28px;
        color: var(--dp-text-primary);
        line-height: 1.2;
        margin: 0;
        padding: 0;
    }
    .detail-page .room-location {
        font-size: 13px;
        color: var(--dp-text-secondary);
        margin-top: 4px;
        display: flex;
        align-items: center;
        gap: 4px;
    }
    .detail-page .room-location svg { flex-shrink: 0; }
    .detail-page .price-badge {
        text-align: right;
        flex-shrink: 0;
    }
    .detail-page .price-amount {
        font-size: 22px;
        font-weight: 800;
        color: var(--dp-primary);
    }
    .detail-page .price-period {
        font-size: 12px;
        color: var(--dp-text-muted);
        font-weight: 500;
    }
    .detail-page .room-specs {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }
    .detail-page .spec-chip {
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 8px 14px;
        background: var(--dp-surface-2);
        border: 1px solid var(--dp-border);
        border-radius: 50px;
        font-size: 12px;
        font-weight: 600;
        color: var(--dp-text-secondary);
        transition: all .2s;
    }
    .detail-page .spec-chip:hover {
        background: var(--dp-primary-light);
        border-color: var(--dp-border-focus);
        color: var(--dp-primary);
    }
    .detail-page .spec-chip svg { color: var(--dp-primary); }

    /* ─── Section Card ─── */
    .detail-page .section-card {
        background: var(--dp-surface);
        border-radius: var(--dp-radius);
        padding: 24px 28px;
        box-shadow: var(--dp-shadow-sm);
        border: 1px solid var(--dp-border);
    }
    .detail-page .section-title {
        font-size: 17px;
        font-weight: 700;
        color: var(--dp-text-primary);
        margin-bottom: 14px;
        margin-top: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .detail-page .section-title::before {
        content: '';
        width: 4px;
        height: 18px;
        background: var(--dp-primary);
        border-radius: 2px;
        display: block;
        flex-shrink: 0;
    }
    .detail-page .desc-text {
        font-size: 14px;
        line-height: 1.8;
        color: var(--dp-text-secondary);
        margin: 0;
    }

    /* ─── Facilities ─── */
    .detail-page .facilities-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }
    .detail-page .facility-tag {
        display: flex;
        align-items: center;
        gap: 7px;
        padding: 9px 16px;
        background: var(--dp-primary-light);
        border: 1px solid #BFDBFE;
        border-radius: 50px;
        font-size: 13px;
        font-weight: 600;
        color: var(--dp-primary);
        transition: all .2s;
        cursor: default;
    }
    .detail-page .facility-tag:hover {
        background: var(--dp-primary);
        color: #fff;
        border-color: var(--dp-primary);
    }
    .detail-page .facility-tag:hover svg { color: #fff; }

    /* ─── Rules ─── */
    .detail-page .rules-list {
        list-style: none;
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin: 0;
        padding: 0;
    }
    .detail-page .rules-list li {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 14px;
        color: var(--dp-text-secondary);
    }
    .detail-page .rule-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: var(--dp-primary);
        flex-shrink: 0;
    }

    /* ─── Right Column – Booking Card ─── */
    .detail-page .right-col {
    position: sticky;
    top: 24px;
    z-index: 1;
}
    .detail-page .booking-card {
        background: var(--dp-surface);
        border-radius: var(--dp-radius-lg);
        box-shadow: var(--dp-shadow-lg);
        border: 1px solid var(--dp-border);
        overflow: hidden;
    }
    .detail-page .booking-card-header {
        background: linear-gradient(135deg, var(--dp-primary) 0%, #1D4ED8 100%);
        padding: 20px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .detail-page .booking-card-header h2 {
        font-size: 22px;
        color: #fff;
        line-height: 1;
        margin: 0;
        padding: 0;
    }
    .detail-page .availability-badge {
        background: rgba(255,255,255,.2);
        color: #fff;
        font-size: 11px;
        font-weight: 700;
        padding: 5px 12px;
        border-radius: 50px;
        text-transform: uppercase;
        letter-spacing: .5px;
        backdrop-filter: blur(4px);
    }
    .detail-page .availability-badge.available {
        background: rgba(16,185,129,.25);
    }
    .detail-page .booking-body {
        padding: 20px 24px;
        display: flex;
        flex-direction: column;
        gap: 14px;
    }
    .detail-page .form-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }
    .detail-page .form-label {
        font-size: 12px;
        font-weight: 700;
        color: var(--dp-text-secondary);
        text-transform: uppercase;
        letter-spacing: .5px;
    }
    .detail-page .form-control {
        width: 100%;
        padding: 11px 14px;
        border: 1.5px solid var(--dp-border);
        border-radius: var(--dp-radius-sm);
        font-size: 14px;
        color: var(--dp-text-primary);
        background: var(--dp-surface-2);
        outline: none;
        transition: border-color .2s, box-shadow .2s;
        appearance: none;
        -webkit-appearance: none;
    }
    .detail-page .form-control:focus {
        border-color: var(--dp-primary);
        box-shadow: 0 0 0 3px rgba(37,99,235,.12);
        background: var(--dp-surface);
    }
    .detail-page select.form-control {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2394A3B8' stroke-width='2.5'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 14px center;
        padding-right: 36px;
    }

    /* ─── Summary Box ─── */
    .detail-page .summary-box {
        background: var(--dp-surface-2);
        border: 1px solid var(--dp-border);
        border-radius: var(--dp-radius-sm);
        padding: 16px;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    .detail-page .summary-title {
        font-size: 12px;
        font-weight: 700;
        color: var(--dp-text-secondary);
        text-transform: uppercase;
        letter-spacing: .5px;
        margin-bottom: 2px;
    }
    .detail-page .summary-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 13px;
    }
    .detail-page .summary-row .label { color: var(--dp-text-secondary); }
    .detail-page .summary-row .value { font-weight: 600; color: var(--dp-text-primary); }
    .detail-page .summary-divider {
        height: 1px;
        background: var(--dp-border);
        margin: 4px 0;
    }
    .detail-page .summary-total .label {
        font-weight: 700;
        color: var(--dp-text-primary);
        font-size: 14px;
    }
    .detail-page .summary-total .value {
        font-weight: 800;
        color: var(--dp-primary);
        font-size: 15px;
    }
    .detail-page .btn-book {
        display: block;
        width: 100%;
        padding: 14px;
        background: linear-gradient(135deg, var(--dp-primary) 0%, #1D4ED8 100%);
        color: #fff;
        font-size: 15px;
        font-weight: 700;
        border: none;
        border-radius: var(--dp-radius-sm);
        cursor: pointer;
        transition: all .2s;
        letter-spacing: .2px;
        box-shadow: 0 4px 14px rgba(37,99,235,.35);
    }
    .detail-page .btn-book:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(37,99,235,.45);
    }
    .detail-page .btn-book:active { transform: translateY(0); }
    .detail-page .booking-note {
        text-align: center;
        font-size: 11px;
        color: var(--dp-text-muted);
        line-height: 1.5;
        margin: 0;
    }

    /* ─── Responsive ─── */
    @media (max-width: 900px) {
        .detail-page .detail-wrapper {
            grid-template-columns: 1fr;
        }
        .detail-page .right-col { position: static; }
        .detail-page .gallery {
            grid-template-columns: 1fr 1fr;
            grid-template-rows: 200px 140px;
        }
    }
    @media (max-width: 560px) {
        .detail-page .detail-wrapper { padding: 16px 12px 60px; gap: 20px; }
        .detail-page .gallery { grid-template-rows: 160px 120px; gap: 4px; }
        .detail-page .room-name { font-size: 22px; }
        .detail-page .price-amount { font-size: 18px; }
    }
</style>

{{-- Wrapper utama: semua style di-scope ke sini --}}
<div class="detail-page">
<div class="detail-wrapper">

    {{-- ══════════ LEFT COLUMN ══════════ --}}
    <div class="left-col">

        {{-- Gallery --}}
        <div class="gallery">
            <div class="gallery-main">
                @if(!empty($kamar->images[0]))
                    <img src="{{ asset('storage/' . $kamar->images[0]) }}" alt="Foto utama kamar">
                @else
                    <div class="gallery-placeholder">
                        <svg width="40" height="40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
                        <span>Foto Kamar</span>
                    </div>
                @endif
            </div>
            <div class="gallery-thumb">
                @if(!empty($kamar->images[1]))
                    <img src="{{ asset('storage/' . $kamar->images[1]) }}" alt="Foto kamar 2">
                @else
                    <div class="gallery-placeholder">
                        <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
                    </div>
                @endif
            </div>
            <div class="gallery-thumb" @if(count($kamar->images ?? []) > 3) data-more="+{{ count($kamar->images) - 3 }} foto" @endif>
                @if(!empty($kamar->images[2]))
                    <img src="{{ asset('storage/' . $kamar->images[2]) }}" alt="Foto kamar 3">
                @else
                    <div class="gallery-placeholder">
                        <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
                    </div>
                @endif
            </div>
        </div>

        {{-- Room Info --}}
        <div class="room-header-card">
            <div class="room-meta-top">
                <div>
                    <h1 class="room-name">{{ $kamar->nama }}</h1>
                    <p class="room-location">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        {{ $kamar->address ?? 'Jl. Contoh No.1, Purwokerto' }}
                    </p>
                </div>
                <div class="price-badge">
                    <div class="price-amount">Rp {{ number_format($kamar->price ?? 650000, 0, ',', '.') }}</div>
                    <div class="price-period">/ Bulan</div>
                </div>
            </div>

            <div class="room-specs">
                <div class="spec-chip">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                    {{ $kamar->bed_type ?? 'Single Bed' }}
                </div>
                <div class="spec-chip">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/><line x1="4" y1="22" x2="4" y2="15"/></svg>
                    {{ $kamar->size ?? '3x4 m²' }}
                </div>
                @php $bathroomType = $kamar->bathroom_type ?? 'private'; @endphp
                @if($bathroomType)
                <div class="spec-chip">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 6 6.5 3.5a1.5 1.5 0 0 0-1-.5C4.683 3 4 3.683 4 4.5V17a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-5"/><line x1="10" y1="5" x2="8" y2="7"/><line x1="2" y1="12" x2="22" y2="12"/></svg>
                    Kamar Mandi {{ $bathroomType == 'private' ? 'Pribadi' : 'Bersama' }}
                </div>
                @endif
                <div class="spec-chip">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 20V10"/><path d="M12 20V4"/><path d="M6 20v-6"/></svg>
                    Lantai {{ $kamar->floor ?? '1' }}
                </div>
            </div>
        </div>

        {{-- Deskripsi --}}
        <div class="section-card">
            <h2 class="section-title">Deskripsi</h2>
            <p class="desc-text">
                {{ $kamar->description ?? 'Kamar ini cocok untuk kamu yang membutuhkan tempat tinggal yang nyaman dan praktis. Kondisi kamar bersih dan cukup untuk aktivitas sehari-hari, dilengkapi dengan kasur ukuran single bed, meja, dan kursi. Penghuni juga mendapatkan akses ke kamar mandi luar digunakan bersama. Lingkungan kost yang tenang dan aman membuat kamar ini cocok untuk mahasiswa atau pekerja yang mencari hunian sederhana namun tetap nyaman.' }}
            </p>
        </div>

        {{-- Fasilitas Umum --}}
        <div class="section-card">
            <h2 class="section-title">Fasilitas Umum</h2>
            <div class="facilities-grid">
                @php
                    $defaultFacilities = [
                        ['icon' => '<svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 12.55a11 11 0 0 1 14.08 0"/><path d="M1.42 9a16 16 0 0 1 21.16 0"/><path d="M8.53 16.11a6 6 0 0 1 6.95 0"/><line x1="12" y1="20" x2="12.01" y2="20"/></svg>', 'label' => 'Wifi Gratis'],
                        ['icon' => '<svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/><line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/></svg>', 'label' => 'Air Minum'],
                        ['icon' => '<svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>', 'label' => 'Listrik'],
                        ['icon' => '<svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 9h18v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9z"/><path d="M3 9V7a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v2"/><path d="M9 22V12h6v10"/></svg>', 'label' => 'Dapur Bersama'],
                        ['icon' => '<svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>', 'label' => 'Parkir'],
                        ['icon' => '<svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>', 'label' => 'Kulkas Bersama'],
                    ];
                    $facilities = $kamar->facilities ?? $defaultFacilities;
                @endphp
                @foreach($facilities as $facility)
                <div class="facility-tag">
                    {!! is_array($facility) ? $facility['icon'] : '' !!}
                    {{ is_array($facility) ? $facility['label'] : $facility }}
                </div>
                @endforeach
            </div>
        </div>

        {{-- Peraturan Kost --}}
        @if(!empty($kamar->rules))
        <div class="section-card">
            <h2 class="section-title">Peraturan Kost</h2>
            <ul class="rules-list">
                @foreach($kamar->rules as $rule)
                <li>
                    <span class="rule-dot"></span>
                    {{ $rule }}
                </li>
                @endforeach
            </ul>
        </div>
        @endif

    </div>

    {{-- ══════════ RIGHT COLUMN – BOOKING ══════════ --}}
    <div class="right-col">
        <div class="booking-card">
            <div class="booking-card-header">
                <h2>Rafa Kost</h2>
                <span class="availability-badge available">Tersedia</span>
            </div>
            <div class="booking-body">
                <p style="font-size:13px;font-weight:700;color:#475569;text-transform:uppercase;letter-spacing:.5px;">
                    Booking Kamar
                </p>
                <div class="form-group">
                    <label class="form-label">Tanggal Masuk</label>
                    <input type="date" class="form-control" name="tanggal_masuk"
                        value="{{ old('tanggal_masuk', date('Y-m-d')) }}"
                        min="{{ date('Y-m-d') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Durasi Sewa</label>
                    <select class="form-control" name="durasi" id="durasiSelect">
                        <option value="1">1 Bulan</option>
                        <option value="3">3 Bulan</option>
                        <option value="6">6 Bulan</option>
                        <option value="12">12 Bulan</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Orang</label>
                    <select class="form-control" name="orang">
                        <option value="1">1 Orang</option>
                        <option value="2">2 Orang</option>
                    </select>
                </div>
                <div class="summary-box">
                    <p class="summary-title">Rangkuman</p>
                    <div class="summary-row">
                        <span class="label">Harga Sewa</span>
                        <span class="value" id="hargaSewa">Rp {{ number_format($kamar->price ?? 650000, 0, ',', '.') }}</span>
                    </div>
                    <div class="summary-row">
                        <span class="label">Durasi</span>
                        <span class="value" id="durasiLabel">1 Bulan</span>
                    </div>
                    <div class="summary-row">
                        <span class="label">Orang</span>
                        <span class="value">1 Orang</span>
                    </div>
                    <div class="summary-divider"></div>
                    <div class="summary-row summary-total">
                        <span class="label">Total</span>
                        <span class="value" id="totalHarga">Rp {{ number_format($kamar->price ?? 650000, 0, ',', '.') }}</span>
                    </div>
                </div>
                <button class="btn-book" onclick="submitBooking()">
                    Sewa Sekarang →
                </button>
                <p class="booking-note">
                    Pembayaran akan dikonfirmasi oleh pengelola kost.<br>
                    Tidak ada biaya tambahan tersembunyi.
                </p>
            </div>
        </div>
    </div>

</div>
</div>{{-- /.detail-page --}}

<script>
    (function () {
        const priceBase = {{ $kamar->price ?? 650000 }};

        function formatRupiah(num) {
            return 'Rp ' + num.toLocaleString('id-ID');
        }

        const durasiSelect = document.getElementById('durasiSelect');
        const durasiLabel  = document.getElementById('durasiLabel');
        const totalHarga   = document.getElementById('totalHarga');

        durasiSelect?.addEventListener('change', function () {
            const bulan = parseInt(this.value);
            const total = priceBase * bulan;
            durasiLabel.textContent = bulan + ' Bulan';
            totalHarga.textContent  = formatRupiah(total);
        });

        window.submitBooking = function () {
            const tanggal = document.querySelector('[name="tanggal_masuk"]')?.value;
            const durasi  = document.querySelector('[name="durasi"]')?.value;
            const orang   = document.querySelector('[name="orang"]')?.value;

            if (!tanggal) {
                alert('Harap pilih tanggal masuk.');
                return;
            }

            const params = new URLSearchParams({ tanggal_masuk: tanggal, durasi, orang });
            window.location.href = `/booking/{{ $kamar->id ?? 1 }}?` + params.toString();
        };
    })();
</script>

@include('layouts.footer')

@endsection