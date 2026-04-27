@extends('layouts.app')

@section('main_class', 'pt-0')

@section('content')
    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        html, body {
            background-color: #ffffff !important;
            font-family: 'DM Sans', sans-serif;
            margin: 0;
        }

        .adm-wrapper {
            background-color: #ffffff;
            min-height: 100vh;
            padding: 32px 20px;
        }

        .adm-container {
            max-width: 1140px;
            margin: 0 auto;
            width: 100%;
        }

        .section-logo {
            width: 22px;
            height: 22px;
            object-fit: contain;
        }

        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }

        .title-wrapper {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .page-title {
            font-size: 16px;
            font-weight: 700;
            color: #000000;
            margin: 0;
        }

        .btn-add {
            background-color: #0EA5E9;
            color: #ffffff;
            padding: 10px 20px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: opacity 0.2s;
        }

        .btn-add:hover { opacity: 0.85; }

        /* Style Table */
        .table-container {
            border: 1px solid #E2E8F0;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.03);
            background: #ffffff;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        thead {
            background-color: #000000;
            color: #ffffff;
        }

        th {
            padding: 14px 20px;
            font-size: 12px;
            font-weight: 600;
            white-space: nowrap;
        }

        td {
            padding: 14px 20px;
            font-size: 13px;
            color: #1E293B;
            border-bottom: 1px solid #F1F5F9;
        }

        tbody tr:hover { background-color: #F8FAFC; }

        .kamar-name {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
            color: #000000;
        }

        /* Badges */
        .badge {
            padding: 4px 12px;
            border-radius: 100px;
            font-size: 11px;
            font-weight: 600;
            display: inline-block;
            text-transform: capitalize;
        }
        .badge-green { background: #DCFCE7; color: #16A34A; }
        .badge-red { background: #FEE2E2; color: #DC2626; }

        /* Action Buttons in Table */
        .action-btns {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-sm-edit {
            background-color: #E0F2FE;
            color: #0284C7;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .btn-sm-delete {
            background-color: #FEE2E2;
            color: #DC2626;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .table-footer {
            padding: 14px 20px;
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            color: #475569;
            font-weight: 500;
            background: #ffffff;
        }
    </style>

    <div class="adm-wrapper">
        <div class="adm-container">

            {{-- Back link --}}
            <a href="{{ route('dashboard') }}" 
               style="color: #000000; font-size: 13px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; margin-bottom: 24px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="15 18 9 12 15 6"></polyline>
                </svg>
                Kembali
            </a>

            {{-- Header (Title & Button) --}}
            <div class="page-header">
                <div class="title-wrapper">
                    <img src="{{ asset('images/frameworkpartikel.png') }}" alt="icon" class="section-logo">
                    <h1 class="page-title">Data Kamar</h1>
                </div>
                <a href="{{ route('kamars.create') }}" class="btn-add">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
                    Tambah Kamar
                </a>
            </div>

            {{-- Success Alert (Logika Asli) --}}
            @if(session('success'))
                <div style="background: #DCFCE7; color: #16A34A; padding: 12px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 6L9 17l-5-5"/></svg>
                    {{ session('success') }}
                </div>
            @endif

            {{-- Table Wrapper --}}
            <div class="table-container">
                <div style="overflow-x: auto;">
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th>Nama</th>
                                <th>Lantai</th>
                                <th>Harga</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kamars as $kamar)
                                <tr>
                                    <td style="font-weight: 600;">{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="kamar-name">
                                            {{-- IKON KOTAK SESUAI GAMBAR TERAKHIR --}}
                                            <svg width="18" height="18" fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                                <rect x="5" y="3" width="14" height="18" rx="2" ry="2"></rect>
                                                <line x1="9" y1="11" x2="12" y2="8"></line>
                                                <line x1="9" y1="16" x2="15" y2="10"></line>
                                            </svg>
                                            {{ $kamar->nama }}
                                        </div>
                                    </td>
                                    <td style="color: #64748B;">{{ $kamar->lantai }}</td>
                                    <td style="font-weight: 600;">Rp {{ is_numeric($kamar->harga) ? number_format($kamar->harga, 0, ',', '.') : $kamar->harga }}</td>
                                    <td>
                                        <span class="badge {{ $kamar->status == 'tersedia' ? 'badge-green' : 'badge-red' }}">
                                            • {{ ucfirst($kamar->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-btns">
                                            <a href="{{ route('kamars.edit', $kamar->id) }}" class="btn-sm-edit">
                                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg> Edit
                                            </a>
                                            <form action="{{ route('kamars.destroy', $kamar->id) }}" method="POST" style="margin: 0;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-sm-delete" onclick="return confirm('Yakin ingin menghapus kamar ini?')">
                                                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6"/></svg> Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" style="text-align: center; padding: 40px; color: #94A3B8;">
                                        Belum ada data kamar yang didaftarkan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Table Footer (Total Kamar) --}}
                <div class="table-footer">
                    <div>Total: <span style="font-weight: 700; color: #1E293B;">{{ $kamars->count() }} kamar</span></div>
                    <div style="display: flex; gap: 16px;">
                        <span style="color: #16A34A;">• Tersedia: <span style="font-weight: 700;">{{ $kamars->where('status','tersedia')->count() }}</span></span>
                        <span style="color: #DC2626;">• Terisi: <span style="font-weight: 700;">{{ $kamars->where('status','terisi')->count() }}</span></span>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection