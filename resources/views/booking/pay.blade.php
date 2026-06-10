
@extends('layouts.app')

@section('content')
@php
    $status = strtolower($booking->payment_status ?? 'pending');

    $statusText = match($status) {
        'paid' => 'Paid',
        'failed' => 'Failed',
        'expired' => 'Expired',
        default => 'Pending',
    };

    $statusClass = match($status) {
        'paid' => 'status-paid',
        'failed', 'expired' => 'status-failed',
        default => 'status-pending',
    };

    $paymentGateway = strtoupper($booking->payment_gateway ?? '-');

    $methodName = $booking->payment_method_name ?? '-';
    $methodCodeRaw = $booking->payment_method_code ?? '';
    $methodCode = strtolower($methodCodeRaw);
    $methodCodeUpper = strtoupper($methodCodeRaw ?: '-');

    $methodLogo = optional($method ?? null)->logo;

    if ($methodLogo) {
        $methodLogoUrl = \Illuminate\Support\Str::startsWith($methodLogo, ['http://', 'https://'])
            ? $methodLogo
            : asset(ltrim($methodLogo, '/'));
    } else {
        $methodLogoUrl = asset('images/KKJA.png');
    }

    $isQris = str_contains($methodCode, 'qris')
        || in_array($methodCodeUpper, ['11', '17', '23']);

    $isVa = str_contains($methodCode, 'va')
        || str_contains($methodCode, 'virtual')
        || str_contains($methodCode, 'bca')
        || str_contains($methodCode, 'bni')
        || str_contains($methodCode, 'bri')
        || str_contains($methodCode, 'mandiri')
        || str_contains($methodCode, 'permata')
        || str_contains($methodCode, 'cimb')
        || str_contains($methodCode, 'danamon')
        || str_contains($methodCode, 'bnc')
        || str_contains($methodCode, 'bsi')
        || str_contains(strtolower($methodName), 'virtual account');

    $isEwallet = str_contains($methodCode, 'dana')
        || str_contains($methodCode, 'gopay')
        || str_contains($methodCode, 'ovo')
        || str_contains($methodCode, 'shopee')
        || str_contains($methodCode, 'linkaja')
        || str_contains($methodCode, 'astrapay')
        || str_contains($methodCode, 'virgo')
        || str_contains($methodCode, 'ovopush')
        || str_contains(strtolower($methodName), 'dana')
        || str_contains(strtolower($methodName), 'gopay')
        || str_contains(strtolower($methodName), 'ovo')
        || str_contains(strtolower($methodName), 'shopee');

    $hargaSewa = (int) ($booking->total_harga ?? 0);
    $biayaLayanan = (int) ($booking->payment_fee ?? 0);
    $lateFee = (int) ($booking->late_fee ?? 0);
    $totalBayar = (int) ($booking->payment_total ?: ($hargaSewa + $biayaLayanan + $lateFee));

    $qrString = $booking->qr_string
        ?? $booking->qris_string
        ?? $booking->qr_content
        ?? null;

    $qrImage = $booking->qr_url
        ?? $booking->qris_url
        ?? $booking->qr_image
        ?? null;

    $paymentUrl = $booking->payment_url ?? null;

    $isQrStringImageUrl = $qrString
        ? \Illuminate\Support\Str::startsWith($qrString, ['http://', 'https://'])
        : false;

    // Menyiapkan URL Gambar QRIS yang akan diunduh
    $downloadUrl = $qrImage ?? ($isQrStringImageUrl ? $qrString : ($qrString ? "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" . urlencode($qrString) : null));

    $tanggalMasuk = $booking->tanggal_masuk
        ? \Carbon\Carbon::parse($booking->tanggal_masuk)
        : null;

    $tanggalHabis = null;

    if ($tanggalMasuk) {
        $tanggalHabis = $booking->tanggal_habis_custom
            ? \Carbon\Carbon::parse($booking->tanggal_habis_custom)
            : $tanggalMasuk->copy()->addMonths((int) $booking->durasi);
    }
