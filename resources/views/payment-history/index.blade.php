
@extends('layouts.app')

@section('content')

@include('layouts.navigation-user')

<style>
    /* Tema Light Mode Premium & Elegan (Compact Version) */
    :root {
        --bg-main: #f8fafc;        
        --bg-surface: #ffffff;     
        --bg-surface-alt: #fcfcfc; 
        --bg-hover: #f1f5f9;       
        --border-color: #e2e8f0;   
        --text-main: #0f172a;      
        --text-muted: #64748b;     
        --accent-color: #2563eb;   
        --accent-hover: #1e40af;
        --danger-color: #ef4444;
        --danger-hover: #b91c1c;
    }

    .history-page {
        min-height: 100vh;
        background: var(--bg-main);
        padding: 100px 20px 60px;
        font-family: 'Inter', 'Plus Jakarta Sans', sans-serif;
    }

    .history-wrapper {
        max-width: 1000px;
        margin: 0 auto;
    }

    /* Header Section */
    .history-header {
        margin-bottom: 20px;
    }

    .history-title {
        font-size: 22px;
        font-weight: 800;
        color: var(--text-main);
        margin-bottom: 6px;
        letter-spacing: -0.5px;
    }

    .history-subtitle {
        font-size: 13px;
        color: var(--text-muted);
    }

    /* Alerts */
    .alert-box {
        padding: 12px 16px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-size: 13px;
        font-weight: 600;
    }
    .alert-success { background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; }
    .alert-error { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }

    /* Container Tabel */
    .table-container {
        background: var(--bg-surface);
        border: 1px solid var(--border-color);
        border-radius: 10px;
        overflow-x: auto;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.03);
    }

    /* Custom Scrollbar */
    .table-container::-webkit-scrollbar { height: 6px; }
    .table-container::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 10px; }
    .table-container::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    .table-container::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

    /* Desain Tabel Utama (Compact) */
    .main-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 800px; 
    }

    .main-table th {
        background: var(--bg-surface);
        padding: 12px 16px; 
        text-align: left;
        font-size: 11px; 
        color: var(--text-muted);
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid var(--border-color);
    }

    .main-table td {
        padding: 12px 16px; 
        border-bottom: 1px solid var(--border-color);
        vertical-align: middle;
        font-size: 13px;
        color: var(--text-main);
    }

    .main-table tr:nth-child(even) td { background-color: var(--bg-surface-alt); }
    .main-table tr:hover td { background-color: var(--bg-hover); }
    .main-table tr:last-child td { border-bottom: none; }

    /* Tipografi Spesifik Tabel */
    .invoice-link {
        color: var(--accent-color);
        font-weight: 700;
        font-size: 13px;
        display: inline-block;
        font-family: ui-monospace, SFMono-Regular, monospace;
        text-decoration: none;
        transition: 0.2s;
    }
    .invoice-link:hover {
        color: var(--accent-hover);
        text-decoration: underline;
    }
    
    .invoice-disabled {
        color: var(--text-muted);
        font-weight: 600;
        font-size: 13px;
        font-family: ui-monospace, SFMono-Regular, monospace;
    }

    .room-name {
        font-weight: 700;
        font-size: 13px;
    }

    .info-pill {
        display: inline-flex;
        align-items: center;
        background: #f8fafc;
        border: 1px solid var(--border-color);
        padding: 4px 8px; 
        border-radius: 6px;
        color: var(--text-muted);
        font-size: 11px;
        font-weight: 600;
        margin-right: 6px;
        margin-bottom: 2px;
    }

    .price-text {
        font-weight: 700;
        color: var(--text-main);
        font-size: 13px;
    }

    /* Status Badges */
    .status-badge {
        display: inline-flex;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.3px;
    }
    .status-paid { color: #166534; background: #dcfce7; }
    .status-pending { color: #b45309; background: #fef3c7; }
    .status-canceled { color: #991b1b; background: #fee2e2; }
    .status-other { color: var(--text-muted); background: #f1f5f9; }

    /* Teks Batal (Minimalist) */
    .btn-cancel-text {
        background: transparent;
        border: none;
        color: var(--danger-color);
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        padding: 0;
        transition: 0.2s;
    }
    .btn-cancel-text:hover {
        color: var(--danger-hover);
        text-decoration: underline;
    }

    .empty-state {
        text-align: center;
        color: var(--text-muted);
        padding: 40px !important;
        font-size: 13px;
    }
</style>

<div class="history-page">
    <div class="history-wrapper">

        <div class="history-header">
            <h1 class="history-title">Riwayat Pembayaran</h1>
            <p class="history-subtitle">
                Klik nomor invoice untuk melihat detail atau melanjutkan pembayaran.
            </p>
        </div>

        @if(session('success'))
            <div class="alert-box alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert-box alert-error">
                {{ session('error') }}
            </div>
        @endif

        <div class="table-container">
            <table class="main-table">
                <thead>
                    <tr>
                        <th>Invoice</th>
                        <th>Item (Kamar)</th>
                        <th>Keterangan</th>
                        <th>Harga</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $booking)
                        @php
                            $status = $booking->payment_status ?? 'pending';

                            $statusClass = match($status) {
                                'paid' => 'status-paid',
                                'pending' => 'status-pending',
                                'canceled' => 'status-canceled',
                                default => 'status-other',
                            };

                            $statusText = match($status) {
                                'paid' => 'Lunas',
                                'pending' => 'Pending',
                                'canceled' => 'Batal',
                                default => ucfirst($status),
                            };
                        @endphp

                        <tr>
                            <td>
                                @if(!empty($booking->invoice))
                                    @if($status === 'paid')
                                        <a href="{{ route('booking.invoice', $booking->invoice) }}" class="invoice-link" title="Lihat Struk Invoice">
                                            {{ $booking->invoice }}
                                        </a>
                                    @elseif($status === 'pending')
                                        <a href="{{ route('booking.methods', $booking->invoice) }}" class="invoice-link" title="Lanjut Bayar">
                                            {{ $booking->invoice }}
                                        </a>
                                    @else
                                        <span class="invoice-disabled">
                                            {{ $booking->invoice }}
                                        </span>
                                    @endif
                                @else
                                    <span class="invoice-disabled">N/A</span>
                                @endif
                            </td>

                            <td>
                                <div class="room-name">{{ $booking->kamar->nama ?? 'Nama Kamar' }}</div>
                            </td>

                            <td>
                                <div style="display: flex; flex-wrap: wrap;">
                                    <span class="info-pill">{{ $booking->tanggal_masuk ?? '-' }}</span>
                                    <span class="info-pill">{{ $booking->durasi ?? '-' }} Bln</span>
                                </div>
                            </td>

                            <td>
                                <div class="price-text">
                                    Rp {{ number_format($booking->payment_total ?? $booking->total_harga ?? 0, 0, ',', '.') }}
                                </div>
                            </td>

                            <td>
                                <span class="status-badge {{ $statusClass }}">
                                    {{ $statusText }}
                                </span>
                            </td>

                            <td style="text-align: right;">
                                @if($status === 'pending')
                                    <form method="POST" action="{{ route('payment-history.cancel', $booking) }}" onsubmit="return confirm('Batalkan invoice ini?');" style="margin: 0;">
                                        @csrf
                                        <button type="submit" class="btn-cancel-text">
                                            Batalkan
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="6" class="empty-state">
                                Tidak ada data riwayat pembayaran.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>

@include('layouts.footer')

@endsection