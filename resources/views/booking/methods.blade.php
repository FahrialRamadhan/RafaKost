@extends('layouts.app')

@section('content')

@php
    $categoryOrder = ['qris', 'e-wallet', 'virtual-account', 'saldo', 'lainnya'];

    $categoryLabels = [
        'qris'            => 'QRIS',
        'e-wallet'        => 'E-Wallet',
        'virtual-account' => 'Virtual Account',
        'saldo'           => 'Saldo',
        'lainnya'         => 'Lainnya',
    ];

    $categoryDescriptions = [
        'qris'            => 'Scan QRIS dari mobile banking atau e-wallet favoritmu.',
        'e-wallet'        => 'Bayar cepat pakai DANA, OVO, ShopeePay, dan dompet digital lain.',
        'virtual-account' => 'Transfer melalui nomor virtual account bank.',
        'saldo'           => 'Gunakan saldo akun jika tersedia.',
        'lainnya'         => 'Metode pembayaran lain yang tersedia.',
    ];

    $groupedMethods = $methods
        ->sortBy(function ($method) use ($categoryOrder) {
            $index = array_search($method->category, $categoryOrder);
            return $index === false ? 99 : $index;
        })
        ->groupBy('category');

	$hargaSewa = (int) ($booking->total_harga ?? 0);
	$lateFee = (int) ($booking->late_fee ?? 0);
	$lateDays = (int) ($booking->late_days ?? 0);
	
	$baseAmount = $hargaSewa + $lateFee;
@endphp

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    * {
        box-sizing: border-box;
    }

    .payment-page {
        min-height: 100vh;
        background: #ffffff;
        padding: 86px 16px 60px;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        color: #111827;
    }

    .payment-container {
        max-width: 1120px;
        margin: 0 auto;
    }

    .payment-hero {
        position: relative;
        overflow: hidden;
        background: #000000;
        color: #ffffff;
        border-radius: 12px 12px 0 0;
        padding: 42px 50px 46px;
        min-height: 185px;
    }

    .payment-hero-content {
        position: relative;
        z-index: 2;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 28px;
    }

    .hero-left {
        max-width: 460px;
    }

    .payment-label {
        margin: 0 0 8px;
        font-size: 10px;
        color: #d1d5db;
        font-weight: 500;
        letter-spacing: .9px;
        text-transform: uppercase;
    }

    .payment-title {
        margin: 0;
        font-size: 34px;
        line-height: .98;
        color: #ffffff;
        font-weight: 700;
        letter-spacing: -.7px;
    }

    .invoice-pill {
        display: inline-flex;
        align-items: center;
        margin-top: 12px;
        background: #ffffff;
        color: #111827;
        border-radius: 999px;
        padding: 5px 11px;
        font-size: 10px;
        font-weight: 500;
    }

    .hero-total-card {
        width: 220px;
        background: #ffffff;
        color: #111827;
        border-radius: 7px;
        padding: 18px 20px;
        flex-shrink: 0;
    }

    .hero-total-label {
        font-size: 10px;
        color: #64748b;
        text-transform: uppercase;
        font-weight: 600;
        margin-bottom: 6px;
    }

    .hero-total-amount {
        font-size: 20px;
        font-weight: 700;
        color: #111827;
        line-height: 1.1;
    }

    .hero-total-note {
        margin-top: 8px;
        font-size: 11px;
        color: #64748b;
        line-height: 1.4;
    }

    .meta-strip {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-top: 0;
        box-shadow: 0 8px 18px rgba(15, 23, 42, .06);
    }

    .meta-item {
        padding: 15px 18px;
        border-right: 1px solid #e5e7eb;
    }

    .meta-item:last-child {
        border-right: 0;
    }

    .meta-key {
        font-size: 9px;
        color: #9ca3af;
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: .6px;
        margin-bottom: 4px;
    }

    .meta-value {
        font-size: 12px;
        color: #111827;
        font-weight: 600;
    }

    .payment-body {
        padding: 34px 0 0;
    }

    .back-row {
        margin-bottom: 24px;
    }

    .back-link {
        display: inline-flex;
        align-items: center;
        text-decoration: none;
        color: #64748b;
        font-size: 13px;
        font-weight: 600;
    }

    .back-link:hover {
        color: #0ea5e9;
    }

    .category-block {
        margin-bottom: 28px;
    }

    .category-summary {
        list-style: none;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 16px;
        margin-bottom: 16px;
    }

    .category-summary::-webkit-details-marker {
        display: none;
    }

    .category-heading {
        display: flex;
        gap: 13px;
        align-items: flex-start;
    }

    .category-icon {
        width: 40px;
        height: 40px;
        flex-shrink: 0;
        display: flex;
        align-items: flex-start;
        justify-content: center;
    }

    .category-icon img {
        width: 38px;
        height: 38px;
        object-fit: contain;
        display: block;
    }

    .category-title {
        font-size: 20px;
        color: #111827;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .3px;
        line-height: 1;
    }

    .category-desc {
        margin-top: 5px;
        font-size: 17px;
        color: #8b8b8b;
        line-height: 1.35;
        font-weight: 500;
    }

    .category-count {
        background: #0ea5e9;
        color: #ffffff;
        border-radius: 999px;
        padding: 7px 18px;
        font-size: 11px;
        font-weight: 500;
        white-space: nowrap;
    }

    .category-arrow {
        display: inline-block;
        margin-left: 8px;
        transition: transform .2s ease;
    }

    .category-block[open] .category-arrow {
        transform: rotate(180deg);
    }

    .category-content {
        padding-top: 2px;
    }

    .method-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 18px;
    }

    .method-form {
        display: block;
    }

    .method-card {
        width: 100%;
        min-height: 210px;
        background: #ffffff;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        padding: 18px;
        text-align: left;
        cursor: pointer;
        transition: .2s ease;
        font-family: inherit;
    }

    .method-card:hover {
        border-color: #0ea5e9;
        box-shadow: 0 10px 22px rgba(14, 165, 233, .14);
        transform: translateY(-2px);
    }

    .method-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 12px;
        margin-bottom: 18px;
    }

    .method-name {
        font-size: 12px;
        color: #111827;
        font-weight: 700;
        text-transform: uppercase;
    }

    .method-info {
        margin-top: 5px;
        font-size: 10px;
        color: #64748b;
        line-height: 1.4;
    }

    .method-code {
        margin-top: 5px;
        font-size: 9px;
        color: #9ca3af;
        font-weight: 500;
        word-break: break-word;
    }

    .method-logo {
        width: 64px;
        height: 32px;
        display: flex;
        align-items: flex-start;
        justify-content: flex-end;
        flex-shrink: 0;
    }

    .method-logo img {
        max-width: 62px;
        max-height: 28px;
        object-fit: contain;
    }

    .method-logo span {
        font-size: 9px;
        color: #9ca3af;
        font-weight: 600;
    }

    .price-box {
        margin-top: 10px;
    }

    .price-row {
        display: flex;
        justify-content: space-between;
        gap: 10px;
        margin-bottom: 10px;
    }

    .price-row span {
        font-size: 10px;
        color: #64748b;
    }

    .price-row strong {
        font-size: 10px;
        color: #111827;
        font-weight: 600;
        text-align: right;
    }

    .price-total {
        margin-top: 14px;
        padding-top: 12px;
        border-top: 1px dashed #d1d5db;
        display: flex;
        justify-content: space-between;
        gap: 10px;
    }

    .price-total span {
        font-size: 10px;
        color: #111827;
        font-weight: 600;
    }

    .price-total strong {
        font-size: 10px;
        color: #111827;
        font-weight: 600;
        text-align: right;
    }

    .choose-row {
        margin-top: 18px;
    }

    .choose-btn {
        display: block;
        width: 100%;
        background: #0ea5e9;
        color: #ffffff;
        border-radius: 8px;
        padding: 9px 12px;
        font-size: 11px;
        font-weight: 500;
        text-align: center;
    }

    .empty-box {
        text-align: center;
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        padding: 36px 22px;
    }

    .empty-title {
        font-size: 18px;
        font-weight: 700;
        color: #111827;
        margin-bottom: 8px;
    }

    .empty-desc {
        font-size: 13px;
        color: #6b7280;
        line-height: 1.6;
    }

    .footer-note {
        margin-top: 42px;
        background: #f3f4f6;
        border-radius: 8px;
        padding: 14px 20px;
        display: flex;
        gap: 12px;
        align-items: flex-start;
    }

    .footer-note-icon {
        width: 20px;
        height: 20px;
        border: 1px solid #111827;
        border-radius: 999px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        flex-shrink: 0;
        margin-top: 2px;
    }

    .footer-note p {
        margin: 0;
        font-size: 12px;
        color: #111827;
        line-height: 1.6;
    }

    @media (max-width: 1024px) {
        .payment-container {
            max-width: 920px;
        }

        .method-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }

    @media (max-width: 768px) {
        .payment-page {
            padding: 80px 12px 45px;
        }

        .payment-hero {
            padding: 30px 24px 34px;
        }

        .payment-hero-content {
            flex-direction: column;
        }

        .hero-total-card {
            width: 100%;
        }

        .payment-title {
            font-size: 28px;
        }

        .meta-strip {
            grid-template-columns: repeat(2, 1fr);
        }

        .meta-item:nth-child(2) {
            border-right: 0;
        }

        .meta-item:nth-child(1),
        .meta-item:nth-child(2) {
            border-bottom: 1px solid #e5e7eb;
        }

        .category-summary {
            flex-direction: column;
            align-items: flex-start;
        }

        .category-count {
            align-self: flex-end;
        }

        .category-title {
            font-size: 18px;
        }

        .category-desc {
            font-size: 14px;
        }

        .method-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
        }

        .method-card {
            padding: 14px;
        }
    }

    @media (max-width: 520px) {
        .method-grid {
            grid-template-columns: 1fr;
        }

        .meta-strip {
            grid-template-columns: 1fr;
        }

        .meta-item {
            border-right: 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .meta-item:last-child {
            border-bottom: 0;
        }
    }


	.alert-box {
    margin-bottom: 22px;
    border-radius: 12px;
    padding: 14px 16px;
    font-size: 13px;
    line-height: 1.5;
    font-weight: 500;
	}
	
	.alert-error {
	    background: #fee2e2;
	    border: 1px solid #fca5a5;
	    color: #991b1b;
	}
	
	.alert-success {
	    background: #dcfce7;
	    border: 1px solid #86efac;
	    color: #166534;
	}
</style>

<div class="payment-page">
    <div class="payment-container">

        {{-- HERO --}}
        <div class="payment-hero">
            <div class="payment-hero-content">
                <div class="hero-left">
                    <p class="payment-label">Rafa Kost Payment</p>

                    <h1 class="payment-title">
                        Pilih Metode<br>Pembayaran
                    </h1>

                    <span class="invoice-pill">
                        {{ $booking->invoice }}
                    </span>
                </div>

                <div class="hero-total-card">
						<div class="hero-total-label">Total Tagihan</div>
						
						<div class="hero-total-amount">
						    Rp {{ number_format($baseAmount, 0, ',', '.') }}
						</div>

                    <div class="hero-total-note">
                        Belum termasuk biaya admin.
                    </div>
                </div>
            </div>
        </div>

        {{-- META --}}
        <div class="meta-strip">
            <div class="meta-item">
                <div class="meta-key">Kamar</div>
                <div class="meta-value">{{ $booking->kamar->nama ?? '-' }}</div>
            </div>

            <div class="meta-item">
                <div class="meta-key">Durasi</div>
                <div class="meta-value">{{ $booking->durasi }} Bulan</div>
            </div>

            <div class="meta-item">
                <div class="meta-key">Orang</div>
                <div class="meta-value">{{ $booking->orang ?? 1 }} Orang</div>
            </div>

            <div class="meta-item">
                <div class="meta-key">Tanggal Masuk</div>
                <div class="meta-value">
                    {{ $booking->tanggal_masuk ? $booking->tanggal_masuk->format('d M Y') : '-' }}
                </div>
            </div>
        </div>

		<div class="payment-body">
		
		    @if(session('error'))
		        <div class="alert-box alert-error">
		            {{ session('error') }}
		        </div>
		    @endif
		
		    @if(session('success'))
		        <div class="alert-box alert-success">
		            {{ session('success') }}
		        </div>
		    @endif
		
		    @if($errors->any())
		        <div class="alert-box alert-error">
		            {{ $errors->first() }}
		        </div>
		    @endif
		
		    <div class="back-row">
		        <a href="{{ route('booking.invoice', $booking->invoice) }}" class="back-link">
		            ← Kembali ke Invoice
		        </a>
		    </div>

            {{-- PAYMENT METHODS --}}
            @forelse($groupedMethods as $category => $items)
                @php
                    $label = $categoryLabels[$category] ?? ucfirst($category ?? 'Lainnya');
                    $description = $categoryDescriptions[$category] ?? 'Pilih metode pembayaran yang tersedia.';
                @endphp

                <details class="category-block" {{ $loop->first ? 'open' : '' }}>
                    <summary class="category-summary">
                        <div class="category-heading">
                            <div class="category-icon">
                                <img src="{{ asset('images/frameworkpartikel.png') }}" alt="">
                            </div>

                            <div>
                                <div class="category-title">{{ $label }}</div>
                                <div class="category-desc">{{ $description }}</div>
                            </div>
                        </div>

                        <div class="category-count">
                            Tersedia {{ $items->count() }} metode
                            <span class="category-arrow">⌄</span>
                        </div>
                    </summary>

                    <div class="category-content">
                        <div class="method-grid">
                            @foreach($items as $method)
                               @php
								    $feePercent = round($baseAmount * ((float) $method->fee_percent / 100));
								    $fee = (int) $method->fee_fixed + (int) $feePercent;
								    $total = $baseAmount + $fee;
								@endphp
                                <form method="POST" action="{{ route('booking.methods.choose', $booking->invoice) }}" class="method-form">
                                    @csrf

                                    <input type="hidden" name="payment_method_id" value="{{ $method->id }}">

                                    <button type="submit" class="method-card">
                                        <div class="method-top">
                                            <div>
                                                <div class="method-name">{{ $method->name }}</div>

                                                <div class="method-info">
                                                    {{ $method->info ?? 'Dicek Otomatis' }}
                                                </div>

                                                <div class="method-code">
                                                    {{ strtoupper($method->code) }}
                                                </div>
                                            </div>

                                            <div class="method-logo">
                                                @if($method->logo)
                                                    <img src="{{ asset(ltrim($method->logo, '/')) }}" alt="{{ $method->name }}">
                                                @else
                                                    <span>LOGO</span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="price-box">
											<div class="price-row">
											    <span>Harga Sewa</span>
											    <strong>Rp {{ number_format($hargaSewa, 0, ',', '.') }}</strong>
											</div>
											
											@if($lateFee > 0)
											    <div class="price-row">
											        <span>
											            Denda Telat {{ $lateDays > 0 ? '(' . $lateDays . ' hari)' : '' }}
											        </span>
											        <strong style="color:#dc2626;">
											            Rp {{ number_format($lateFee, 0, ',', '.') }}
											        </strong>
											    </div>
											@endif
											
											<div class="price-row">
											    <span>Biaya Admin</span>
											    <strong style="color:{{ $fee > 0 ? '#111827' : '#059669' }};">
											        {{ $fee > 0 ? 'Rp ' . number_format($fee, 0, ',', '.') : 'Gratis' }}
											    </strong>
											</div>

                                            <div class="price-total">
                                                <span>Total Bayar</span>
                                                <strong>Rp {{ number_format($total, 0, ',', '.') }}</strong>
                                            </div>
                                        </div>

                                        <div class="choose-row">
                                            <span class="choose-btn">Pilih Metode</span>
                                        </div>
                                    </button>
                                </form>
                            @endforeach
                        </div>
                    </div>
                </details>

            @empty
                <div class="empty-box">
                    <div class="empty-title">
                        Belum Ada Metode Pembayaran Aktif
                    </div>

                    <div class="empty-desc">
                        Belum ada metode pembayaran aktif.
                        Silakan aktifkan atau tambahkan metode pembayaran dari dashboard admin.
                    </div>
                </div>
            @endforelse

            <div class="footer-note">
                <div class="footer-note-icon">i</div>
                <p>
                    Pastikan membayar sesuai nominal yang muncul setelah memilih metode pembayaran.
                    Status pembayaran akan dikonfirmasi otomatis oleh sistem. Jika callback gagal,
                    admin dapat melakukan konfirmasi manual melalui panel admin.
                </p>
            </div>

        </div>
    </div>
</div>

@endsection