@extends('layouts.app')

@section('content')
<div style="min-height: 100vh; background-color: #f8fafc; padding: 40px 20px; font-family: 'DM Sans', ui-sans-serif, system-ui, -apple-system, sans-serif;">
    <div style="max-width: 1000px; margin: 0 auto;">

        {{-- Bagian Header (Di luar kotak) --}}
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px;">
            <div>
                <h1 style="font-size: 24px; font-weight: 900; color: #0f172a; margin: 0 0 6px 0;">
                    Edit Masa Sewa
                </h1>
                <p style="font-size: 14px; color: #64748b; margin: 0;">
                    Ubah tanggal berakhir masa sewa untuk penyewa ini.
                </p>
            </div>
            
            <a href="{{ route('admin.tenants.index') }}" 
               style="display: inline-flex; align-items: center; gap: 6px; background: #ffffff; border: 1px solid #e2e8f0; color: #475569; padding: 8px 16px; border-radius: 8px; text-decoration: none; font-size: 13px; font-weight: 700; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Kembali
            </a>
        </div>

        {{-- Notifikasi --}}
        @if(session('success'))
            <div style="background: #dcfce7; border: 1px solid #bbf7d0; color: #166534; padding: 14px 18px; border-radius: 12px; margin-bottom: 24px; font-size: 14px; font-weight: 600;">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error') || $errors->any())
            <div style="background: #fee2e2; border: 1px solid #fecaca; color: #991b1b; padding: 14px 18px; border-radius: 12px; margin-bottom: 24px; font-size: 14px; font-weight: 600;">
                {{ session('error') ?? $errors->first() }}
            </div>
        @endif

        {{-- Kotak Utama (Card Putih) --}}
        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 32px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);">

            {{-- Kotak Abu-abu untuk Data Read-Only --}}
            <div style="background: #f8fafc; border: 1px solid #f1f5f9; border-radius: 12px; padding: 24px; margin-bottom: 32px;">
                
                {{-- Bagian Invoice --}}
                <div style="margin-bottom: 24px;">
                    <div style="font-size: 11px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 6px;">
                        Invoice
                    </div>
                    <div style="font-size: 18px; font-weight: 900; color: #0f172a; display: flex; align-items: center; gap: 8px;">
                        <svg width="20" height="20" fill="none" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        {{ $booking->invoice }}
                    </div>
                </div>

                {{-- Grid 4 Kolom untuk Detail --}}
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 24px;">
                    <div>
                        <div style="font-size: 11px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 6px;">Nama Penyewa</div>
                        <div style="font-size: 14px; font-weight: 800; color: #0f172a;">{{ $booking->customer_name }}</div>
                    </div>

                    <div>
                        <div style="font-size: 11px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 6px;">Kamar</div>
                        <div style="font-size: 14px; font-weight: 800; color: #0f172a;">{{ $booking->kamar->nama ?? '-' }}</div>
                    </div>

                    <div>
                        <div style="font-size: 11px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 6px;">Tanggal Masuk</div>
                        <div style="font-size: 14px; font-weight: 800; color: #0f172a;">
                            {{ $booking->tanggal_masuk ? \Carbon\Carbon::parse($booking->tanggal_masuk)->format('d M Y') : '-' }}
                        </div>
                    </div>

                    <div>
                        <div style="font-size: 11px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 6px;">Tgl Habis Saat Ini</div>
                        <div style="font-size: 14px; font-weight: 800; color: #0f172a;">
                            {{ $tanggalHabisDefault ? $tanggalHabisDefault->format('d M Y') : '-' }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Form Tanggal Baru --}}
            <form method="POST" action="{{ route('admin.tenants.update', $booking) }}">
                @csrf
                @method('PUT')

                <div style="margin-bottom: 32px;">
                    <label style="display: block; font-size: 14px; font-weight: 900; color: #0f172a; margin-bottom: 12px;">
                        Atur Tanggal Habis Baru <span style="color: #ef4444;">*</span>
                    </label>

                    <input 
                        type="date" 
                        name="tanggal_habis_custom" 
                        value="{{ old('tanggal_habis_custom', $tanggalHabisDefault ? $tanggalHabisDefault->format('Y-m-d') : '') }}"
                        style="width: 100%; border: 1px solid #cbd5e1; border-radius: 8px; padding: 14px 16px; font-size: 14px; color: #0f172a; box-sizing: border-box; outline: none; font-family: inherit;"
                        required
                    >
                    <p style="font-size: 12px; color: #64748b; margin-top: 8px; margin-bottom: 0;">
                        Pastikan tanggal baru yang dimasukkan sudah sesuai kesepakatan dengan penyewa.
                    </p>
                </div>

                {{-- Garis Pemisah & Tombol Simpan --}}
                <div style="border-top: 1px solid #f1f5f9; padding-top: 24px; display: flex; justify-content: flex-end;">
                    <button type="submit" 
                            style="display: inline-flex; align-items: center; gap: 8px; background: #0ea5e9; color: #ffffff; border: none; border-radius: 8px; padding: 12px 24px; font-size: 14px; font-weight: 800; cursor: pointer; box-shadow: 0 2px 4px rgba(14, 165, 233, 0.2);">
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        Simpan Perubahan
                    </button>
                </div>

            </form>
        </div>
        
    </div>
</div>
@endsection