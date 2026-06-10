@extends('layouts.app')

@section('main_class', 'pt-0')

@section('content')
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        html, body {
            background-color: #ffffff !important;
            font-family: 'DM Sans', sans-serif;
        }

        .page-wrapper { background-color: #ffffff; min-height: 100vh; padding: 40px 20px; }
        .content-container { max-width: 1140px; margin: 0 auto; width: 100%; }

        /* Header Tabel Hitam (Sesuai Referensi) */
        .table-container {
            border-radius: 12px; overflow: hidden; border: 1px solid #E2E8F0;
        }
        table { width: 100%; border-collapse: collapse; }
        thead { background-color: #000000; color: #ffffff; }
        th { padding: 14px 20px; text-align: left; font-size: 12px; font-weight: 600; }
        td { padding: 14px 20px; font-size: 13px; color: #1E293B; border-bottom: 1px solid #F1F5F9; }
        
        /* Badges & Buttons */
        .badge-status {
            padding: 4px 12px; border-radius: 100px; font-size: 11px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px;
        }
        .status-dot { width: 6px; height: 6px; border-radius: 50%; }
        
        /* Aksi Buttons (Pill style seperti di gambar) */
        .btn-aksi {
            padding: 6px 14px; border-radius: 100px; font-size: 11px; font-weight: 600; text-decoration: none; 
            display: inline-flex; align-items: center; gap: 4px; cursor: pointer; border: none;
        }
        .btn-edit { background: #E0F2FE; color: #0284C7; }
        .btn-hapus { background: #FEE2E2; color: #DC2626; }
    </style>

    <div class="page-wrapper">
        <div class="content-container">

            {{-- Back link menuju Dashboard Admin --}}
            <a href="{{ url('/admin/dashboard') }}" 
               style="color: #000000; font-size: 13px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; margin-bottom: 24px; transition: color 0.2s;"
               onmouseover="this.style.color='#0EA5E9'" 
               onmouseout="this.style.color='#000000'">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
                Kembali
            </a>

            {{-- Page Header --}}
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px;">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <img src="{{ asset('images/frameworkpartikel.png') }}" alt="icon" style="width: 20px; height: 20px; object-fit: contain;">
                    <h1 style="font-size: 16px; font-weight: 700; color: #000000; margin: 0;">Data Kamar</h1>
                </div>
                <a href="{{ route('kamars.create') }}" style="background: #0EA5E9; color: white; padding: 8px 16px; border-radius: 6px; font-size: 12px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>
                    Tambah Kamar
                </a>
            </div>

            {{-- Success Alert --}}
            @if(session('success'))
                <div style="background: #DCFCE7; color: #16A34A; padding: 12px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; margin-bottom: 20px;">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Table Container --}}
            <div class="table-container">
                <div style="overflow-x: auto;">
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th>Nama</th>
                                <th>Lantai</th>
								<th>Kamar Mandi</th>
                                <th>Harga</th>
                                <th style="text-align: center;">Status</th>
                                <th style="text-align: center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kamars as $kamar)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <div style="display: flex; align-items: center; gap: 10px; font-weight: 600;">
                                            {{-- IKON BARU SESUAI GAMBAR KE-2 --}}
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                <rect x="6" y="3" width="12" height="18" rx="2" ry="2"></rect>
                                                <line x1="14" y1="8" x2="10" y2="16"></line>
                                            </svg>
                                            {{ $kamar->nama }}
                                        </div>
                                    </td>
                                    <td style="color: #64748B;">{{ $kamar->lantai }}</td>
									<td style="color: #64748B;">{{ $kamar->kamar_mandi ?? '-' }}</td>
                                    <td style="font-weight: 600;">Rp {{ is_numeric($kamar->harga) ? number_format($kamar->harga, 0, ',', '.') : $kamar->harga }}</td>
                                    
                                    {{-- Status --}}
                                    <td style="text-align: center;">
                                        @if($kamar->status === 'tersedia')
                                            <span class="badge-status" style="background: #F8FAFC; color: #64748B; border: 1px solid #E2E8F0;">
                                                <span class="status-dot" style="background: #10B981;"></span> Tersedia
                                            </span>
                                        @else
                                            <span class="badge-status" style="background: #FEE2E2; color: #DC2626;">
                                                <span class="status-dot" style="background: #DC2626;"></span> Terisi
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Aksi --}}
                                    <td style="text-align: center;">
                                        <div style="display: inline-flex; gap: 8px;">
                                            <a href="{{ route('kamars.edit', $kamar->id) }}" class="btn-aksi btn-edit">
                                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                                Edit
                                            </a>
                                            <form action="{{ route('kamars.destroy', $kamar->id) }}" method="POST" style="margin: 0;">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn-aksi btn-hapus" onclick="return confirm('Yakin ingin menghapus kamar ini?')">
                                                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6"/></svg>
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" style="text-align: center; padding: 40px; color: #64748B;">Belum ada data kamar.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    
                    {{-- Table Footer --}}
                    <div style="padding: 12px 20px; display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #E2E8F0; font-size: 12px; font-weight: 600;">
                        <span style="color: #000000;">Total: {{ $kamars->count() }} kamar</span>
                        <div style="display: flex; gap: 16px;">
                            <span style="display: flex; align-items: center; gap: 4px; color: #000000;"><span class="status-dot" style="background: #10B981;"></span> Tersedia: {{ $kamars->where('status','tersedia')->count() }}</span>
                            <span style="display: flex; align-items: center; gap: 4px; color: #000000;"><span class="status-dot" style="background: #DC2626;"></span> Terisi: {{ $kamars->where('status','terisi')->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection