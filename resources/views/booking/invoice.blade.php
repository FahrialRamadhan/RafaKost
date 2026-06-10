
@extends('layouts.app')

@section('content')
@php
    $status = strtolower($booking->payment_status ?? 'pending');
    $isPaid = $status === 'paid';

    $statusText = match($status) {
        'paid' => 'PAID',
        'pending' => 'PENDING',
        'failed' => 'FAILED',
        'expired' => 'EXPIRED',
        default => strtoupper($status),
    };

    $statusColor = match($status) {
        'paid' => '#16a34a',
        'pending' => '#0ea5e9',
        'failed', 'expired' => '#dc2626',
        default => '#64748b',
    };

	$hargaSewa = (int) ($booking->total_harga ?? 0);
	$biayaLayanan = (int) ($booking->payment_fee ?? 0);
	$total = (int) ($booking->payment_total ?: ($hargaSewa + $biayaLayanan));
	
	$lateFee = (int) ($booking->late_fee ?? 0);
	
	if ($lateFee <= 0 && $total > ($hargaSewa + $biayaLayanan)) {
	    $lateFee = $total - ($hargaSewa + $biayaLayanan);
	}
	
	$lateDays = (int) ($booking->late_days ?? 0);
	
	if ($lateDays <= 0 && $lateFee > 0 && $booking->due_date) {
	    $dueDate = \Carbon\Carbon::parse($booking->due_date)->startOfDay();
	    $today = now()->startOfDay();
	
	    if ($today->greaterThan($dueDate)) {
	        $lateDays = $dueDate->diffInDays($today);
	    }
	}

    $tanggalMasuk = $booking->tanggal_masuk
        ? \Carbon\Carbon::parse($booking->tanggal_masuk)
        : null;
@endphp

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    * {
        box-sizing: border-box;
    }

    .invoice-page {
        min-height: 100vh;
        background: #f4f5f7;
        /* Padding atas diperbesar jadi 120px agar tidak nabrak navbar */
        padding: 120px 16px 80px; 
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        color: #111827;
    }

    .invoice-container {
        max-width: 820px; 
        margin: 0 auto;
    }

    .invoice-card {
        background: #ffffff;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08);
    }

    /* HEADER HITAM */
    .invoice-hero {
        background: #000000;
        color: #ffffff;
        padding: 30px 40px;
        border-radius: 20px 20px 0 0;
    }

    .hero-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
        margin-bottom: 30px;
    }

    .brand-box {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .brand-logo img {
        height: 48px;
        width: auto;
        display: block;
    }

    .brand-name {
        font-size: 20px;
        line-height: 1;
        font-weight: 700;
        color: #ffffff;
    }

    .brand-location {
        margin-top: 4px;
        font-size: 11px;
        color: #e5e7eb;
        font-weight: 400;
    }

    .save-btn {
        border: 0;
        background: #0ea5e9;
        color: #ffffff;
        padding: 8px 24px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        font-family: inherit;
        transition: 0.2s;
    }

    .save-btn:hover {
        background: #0284c7;
    }

    .hero-bottom {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        gap: 20px;
    }

    .hero-label {
        margin-bottom: 8px;
        font-size: 10px;
        color: #d1d5db;
        letter-spacing: .5px;
        text-transform: uppercase;
        font-weight: 600;
    }

    .hero-invoice {
        font-size: 16px;
        color: #ffffff;
        font-weight: 700;
    }

    .hero-total {
        text-align: right;
    }

    .hero-amount {
        font-size: 32px;
        line-height: 1;
        color: #ffffff;
        font-weight: 800;
    }

    /* META STRIP */
    .meta-strip {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        border-bottom: 1px solid #e5e7eb;
        background: #ffffff;
    }

    .meta-item {
        padding: 14px 20px;
        text-align: center;
        border-right: 1px solid #e5e7eb;
    }

    .meta-item:last-child {
        border-right: 0;
    }

    .meta-key {
        margin-bottom: 4px;
        font-size: 9px;
        color: #9ca3af;
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: .5px;
    }

    .meta-value {
        font-size: 11px;
        color: #111827;
        font-weight: 700;
    }

    /* BODY */
    .invoice-body {
        padding: 24px 40px 36px;
        background: #ffffff;
    }

    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
        margin-bottom: 16px;
    }

    .panel {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 20px 24px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    }

    .panel-title,
    .cost-title {
        margin-bottom: 16px;
        font-size: 11px;
        color: #111827;
        font-weight: 700;
        text-transform: uppercase;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .title-icon {
        width: 12px;
        height: 12px;
        object-fit: contain;
        display: inline-block;
    }

    .field {
        margin-bottom: 12px;
    }

    .field:last-child {
        margin-bottom: 0;
    }

    .field-label {
        margin-bottom: 2px;
        font-size: 9px;
        color: #94a3b8;
        text-transform: uppercase;
        font-weight: 600;
    }

    .field-value {
        font-size: 11px;
        color: #111827;
        font-weight: 600;
        line-height: 1.4;
        word-break: break-word;
    }

    .booking-row {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        padding: 8px 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .booking-row:last-child {
        border-bottom: 0;
        padding-bottom: 0;
    }

    .booking-key {
        font-size: 11px;
        color: #64748b;
        font-weight: 500;
    }

    .booking-val {
        font-size: 11px;
        color: #111827;
        font-weight: 700;
        text-align: right;
    }

    /* RINCIAN BIAYA */
    .cost-box {
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    }

    .cost-body {
        padding: 20px 24px 14px;
        background: #ffffff;
    }

    .cost-row {
        display: flex;
        justify-content: space-between;
        gap: 16px;
        padding: 8px 0;
        border-bottom: 1px dashed #cbd5e1;
    }

    .cost-row:last-child {
        border-bottom: 0;
    }

    .cost-row span {
        font-size: 11px;
        color: #94a3b8;
        font-weight: 500;
    }

    .cost-row strong {
        font-size: 11px;
        color: #111827;
        font-weight: 700;
        text-align: right;
    }

    .cost-total {
        background: #f8fafc;
        border-top: 1px solid #e5e7eb;
        padding: 16px 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .cost-total span {
        font-size: 12px;
        color: #111827;
        font-weight: 700;
    }

    .cost-total strong {
        font-size: 15px;
        color: #111827;
        font-weight: 800;
        text-align: right;
    }

    /* CATATAN */
    .note-box {
        margin-top: 16px;
        background: #e0f2fe;
        border: 1px solid #bae6fd;
        border-radius: 10px;
        padding: 14px 20px;
        color: #0284c7;
    }

    .note-title {
        font-size: 12px;
        font-weight: 700;
        margin-bottom: 2px;
    }

    .note-text {
        font-size: 11px;
        line-height: 1.5;
        font-weight: 500;
    }

    /* BUTTONS */
    .invoice-action {
        margin-top: 20px;
    }

    .pay-btn {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 8px;
        width: 100%;
        background: #0ea5e9;
        color: #ffffff;
        border-radius: 10px;
        padding: 16px 20px;
        text-decoration: none;
        font-size: 16px;
        font-weight: 600;
        box-shadow: 0 4px 12px rgba(14, 165, 233, 0.2);
        transition: 0.2s;
    }

    .pay-btn:hover {
        background: #0284c7;
        color: #ffffff;
    }

    .pay-icon {
        width: 16px;
        height: 16px;
        object-fit: contain;
        display: inline-block;
        filter: brightness(0) invert(1);
    }

    .dark-btn {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        background: #111827;
        color: #ffffff;
        border-radius: 10px;
        padding: 16px 20px;
        text-decoration: none;
        font-size: 16px;
        font-weight: 600;
        transition: 0.2s;
    }

    .dark-btn:hover {
        background: #1f2937;
    }

    .small-note {
        margin-top: 12px;
        text-align: center;
        color: #94a3b8;
        font-size: 10px;
        font-weight: 500;
    }

    /* =========================================
       PRINT STYLES (DIJAMIN MUNCUL & PAS A4)
       ========================================= */
    @media print {
        @page {
            size: A4 portrait;
            margin: 5mm;
        }

        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            color-adjust: exact !important;
        }

        body * {
            visibility: hidden;
        }

        .invoice-container, .invoice-container * {
            visibility: visible;
        }

        .invoice-container {
            position: absolute !important;
            left: 0 !important;
            top: 0 !important;
            width: 100% !important;
            max-width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        .save-btn, .invoice-action, .small-note {
            display: none !important;
        }

        .invoice-card {
            border: none !important;
            box-shadow: none !important;
            border-radius: 0 !important;
        }

        .invoice-hero {
            background-color: #000000 !important;
            color: #ffffff !important;
            padding: 24px 30px !important;
            border-radius: 0 !important;
        }

        .invoice-body {
            padding: 24px 30px !important;
        }

        .brand-logo img { height: 40px !important; }
        .hero-amount { font-size: 26px !important; }

        .panel {
            padding: 16px 20px !important;
            border: 1px solid #d1d5db !important;
            box-shadow: none !important;
        }

        .cost-body { padding: 16px 20px 12px !important; }
        .cost-total { padding: 12px 20px !important; }
        .note-box { 
            padding: 12px 16px !important; 
            border: 1px solid #0ea5e9 !important;
            background-color: #e0f2fe !important; 
        }
    }

    /* MOBILE RESPONSIVE */
    @media (max-width: 768px) {
        .invoice-page { 
            /* Jarak atas mobile juga disesuaikan */
            padding: 100px 12px 40px; 
        }
        .invoice-hero { padding: 24px 20px; }
        .hero-top, .hero-bottom { flex-direction: column; align-items: flex-start; gap: 16px; }
        .hero-total { text-align: left; }
        .meta-strip { grid-template-columns: repeat(2, 1fr); }
        .meta-item:nth-child(2) { border-right: 0; }
        .meta-item:nth-child(1), .meta-item:nth-child(2) { border-bottom: 1px solid #e5e7eb; }
        .invoice-body { padding: 24px 20px; }
        .info-grid { grid-template-columns: 1fr; }
    }
</style>

<div class="invoice-page">
    <div class="invoice-container">
        <div class="invoice-card">

            {{-- HEADER HITAM --}}
            <div class="invoice-hero">
                <div class="hero-top">
                    <div class="brand-box">
                        <a href="{{ url('/') }}" class="brand-logo">
                            <img src="{{ asset('images/logo.png') }}" alt="Rafa Kost">
                        </a>
                        <div class="brand-text">
                            <div class="brand-name">Rafa Kost</div>
                            <div class="brand-location">Purwokerto, Jawa Tengah</div>
                        </div>
                    </div>

                    <button type="button" class="save-btn" onclick="window.print()">
                        Simpan
                    </button>
                </div>

                <div class="hero-bottom">
                    <div>
                        <div class="hero-label">No Invoice</div>
                        <div class="hero-invoice">{{ $booking->invoice }}</div>
                    </div>

                    <div class="hero-total">
                        <div class="hero-label">Total Pembayaran</div>
                        <div class="hero-amount">
                            Rp {{ number_format($total, 0, ',', '.') }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- META STRIP --}}
            <div class="meta-strip">
                <div class="meta-item">
                    <div class="meta-key">Kamar</div>
                    <div class="meta-value">{{ $booking->kamar->nama ?? '-' }}</div>
                </div>

                <div class="meta-item">
                    <div class="meta-key">Tanggal Masuk</div>
                    <div class="meta-value">
                        {{ $tanggalMasuk ? $tanggalMasuk->format('d M Y') : '-' }}
                    </div>
                </div>

                <div class="meta-item">
                    <div class="meta-key">Durasi</div>
                    <div class="meta-value">{{ $booking->durasi }} Bulan</div>
                </div>

                <div class="meta-item">
                    <div class="meta-key">Orang</div>
                    <div class="meta-value">{{ $booking->orang ?? 1 }} Orang</div>
                </div>
            </div>

            {{-- BODY --}}
            <div class="invoice-body">

                <div class="info-grid">
                    {{-- DATA PENYEWA --}}
                    <div class="panel">
                        <div class="panel-title">
                            <img src="{{ asset('images/RB.png') }}" alt="" class="title-icon">
                            Data Penyewa
                        </div>
                        <div class="field">
                            <div class="field-label">Nama Lengkap</div>
                            <div class="field-value">{{ $booking->customer_name ?? '-' }}</div>
                        </div>
                        <div class="field">
                            <div class="field-label">No WhatsApp</div>
                            <div class="field-value">{{ $booking->customer_phone ?? '-' }}</div>
                        </div>
                        <div class="field">
                            <div class="field-label">Email</div>
                            <div class="field-value">{{ $booking->customer_email ?? '-' }}</div>
                        </div>
                    </div>

                    {{-- DETAIL BOOKING --}}
                    <div class="panel">
                        <div class="panel-title">
                            <img src="{{ asset('images/RB.png') }}" alt="" class="title-icon">
                            Detail Booking
                        </div>
                        <div class="booking-row">
                            <span class="booking-key">Kamar</span>
                            <span class="booking-val">{{ $booking->kamar->nama ?? '-' }}</span>
                        </div>
                        <div class="booking-row">
                            <span class="booking-key">Tanggal Masuk</span>
                            <span class="booking-val">
                                {{ $tanggalMasuk ? $tanggalMasuk->format('d/m/Y') : '-' }}
                            </span>
                        </div>
                        <div class="booking-row">
                            <span class="booking-key">Durasi</span>
                            <span class="booking-val">{{ $booking->durasi }} Bulan</span>
                        </div>
                        <div class="booking-row">
                            <span class="booking-key">Orang</span>
                            <span class="booking-val">{{ $booking->orang ?? 1 }} Orang</span>
                        </div>
                        <div class="booking-row">
                            <span class="booking-key">Gateway</span>
                            <span class="booking-val">{{ strtoupper($booking->payment_gateway ?? '-') }}</span>
                        </div>
                        <div class="booking-row">
                            <span class="booking-key">Status</span>
                            <span class="booking-val" style="color:{{ $statusColor }};">
                                {{ $statusText }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- RINCIAN BIAYA --}}
                <div class="cost-box">
                    <div class="cost-body">
                        <div class="cost-title">
                            <img src="{{ asset('images/RB.png') }}" alt="" class="title-icon">
                            Rincian Biaya
                        </div>
                        <div class="cost-row">
                            <span>Harga Sewa</span>
                            <strong>Rp {{ number_format($hargaSewa, 0, ',', '.') }}</strong>
                        </div>
                        <div class="cost-row">
                            <span>Biaya Layanan</span>
                            <strong>Rp {{ number_format($biayaLayanan, 0, ',', '.') }}</strong>
                        </div>

					@if($lateFee > 0)
					    <div class="cost-row">
					        <span>
					            Denda Telat Bayar {{ $lateDays > 0 ? '(' . $lateDays . ' hari)' : '' }}
					        </span>
					        <strong style="color:#dc2626;">Rp {{ number_format($lateFee, 0, ',', '.') }}</strong>
					    </div>
					@endif
                    </div>
                    <div class="cost-total">
                        <span>Total Pembayaran</span>
                        <strong>Rp {{ number_format($total, 0, ',', '.') }}</strong>
                    </div>
                </div>

                {{-- CATATAN --}}
                <div class="note-box">
                    <div class="note-title">Catatan :</div>
                    <div class="note-text">
                        Invoice lunas akan otomatis dikirim ke email setelah pembayaran berhasil dikonfirmasi oleh sistem.
                    </div>
                </div>

                {{-- BUTTON --}}
                <div class="invoice-action">
                    @if($status === 'pending')
                        <a href="{{ route('booking.methods', $booking->invoice) }}" class="pay-btn">
                            <img src="{{ asset('images/dompet.png') }}" alt="" class="pay-icon">
                            Lanjutkan Pembayaran
                        </a>
                        <div class="small-note">
                            Setelah pembayaran sukses, bukti pembayaran akan otomatis dikirim ke email penyewa.
                        </div>
                    @else
                        <a href="{{ route('my-rentals.index') }}" class="dark-btn">
                            Lihat Sewa Saya
                        </a>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