@endphp

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    * {
        box-sizing: border-box;
    }

    .pay-root {
        min-height: 100vh;
        background: #ffffff;
        padding: 90px 18px 70px;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        color: #111827;
    }

    .pay-wrap {
        max-width: 1180px;
        margin: 0 auto;
    }

    .pay-grid {
        display: grid;
        grid-template-columns: 1fr 430px;
        gap: 24px;
        align-items: start;
    }

    .pay-card,
    .pay-side-card {
        background: #ffffff;
        border: 1px solid #d9d9d9;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(15, 23, 42, 0.06);
    }

    .pay-card {
        padding: 34px 38px;
    }

    .pay-side-card {
        min-height: 430px;
        padding: 32px;
        position: sticky;
        top: 90px;
    }

    .top-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 16px;
        margin-bottom: 24px;
    }

    .invoice-label {
        font-size: 10px;
        color: #b8b8b8;
        font-weight: 500;
        text-transform: uppercase;
        margin-bottom: 6px;
    }

    .invoice-number {
        font-size: 24px;
        color: #000000;
        font-weight: 700;
        line-height: 1.1;
        word-break: break-word;
    }

    .status-badge {
        min-width: 110px;
        text-align: center;
        padding: 7px 14px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 500;
        text-transform: capitalize;
    }

    .status-pending {
        background: #f6f88d;
        color: #a16207;
    }

    .status-paid {
        background: #dcfce7;
        color: #16a34a;
    }

    .status-failed {
        background: #fee2e2;
        color: #dc2626;
    }

    .total-box {
        background: #000000;
        color: #ffffff;
        border-radius: 9px;
        padding: 26px 32px;
        margin-bottom: 16px;
        display: flex;
        justify-content: space-between;
        gap: 22px;
        align-items: center;
    }

    .total-label {
        font-size: 11px;
        color: #d1d5db;
        font-weight: 500;
        text-transform: uppercase;
        margin-bottom: 10px;
    }

    .total-value {
        font-size: 25px;
        color: #ffffff;
        font-weight: 700;
        line-height: 1.1;
    }

    .total-warning {
        background: #ffffff;
        color: #111827;
        border-radius: 999px;
        padding: 11px 18px;
        font-size: 12px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 9px;
        white-space: nowrap;
    }

    .warning-icon {
        width: 24px;
        height: 24px;
        border: 2px solid #111827;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        font-weight: 700;
        flex-shrink: 0;
    }

    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
        margin-bottom: 24px;
    }

    .info-box {
        border: 1px solid #dedede;
        border-radius: 10px;
        min-height: 92px;
        padding: 18px 20px;
    }

    .info-title {
        font-size: 10px;
        color: #b8b8b8;
        font-weight: 500;
        text-transform: uppercase;
        margin-bottom: 16px;
    }

    .info-content {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .info-icon {
        width: 26px;
        height: 26px;
        object-fit: contain;
        flex-shrink: 0;
    }

    .method-info-logo {
        border-radius: 6px;
        object-fit: contain;
    }

    .info-name {
        font-size: 13px;
        color: #111827;
        font-weight: 700;
        line-height: 1.2;
    }

    .info-sub {
        margin-top: 3px;
        font-size: 10px;
        color: #8b8b8b;
        font-weight: 400;
        word-break: break-word;
    }

    .btn-pay {
        display: block;
        width: 100%;
        border: 0;
        background: #0ea5e9;
        color: #ffffff;
        padding: 15px 18px;
        border-radius: 999px;
        text-align: center;
        text-decoration: none;
        font-size: 13px;
        font-weight: 500;
        font-family: inherit;
        cursor: pointer;
    }

    .btn-pay:hover {
        background: #0284c7;
        color: #ffffff;
    }

    .mini-note {
        margin-top: 14px;
        font-size: 11px;
        color: #9ca3af;
        text-align: center;
        line-height: 1.6;
    }

    .qris-inline-box {
        margin-top: 24px;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        padding: 22px;
        background: #fafafa;
    }

    .qris-inline-title {
        font-size: 15px;
        font-weight: 700;
        color: #111827;
        margin-bottom: 6px;
    }

    .qris-inline-desc {
        font-size: 12px;
        color: #6b7280;
        line-height: 1.6;
        margin-bottom: 18px;
    }

    .qris-preview {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 18px;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        background: #ffffff;
        margin-bottom: 18px;
    }

    .qris-preview img {
        width: 260px;
        height: 260px;
        object-fit: contain;
        background: #ffffff;
        padding: 10px;
        border-radius: 12px;
    }

    .copy-btn {
        margin-top: 16px;
        width: 100%;
        border: 0;
        background: #111827;
        color: #ffffff;
        border-radius: 9px;
        padding: 14px;
        font-size: 13px;
        font-weight: 600;
        font-family: inherit;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: 0.2s;
    }

    .copy-btn:hover {
        background: #000000;
    }

    .booking-detail-head {
        margin-bottom: 22px;
    }

    .booking-detail-label {
        font-size: 10px;
        color: #b8b8b8;
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: .5px;
        margin-bottom: 6px;
    }

    .booking-detail-title {
        font-size: 22px;
        color: #111827;
        font-weight: 700;
        line-height: 1.2;
    }

    .booking-detail-sub {
        margin-top: 6px;
        font-size: 12px;
        color: #6b7280;
        line-height: 1.5;
    }

    .booking-detail-list {
        display: grid;
        gap: 0;
    }

    .booking-detail-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 16px;
        padding: 13px 0;
        border-bottom: 1px solid #eeeeee;
    }

    .booking-detail-row:last-child {
        border-bottom: 0;
    }

    .booking-detail-row span {
        font-size: 12px;
        color: #6b7280;
    }

    .booking-detail-row strong {
        font-size: 12px;
        color: #111827;
        font-weight: 700;
        text-align: right;
        line-height: 1.4;
        word-break: break-word;
    }

    .booking-method-mini {
        margin-top: 22px;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 14px 16px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .booking-method-mini img {
        width: 42px;
        height: 30px;
        object-fit: contain;
        flex-shrink: 0;
    }

    .booking-method-mini-title {
        font-size: 12px;
        color: #111827;
        font-weight: 700;
    }

    .booking-method-mini-sub {
        margin-top: 3px;
        font-size: 10px;
        color: #8b8b8b;
    }

    .booking-total-box {
        margin-top: 22px;
        background: #000000;
        color: #ffffff;
        border-radius: 12px;
        padding: 18px 20px;
    }

    .booking-total-box span {
        display: block;
        font-size: 11px;
        color: #d1d5db;
        text-transform: uppercase;
        margin-bottom: 7px;
    }

    .booking-total-box strong {
        display: block;
        font-size: 24px;
        color: #ffffff;
        font-weight: 700;
    }

    .summary-card {
        margin-top: 24px;
        background: #f7f7f7;
        border-radius: 12px;
        padding: 16px 18px;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        padding: 9px 0;
        border-bottom: 1px solid #e5e7eb;
    }

    .summary-row:last-child {
        border-bottom: 0;
    }

    .summary-row span {
        font-size: 12px;
        color: #6b7280;
    }

    .summary-row strong {
        font-size: 12px;
        color: #111827;
        text-align: right;
    }

    .empty-box {
        border: 1px dashed #d1d5db;
        border-radius: 14px;
        padding: 30px 20px;
        text-align: center;
        color: #6b7280;
        font-size: 13px;
        line-height: 1.6;
    }

    @media (max-width: 900px) {
        .pay-grid {
            grid-template-columns: 1fr;
        }

        .pay-side-card {
            position: static;
            min-height: auto;
        }
    }

    @media (max-width: 640px) {
        .pay-root {
            padding: 80px 12px 45px;
        }

        .pay-card,
        .pay-side-card {
            padding: 22px;
        }

        .top-row,
        .total-box {
            flex-direction: column;
            align-items: flex-start;
        }

        .total-warning {
            white-space: normal;
        }

        .info-grid {
            grid-template-columns: 1fr;
        }

        .invoice-number {
            font-size: 20px;
        }

        .total-value {
            font-size: 20px;
        }

        .qris-preview img {
            width: 230px;
            height: 230px;
        }
    }
</style>

<div class="pay-root">
    <div class="pay-wrap">

        <div class="pay-grid">

            {{-- LEFT CARD --}}
            <div class="pay-card">

                <div class="top-row">
                    <div>
                        <div class="invoice-label">No Invoice</div>
                        <div class="invoice-number">{{ $booking->invoice }}</div>
                    </div>

                    <span class="status-badge {{ $statusClass }}">
                        {{ $statusText }}
                    </span>
                </div>

                <div class="total-box">
                    <div>
                        <div class="total-label">Total yang harus dibayar</div>
                        <div class="total-value">
                            Rp {{ number_format($totalBayar, 0, ',', '.') }}
                        </div>
                    </div>

                    <div class="total-warning">
                        <span class="warning-icon">!</span>
                        Pastikan nominal pembayaran sesuai dengan total di atas.
                    </div>
                </div>

                <div class="info-grid">
                    <div class="info-box">
                        <div class="info-title">Gateway</div>

                        <div class="info-content">
                            <img src="{{ asset('images/KKJA.png') }}" alt="Payment Gateway" class="info-icon">

                            <div>
                                <div class="info-name">{{ $paymentGateway }}</div>
                                <div class="info-sub">Payment Gateway</div>
                            </div>
                        </div>
                    </div>

                    <div class="info-box">
                        <div class="info-title">Metode</div>

                        <div class="info-content">
                            <img src="{{ $methodLogoUrl }}" alt="{{ $methodName }}" class="info-icon method-info-logo">

                            <div>
                                <div class="info-name">{{ $methodName }}</div>
                                <div class="info-sub">{{ $methodCodeUpper }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                @if($status !== 'paid')
                    @if($isQris)
                        <a href="#qris-pembayaran" class="btn-pay">
                            Lihat QRIS Pembayaran
                        </a>
                    @elseif(($isEwallet || $isVa) && $paymentUrl)
                        <a href="{{ $paymentUrl }}" target="_blank" class="btn-pay">
                            Lanjut ke {{ $methodName }}
                        </a>
                    @elseif($paymentUrl)
                        <a href="{{ $paymentUrl }}" target="_blank" class="btn-pay">
                            Bayar Sekarang
                        </a>
                    @else
                        <button type="button" class="btn-pay" disabled>
                            Menunggu Data Pembayaran
                        </button>
                    @endif

                    <div class="mini-note">
                        Setelah pembayaran berhasil, status booking akan otomatis berubah menjadi paid.
                    </div>
                @else
                    <a href="{{ route('my-rentals.index') }}" class="btn-pay">
                        Lihat Sewa Saya
                    </a>
                @endif

                @if($status !== 'paid' && $isQris)
                    <div class="qris-inline-box" id="qris-pembayaran">
                        <div class="qris-inline-title">
                            Scan QRIS
                        </div>

                        <div class="qris-inline-desc">
                            Scan QRIS di bawah menggunakan mobile banking atau e-wallet. Pastikan nominal pembayaran sesuai.
                        </div>

                        @if($qrImage)
                            <div class="qris-preview">
                                <img src="{{ $qrImage }}" alt="QRIS Pembayaran">
                            </div>
                        @elseif($qrString && $isQrStringImageUrl)
                            <div class="qris-preview">
                                <img src="{{ $qrString }}" alt="QRIS Pembayaran">
                            </div>
                        @elseif($qrString)
                            <div class="qris-preview">
                                <img
                                    src="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data={{ urlencode($qrString) }}"
                                    alt="QRIS Pembayaran"
                                >
                            </div>
                        @else
                            <div class="empty-box">
                                QRIS belum diterima dari gateway. Coba refresh halaman beberapa saat lagi.
                            </div>
                        @endif

                        {{-- TOMBOL UNDUH QRIS --}}
                        @if($downloadUrl)
                            <button type="button" class="copy-btn" data-download="{{ $downloadUrl }}">
                                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                                Unduh QRIS
                            </button>
                        @endif
                    </div>
                @endif

                <div class="summary-card">
                    <div class="summary-row">
                        <span>Kamar</span>
                        <strong>{{ $booking->kamar->nama ?? '-' }}</strong>
                    </div>

                    <div class="summary-row">
                        <span>Durasi</span>
                        <strong>{{ $booking->durasi }} Bulan</strong>
                    </div>

                    <div class="summary-row">
                        <span>Orang</span>
                        <strong>{{ $booking->orang ?? 1 }} Orang</strong>
                    </div>

                    <div class="summary-row">
                        <span>Tanggal Masuk</span>
                        <strong>{{ $tanggalMasuk ? $tanggalMasuk->format('d M Y') : '-' }}</strong>
                    </div>
                </div>

            </div>

            {{-- RIGHT CARD: DETAIL BOOKING --}}
            <div class="pay-side-card">

                <div class="booking-detail-head">
                    <div class="booking-detail-label">Detail Booking</div>

                    <div class="booking-detail-title">
                        {{ $booking->kamar->nama ?? 'Kamar' }}
                    </div>

                    <div class="booking-detail-sub">
                        Berikut detail kamar yang kamu booking di Rafa Kost.
                    </div>
                </div>

                <div class="booking-detail-list">
                    <div class="booking-detail-row">
                        <span>No Invoice</span>
                        <strong>{{ $booking->invoice }}</strong>
                    </div>

                    <div class="booking-detail-row">
                        <span>Nama Penyewa</span>
                        <strong>{{ $booking->customer_name ?? '-' }}</strong>
                    </div>

                    <div class="booking-detail-row">
                        <span>No WhatsApp</span>
                        <strong>{{ $booking->customer_phone ?? '-' }}</strong>
                    </div>

                    <div class="booking-detail-row">
                        <span>Email</span>
                        <strong>{{ $booking->customer_email ?? '-' }}</strong>
                    </div>

                    <div class="booking-detail-row">
                        <span>Tanggal Masuk</span>
                        <strong>{{ $tanggalMasuk ? $tanggalMasuk->format('d M Y') : '-' }}</strong>
                    </div>

                    <div class="booking-detail-row">
                        <span>Tanggal Habis</span>
                        <strong>{{ $tanggalHabis ? $tanggalHabis->format('d M Y') : '-' }}</strong>
                    </div>

                    <div class="booking-detail-row">
                        <span>Durasi</span>
                        <strong>{{ $booking->durasi }} Bulan</strong>
                    </div>

                    <div class="booking-detail-row">
                        <span>Jumlah Orang</span>
                        <strong>{{ $booking->orang ?? 1 }} Orang</strong>
                    </div>

                    <div class="booking-detail-row">
                        <span>Status</span>
                        <strong style="color:{{ $status === 'paid' ? '#16a34a' : '#0ea5e9' }};">
                            {{ $statusText }}
                        </strong>
                    </div>
                </div>

                <div class="booking-method-mini">
                    <img src="{{ $methodLogoUrl }}" alt="{{ $methodName }}">

                    <div>
                        <div class="booking-method-mini-title">
                            {{ $methodName }}
                        </div>

                        <div class="booking-method-mini-sub">
                            {{ $paymentGateway }} · {{ $methodCodeUpper }}
                        </div>
                    </div>
                </div>

                <div class="booking-total-box">
                    <span>Total Pembayaran</span>
                    <strong>Rp {{ number_format($totalBayar, 0, ',', '.') }}</strong>
                </div>

            </div>

        </div>

    </div>
</div>

<script>
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('[data-download]');
        if (!btn) return;

        const url = btn.getAttribute('data-download');
        const oldText = btn.innerHTML;
        btn.innerText = 'Mengunduh...';

        // Fetch gambar untuk menjadikannya blob lalu di-download paksa (mencegah tab baru)
        fetch(url)
            .then(response => response.blob())
            .then(blob => {
                const blobUrl = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.style.display = 'none';
                a.href = blobUrl;
                a.download = 'QRIS_Invoice_{{ $booking->invoice }}.png';
                document.body.appendChild(a);
                a.click();
                
                window.URL.revokeObjectURL(blobUrl);
                document.body.removeChild(a);

                btn.innerText = 'Berhasil Diunduh';
                setTimeout(() => {
                    btn.innerHTML = oldText;
                }, 2000);
            })
            .catch(() => {
                // Fallback jika API ter-blokir CORS (membuka tab baru)
                window.open(url, '_blank');
                btn.innerHTML = oldText;
            });
    });
</script>
@endsection

