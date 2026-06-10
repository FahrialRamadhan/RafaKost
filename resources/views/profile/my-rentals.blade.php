@extends('layouts.app')

@section('content')
@php
    use Carbon\Carbon;

    $filterStartDate = isset($startDate)
        ? Carbon::parse($startDate)->format('Y-m-d')
        : request('start_date', now()->format('Y-m-d'));

    $filterEndDate = isset($endDate)
        ? Carbon::parse($endDate)->format('Y-m-d')
        : request('end_date', now()->format('Y-m-d'));
@endphp

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

    .rentals-page {
        min-height: 100vh;
        background: #f4f7fb;
        padding: 104px 16px 64px;
        font-family: 'Inter', sans-serif;
        color: #0f172a;
    }

    .rentals-wrap {
        max-width: 1080px;
        margin: 0 auto;
    }

    .rentals-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 16px;
        margin-bottom: 24px;
    }

    .rentals-title {
        font-size: 25px;
        font-weight: 600;
        letter-spacing: -0.03em;
        margin: 0;
        color: #0f172a;
    }

    .rentals-subtitle {
        font-size: 13px;
        color: #64748b;
        margin: 7px 0 0;
        line-height: 1.6;
    }

    .rentals-time {
        font-size: 12px;
        color: #94a3b8;
        margin-top: 5px;
    }

    .back-profile {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        color: #334155;
        padding: 9px 13px;
        border-radius: 11px;
        text-decoration: none;
        font-size: 12px;
        font-weight: 500;
        transition: .2s ease;
        white-space: nowrap;
    }

    .back-profile:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
    }

    .alert-soft {
        padding: 13px 15px;
        border-radius: 12px;
        margin-bottom: 18px;
        font-size: 13px;
        font-weight: 500;
    }

    .alert-success {
        background: #ecfdf5;
        color: #047857;
        border: 1px solid #a7f3d0;
    }

    .alert-error {
        background: #fef2f2;
        color: #b91c1c;
        border: 1px solid #fecaca;
    }

    .rental-list {
        display: grid;
        grid-template-columns: 1fr;
        gap: 18px;
    }

    .rental-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 12px 30px rgba(15, 23, 42, 0.045);
    }

    .rental-card-body {
        padding: 22px;
        display: grid;
        grid-template-columns: 1.35fr .95fr;
        gap: 22px;
    }

    .room-head {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 12px;
        flex-wrap: wrap;
    }

    .room-title {
        font-size: 21px;
        font-weight: 600;
        letter-spacing: -0.025em;
        color: #0f172a;
        margin: 0;
    }

    .status-pill {
        display: inline-flex;
        align-items: center;
        padding: 5px 10px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 600;
    }

    .invoice-text {
        font-size: 13px;
        color: #64748b;
        margin: 0 0 16px;
    }

    .invoice-text strong {
        color: #334155;
        font-weight: 600;
    }

    .rental-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }

    .info-box {
        background: #f8fafc;
        border: 1px solid #eef2f7;
        border-radius: 14px;
        padding: 14px;
    }

    .info-label {
        font-size: 10px;
        color: #94a3b8;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .04em;
    }

    .info-value {
        font-size: 14px;
        color: #0f172a;
        font-weight: 600;
        margin-top: 5px;
    }

    .rental-note {
        margin-top: 14px;
        padding: 12px 14px;
        border-radius: 14px;
        font-size: 13px;
        font-weight: 500;
        line-height: 1.55;
    }

    .renew-card {
        background: #f8fafc;
        border: 1px solid #eef2f7;
        border-radius: 18px;
        padding: 18px;
    }

    .renew-title {
        font-size: 16px;
        font-weight: 600;
        color: #0f172a;
        margin: 0 0 14px;
        letter-spacing: -0.015em;
    }

    .form-label {
        display: block;
        font-size: 12px;
        font-weight: 600;
        color: #475569;
        margin-bottom: 6px;
    }

    .form-control {
        width: 100%;
        border: 1px solid #cbd5e1;
        background: #ffffff;
        border-radius: 12px;
        padding: 11px 12px;
        font-size: 13px;
        color: #0f172a;
        margin-bottom: 12px;
        outline: none;
        transition: .2s ease;
    }

    .form-control:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, .08);
    }

    .btn-primary {
        width: 100%;
        border: 0;
        background: #2563eb;
        color: #ffffff;
        padding: 12px 16px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 13px;
        cursor: pointer;
        transition: .2s ease;
    }

    .btn-primary:hover {
        background: #1d4ed8;
    }

    .renew-help {
        font-size: 12px;
        color: #64748b;
        line-height: 1.65;
        margin: 12px 0 0;
    }

    .empty-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        padding: 42px 24px;
        text-align: center;
        box-shadow: 0 12px 30px rgba(15, 23, 42, 0.04);
    }

    .empty-title {
        font-size: 21px;
        font-weight: 600;
        color: #0f172a;
        margin: 0;
        letter-spacing: -0.025em;
    }

    .empty-text {
        font-size: 14px;
        color: #64748b;
        margin-top: 9px;
    }

    .btn-search {
        display: inline-flex;
        margin-top: 18px;
        background: #2563eb;
        color: #ffffff;
        padding: 11px 17px;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 600;
        font-size: 13px;
    }

    .history-section {
        margin-top: 28px;
    }

    .history-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        gap: 14px;
        margin-bottom: 12px;
    }

    .section-title {
        font-size: 20px;
        font-weight: 600;
        letter-spacing: -0.02em;
        color: #0f172a;
        margin: 0;
    }

    .section-subtitle {
        font-size: 12px;
        color: #64748b;
        margin-top: 5px;
        line-height: 1.5;
    }

    .history-tools {
        display: flex;
        align-items: center;
        gap: 9px;
        flex-wrap: wrap;
        justify-content: flex-end;
    }

    .date-filter-form {
        display: flex;
        align-items: center;
        gap: 7px;
        flex-wrap: wrap;
    }

    .date-input {
        border: 1px solid #dbe3ee;
        background: #ffffff;
        border-radius: 999px;
        padding: 9px 12px;
        font-size: 12px;
        color: #0f172a;
        outline: none;
        transition: .2s ease;
        height: 38px;
        cursor: pointer;
    }

    .date-input:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, .08);
    }

    .date-separator {
        font-size: 12px;
        color: #94a3b8;
    }

    .btn-today {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        background: #ffffff;
        border: 1px solid #dbe3ee;
        color: #475569;
        border-radius: 999px;
        padding: 8px 12px;
        height: 38px;
        font-size: 12px;
        font-weight: 500;
        cursor: pointer;
    }

    .btn-today:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
    }

    .invoice-search-wrap {
        width: 230px;
        flex-shrink: 0;
    }

    .invoice-search {
        width: 100%;
        height: 38px;
        border: 1px solid #dbe3ee;
        background: #ffffff;
        border-radius: 999px;
        padding: 9px 14px;
        font-size: 12px;
        color: #0f172a;
        outline: none;
        transition: .2s ease;
    }

    .invoice-search:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, .08);
    }

    .history-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 10px 26px rgba(15, 23, 42, 0.035);
        position: relative;
    }

    .history-card.is-loading {
        opacity: .72;
    }

    .table-scroll {
        overflow-x: auto;
    }

    .history-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 860px;
    }

    .history-table th {
        background: #fbfcfe;
        padding: 10px 13px;
        text-align: left;
        font-size: 10px;
        color: #94a3b8;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .04em;
        border-bottom: 1px solid #e2e8f0;
    }

    .history-table td {
        padding: 10px 13px;
        border-bottom: 1px solid #edf2f7;
        font-size: 12px;
        color: #334155;
        vertical-align: middle;
    }

    .history-table tr:last-child td {
        border-bottom: 0;
    }

    .history-table tr:hover td {
        background: #fbfdff;
    }

    .date-text {
        font-size: 11.5px;
        color: #64748b;
        white-space: nowrap;
    }

    .invoice-link {
        color: #2563eb;
        font-weight: 500;
        text-decoration: none;
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        font-size: 11.5px;
    }

    .invoice-link:hover {
        text-decoration: underline;
    }

    .invoice-muted {
        color: #64748b;
        font-weight: 500;
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        font-size: 11.5px;
    }

    .room-name {
        color: #0f172a;
        font-weight: 500;
        font-size: 12px;
    }

    .mini-pill {
        display: inline-flex;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        color: #64748b;
        padding: 4px 7px;
        border-radius: 7px;
        font-size: 10.5px;
        font-weight: 500;
        margin: 2px;
    }

    .price {
        color: #0f172a;
        font-weight: 500;
        font-size: 12px;
        white-space: nowrap;
    }

    .payment-status {
        display: inline-flex;
        padding: 4px 9px;
        border-radius: 999px;
        font-size: 10.5px;
        font-weight: 600;
        white-space: nowrap;
    }

    .btn-cancel {
        background: transparent;
        border: 0;
        color: #ef4444;
        font-size: 11.5px;
        font-weight: 500;
        cursor: pointer;
        padding: 0;
    }

    .btn-cancel:hover {
        text-decoration: underline;
    }

    .empty-history {
        padding: 32px 18px !important;
        text-align: center;
        color: #64748b !important;
        font-size: 12px;
    }

    .no-search-result {
        display: none;
    }

    @media (max-width: 900px) {
        .rental-card-body {
            grid-template-columns: 1fr;
        }

        .rentals-header {
            flex-direction: column;
        }

        .history-top {
            flex-direction: column;
            align-items: stretch;
        }

        .history-tools {
            justify-content: flex-start;
        }
    }

    @media (max-width: 640px) {
        .rentals-page {
            padding: 96px 12px 48px;
        }

        .rental-grid {
            grid-template-columns: 1fr;
        }

        .rentals-title {
            font-size: 23px;
        }

        .history-tools,
        .date-filter-form {
            width: 100%;
            flex-direction: column;
            align-items: stretch;
        }

        .invoice-search-wrap,
        .date-input,
        .btn-today {
            width: 100%;
        }

        .date-separator {
            display: none;
        }
    }
</style>

<div class="rentals-page">
    <div class="rentals-wrap">

        <div class="rentals-header">
            <div>
                <h1 class="rentals-title">Kamar Saya</h1>

                <p class="rentals-subtitle">
                    Pantau masa sewa kamar, tanggal habis, dan lakukan perpanjangan sewa.
                </p>

                <p class="rentals-time">
                    Waktu sekarang: {{ now()->format('d/m/Y H:i') }} WIB
                </p>
            </div>

            <a href="{{ route('profile.edit') }}" class="back-profile">
                ← Profil
            </a>
        </div>

        @if(session('success'))
            <div class="alert-soft alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert-soft alert-error">
                {{ session('error') }}
            </div>
        @endif

        {{-- SEWA AKTIF --}}
        <div class="rental-list">
            @forelse($bookings as $booking)
                @php
                    $tanggalMasuk = $booking->tanggal_masuk ? Carbon::parse($booking->tanggal_masuk) : null;

                    $tanggalHabis = $booking->tanggal_habis_custom
                        ? Carbon::parse($booking->tanggal_habis_custom)
                        : ($tanggalMasuk ? $tanggalMasuk->copy()->addMonths((int) $booking->durasi) : null);

                    $today = now()->startOfDay();
                    $daysLeft = $tanggalHabis ? $today->diffInDays($tanggalHabis, false) : null;

                    if ($daysLeft === null) {
                        $statusText = '-';
                        $badgeBg = '#f1f5f9';
                        $badgeColor = '#64748b';
                        $alertText = 'Data masa sewa belum lengkap.';
                    } elseif ($daysLeft < 0) {
                        $statusText = 'Expired';
                        $badgeBg = '#fee2e2';
                        $badgeColor = '#dc2626';
                        $alertText = 'Masa sewa sudah habis. Kamu bisa melakukan perpanjangan.';
                    } elseif ($daysLeft === 0) {
                        $statusText = 'Habis hari ini';
                        $badgeBg = '#fef3c7';
                        $badgeColor = '#d97706';
                        $alertText = 'Masa sewa habis hari ini. Segera lakukan perpanjangan.';
                    } elseif ($daysLeft <= 7) {
                        $statusText = 'Hampir habis';
                        $badgeBg = '#fef3c7';
                        $badgeColor = '#d97706';
                        $alertText = 'Masa sewa akan habis dalam ' . $daysLeft . ' hari.';
                    } else {
                        $statusText = 'Aktif';
                        $badgeBg = '#dcfce7';
                        $badgeColor = '#16a34a';
                        $alertText = 'Masa sewa masih aktif.';
                    }
                @endphp

                <div class="rental-card">
                    <div class="rental-card-body">
                        <div>
                            <div class="room-head">
                                <h2 class="room-title">
                                    {{ $booking->kamar->nama ?? 'Kamar' }}
                                </h2>

                                <span class="status-pill" style="background:{{ $badgeBg }}; color:{{ $badgeColor }};">
                                    {{ $statusText }}
                                </span>
                            </div>

                            <p class="invoice-text">
                                Invoice: <strong>{{ $booking->invoice }}</strong>
                            </p>

                            <div class="rental-grid">
                                <div class="info-box">
                                    <div class="info-label">Tanggal Masuk</div>
                                    <div class="info-value">
                                        {{ $tanggalMasuk ? $tanggalMasuk->format('d/m/Y') : '-' }}
                                    </div>
                                </div>

                                <div class="info-box">
                                    <div class="info-label">Tanggal Habis</div>
                                    <div class="info-value">
                                        {{ $tanggalHabis ? $tanggalHabis->format('d/m/Y') : '-' }}
                                    </div>
                                </div>

                                <div class="info-box">
                                    <div class="info-label">Durasi</div>
                                    <div class="info-value">
                                        {{ $booking->durasi }} Bulan
                                    </div>
                                </div>

                                <div class="info-box">
                                    <div class="info-label">Sisa Masa Sewa</div>
                                    <div class="info-value" style="color:{{ $badgeColor }};">
                                        @if($daysLeft === null)
                                            -
                                        @elseif($daysLeft < 0)
                                            Lewat {{ abs($daysLeft) }} hari
                                        @else
                                            {{ $daysLeft }} hari lagi
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="rental-note" style="background:{{ $badgeBg }}; color:{{ $badgeColor }};">
                                {{ $alertText }}
                            </div>
                        </div>

                        <div class="renew-card">
                            <h3 class="renew-title">Perpanjang Sewa</h3>

                            <form method="POST" action="{{ route('my-rentals.renew', $booking) }}">
                                @csrf

                                <label class="form-label">Durasi Perpanjangan</label>
                                <select name="durasi" class="form-control">
                                    <option value="1">1 Bulan</option>
                                    <option value="2">2 Bulan</option>
                                    <option value="3">3 Bulan</option>
                                    <option value="6">6 Bulan</option>
                                    <option value="12">12 Bulan</option>
                                </select>

                                <label class="form-label">Jumlah Orang</label>
                                <select name="orang" class="form-control">
                                    <option value="1" {{ (int) $booking->orang === 1 ? 'selected' : '' }}>1 Orang</option>
                                    <option value="2" {{ (int) $booking->orang === 2 ? 'selected' : '' }}>2 Orang</option>
                                </select>

                                <button type="submit" class="btn-primary">
                                    Buat Invoice Perpanjangan
                                </button>
                            </form>

                            <p class="renew-help">
                                Invoice baru akan dibuat. Setelah dibayar, masa sewa akan tercatat sebagai booking paid.
                            </p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="empty-card">
                    <h2 class="empty-title">
                        Belum Ada Sewa Aktif
                    </h2>

                    <p class="empty-text">
                        Kamu belum memiliki booking dengan status pembayaran paid.
                    </p>

                    <a href="{{ url('/') }}" class="btn-search">
                        Cari Kamar
                    </a>
                </div>
            @endforelse
        </div>

        {{-- RIWAYAT PEMBAYARAN --}}
        <div class="history-section">
            <div class="history-top">
                <div>
                    <h2 class="section-title">
                        Riwayat Pembayaran
                    </h2>

                    <p class="section-subtitle">
                        Default menampilkan invoice hari ini. Pilih rentang tanggal untuk melihat invoice lama.
                    </p>
                </div>

                <div class="history-tools">
                    <form class="date-filter-form" id="dateFilterForm">
                        <input
                            type="date"
                            name="start_date"
                            id="startDateInput"
                            class="date-input"
                            value="{{ $filterStartDate }}"
                        >

                        <span class="date-separator">s/d</span>

                        <input
                            type="date"
                            name="end_date"
                            id="endDateInput"
                            class="date-input"
                            value="{{ $filterEndDate }}"
                        >

                        <button type="button" id="todayFilterBtn" class="btn-today">
                            Hari ini
                        </button>
                    </form>

                    <div class="invoice-search-wrap">
                        <input
                            type="text"
                            id="invoiceSearchInput"
                            class="invoice-search"
                            placeholder="Cari invoice..."
                            autocomplete="off"
                        >
                    </div>
                </div>
            </div>

            <div class="history-card" id="historyCard">
                <div class="table-scroll">
                    <table class="history-table">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Invoice</th>
                                <th>Kamar</th>
                                <th>Keterangan</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th style="text-align:right;">Aksi</th>
                            </tr>
                        </thead>

                        <tbody id="historyTableBody">
                            @forelse(($paymentHistories ?? collect()) as $history)
                                @php
                                    $historyStatus = $history->payment_status ?? 'pending';

                                    $historyStatusStyle = match($historyStatus) {
                                        'paid' => 'background:#dcfce7; color:#166534;',
                                        'pending' => 'background:#fef3c7; color:#b45309;',
                                        'canceled' => 'background:#fee2e2; color:#991b1b;',
                                        default => 'background:#f1f5f9; color:#64748b;',
                                    };

                                    $historyStatusText = match($historyStatus) {
                                        'paid' => 'Lunas',
                                        'pending' => 'Pending',
                                        'canceled' => 'Batal',
                                        default => ucfirst($historyStatus),
                                    };

                                    $invoiceText = $history->invoice ?? '';
                                    $dateText = $history->created_at ? $history->created_at->format('d/m/Y') : '-';
                                @endphp

                                <tr class="history-row" data-invoice="{{ strtolower($invoiceText) }}">
                                    <td>
                                        <span class="date-text">{{ $dateText }}</span>
                                    </td>

                                    <td>
                                        @if(!empty($history->invoice))
                                            @if($historyStatus === 'paid')
                                                <a href="{{ route('booking.invoice', $history->invoice) }}" class="invoice-link">
                                                    {{ $history->invoice }}
                                                </a>
                                            @elseif($historyStatus === 'pending')
                                                <a href="{{ route('booking.methods', $history->invoice) }}" class="invoice-link">
                                                    {{ $history->invoice }}
                                                </a>
                                            @else
                                                <span class="invoice-muted">
                                                    {{ $history->invoice }}
                                                </span>
                                            @endif
                                        @else
                                            <span class="invoice-muted">-</span>
                                        @endif
                                    </td>

                                    <td>
                                        <span class="room-name">
                                            {{ $history->kamar->nama ?? 'Kamar' }}
                                        </span>
                                    </td>

                                    <td>
                                        <span class="mini-pill">
                                            {{ $history->tanggal_masuk ?? '-' }}
                                        </span>

                                        <span class="mini-pill">
                                            {{ $history->durasi ?? '-' }} Bln
                                        </span>
                                    </td>

                                    <td>
                                        <span class="price">
                                            Rp {{ number_format($history->payment_total ?? $history->total_harga ?? 0, 0, ',', '.') }}
                                        </span>
                                    </td>

                                    <td>
                                        <span class="payment-status" style="{{ $historyStatusStyle }}">
                                            {{ $historyStatusText }}
                                        </span>
                                    </td>

                                    <td style="text-align:right;">
                                        @if($historyStatus === 'pending')
                                            <form method="POST"
                                                  action="{{ route('payment-history.cancel', $history) }}"
                                                  onsubmit="return confirm('Batalkan invoice ini?');"
                                                  style="margin:0;">
                                                @csrf

                                                <button type="submit" class="btn-cancel">
                                                    Batalkan
                                                </button>
                                            </form>
                                        @else
                                            <span style="font-size:11px; color:#94a3b8;">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="empty-history">
                                        Belum ada riwayat pembayaran pada tanggal ini.
                                    </td>
                                </tr>
                            @endforelse

                            <tr id="noInvoiceResult" class="no-search-result">
                                <td colspan="7" class="empty-history">
                                    Invoice tidak ditemukan.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const input = document.getElementById('invoiceSearchInput');
        const tbody = document.getElementById('historyTableBody');
        const historyCard = document.getElementById('historyCard');
        const startDateInput = document.getElementById('startDateInput');
        const endDateInput = document.getElementById('endDateInput');
        const todayFilterBtn = document.getElementById('todayFilterBtn');

        const historyEndpoint = @json(route('my-rentals.history-data'));
        const csrfToken = @json(csrf_token());

        let dateFetchTimer = null;

        function escapeHtml(value) {
            return String(value ?? '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function applyInvoiceSearch() {
            const keyword = input ? input.value.toLowerCase().trim() : '';
            const rows = document.querySelectorAll('.history-row');
            const noResult = document.getElementById('noInvoiceResult');

            let visibleCount = 0;

            rows.forEach(function (row) {
                const invoice = row.getAttribute('data-invoice') || '';

                if (invoice.includes(keyword)) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            if (noResult) {
                noResult.style.display = visibleCount === 0 && keyword !== '' ? 'table-row' : 'none';
            }
        }

        function setHistoryLoading(isLoading) {
            if (!historyCard) return;

            if (isLoading) {
                historyCard.classList.add('is-loading');
            } else {
                historyCard.classList.remove('is-loading');
            }
        }

        function renderLoadingRow() {
            if (!tbody) return;

            tbody.innerHTML = `
                <tr>
                    <td colspan="7" class="empty-history">
                        Memuat riwayat pembayaran...
                    </td>
                </tr>
            `;
        }

        function renderEmptyRow(message = 'Belum ada riwayat pembayaran pada tanggal ini.') {
            if (!tbody) return;

            tbody.innerHTML = `
                <tr>
                    <td colspan="7" class="empty-history">
                        ${escapeHtml(message)}
                    </td>
                </tr>

                <tr id="noInvoiceResult" class="no-search-result">
                    <td colspan="7" class="empty-history">
                        Invoice tidak ditemukan.
                    </td>
                </tr>
            `;
        }

        function renderHistoryRows(items) {
            if (!tbody) return;

            if (!items || items.length === 0) {
                renderEmptyRow();
                return;
            }

            let html = '';

            items.forEach(function (item) {
                let invoiceHtml = `
                    <span class="invoice-muted">
                        ${escapeHtml(item.invoice)}
                    </span>
                `;

                if (item.invoice_url) {
                    invoiceHtml = `
                        <a href="${escapeHtml(item.invoice_url)}" class="invoice-link">
                            ${escapeHtml(item.invoice)}
                        </a>
                    `;
                }

                if (item.payment_url) {
                    invoiceHtml = `
                        <a href="${escapeHtml(item.payment_url)}" class="invoice-link">
                            ${escapeHtml(item.invoice)}
                        </a>
                    `;
                }

                let actionHtml = `
                    <span style="font-size:11px; color:#94a3b8;">
                        -
                    </span>
                `;

                if (item.cancel_url) {
                    actionHtml = `
                        <form method="POST"
                              action="${escapeHtml(item.cancel_url)}"
                              onsubmit="return confirm('Batalkan invoice ini?');"
                              style="margin:0;">
                            <input type="hidden" name="_token" value="${escapeHtml(csrfToken)}">

                            <button type="submit" class="btn-cancel">
                                Batalkan
                            </button>
                        </form>
                    `;
                }

                html += `
                    <tr class="history-row" data-invoice="${escapeHtml(String(item.invoice).toLowerCase())}">
                        <td>
                            <span class="date-text">
                                ${escapeHtml(item.tanggal)}
                            </span>
                        </td>

                        <td>
                            ${invoiceHtml}
                        </td>

                        <td>
                            <span class="room-name">
                                ${escapeHtml(item.kamar)}
                            </span>
                        </td>

                        <td>
                            <span class="mini-pill">
                                ${escapeHtml(item.tanggal_masuk)}
                            </span>

                            <span class="mini-pill">
                                ${escapeHtml(item.durasi)}
                            </span>
                        </td>

                        <td>
                            <span class="price">
                                ${escapeHtml(item.total)}
                            </span>
                        </td>

                        <td>
                            <span class="payment-status" style="${escapeHtml(item.status_style)}">
                                ${escapeHtml(item.status_text)}
                            </span>
                        </td>

                        <td style="text-align:right;">
                            ${actionHtml}
                        </td>
                    </tr>
                `;
            });

            html += `
                <tr id="noInvoiceResult" class="no-search-result">
                    <td colspan="7" class="empty-history">
                        Invoice tidak ditemukan.
                    </td>
                </tr>
            `;

            tbody.innerHTML = html;
            applyInvoiceSearch();
        }

        async function fetchHistoryByDate() {
            if (!startDateInput || !endDateInput) return;

            const startDate = startDateInput.value;
            const endDate = endDateInput.value;

            if (!startDate || !endDate) return;

            setHistoryLoading(true);
            renderLoadingRow();

            const url = new URL(historyEndpoint, window.location.origin);
            url.searchParams.set('start_date', startDate);
            url.searchParams.set('end_date', endDate);

            try {
                const response = await fetch(url.toString(), {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const result = await response.json();

                if (!response.ok || !result.success) {
                    renderEmptyRow('Gagal memuat riwayat pembayaran.');
                    return;
                }

                renderHistoryRows(result.data);

                const pageUrl = new URL(window.location.href);
                pageUrl.searchParams.set('start_date', startDate);
                pageUrl.searchParams.set('end_date', endDate);
                window.history.replaceState({}, '', pageUrl.toString());

            } catch (error) {
                renderEmptyRow('Gagal memuat riwayat pembayaran.');
            } finally {
                setHistoryLoading(false);
            }
        }

        function scheduleDateFetch() {
            clearTimeout(dateFetchTimer);

            dateFetchTimer = setTimeout(function () {
                fetchHistoryByDate();
            }, 250);
        }

        if (input) {
            input.addEventListener('input', applyInvoiceSearch);
        }

        if (startDateInput) {
            startDateInput.addEventListener('change', scheduleDateFetch);
        }

        if (endDateInput) {
            endDateInput.addEventListener('change', scheduleDateFetch);
        }

        if (todayFilterBtn) {
            todayFilterBtn.addEventListener('click', function () {
                const today = new Date().toISOString().slice(0, 10);

                if (startDateInput) {
                    startDateInput.value = today;
                }

                if (endDateInput) {
                    endDateInput.value = today;
                }

                fetchHistoryByDate();
            });
        }
    });
</script>
@endsection