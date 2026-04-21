@extends('layouts.app')

@section('main_class', 'pt-0')

@section('content')
    <div class="min-h-screen" style="background: #f5f7fa;">

        {{-- Top accent bar --}}
        <div style="height: 4px; background: linear-gradient(90deg, #1a7fe8, #2563eb, #1a7fe8);"></div>

        <div class="max-w-6xl mx-auto py-10 px-6">

            {{-- Page Header --}}
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 28px;">
                <div>
                    <h1 style="color: #1a202c; font-size: 28px; font-weight: 700; letter-spacing: -0.02em; margin: 0;">Data Kamar</h1>
                    <p style="color: #718096; font-size: 14px; margin: 4px 0 0;">Kelola semua data kamar kost</p>
                </div>
            </div>

            {{-- Success Alert --}}
            @if(session('success'))
                <div style="display: flex; align-items: center; gap: 10px; background: rgba(72,187,120,0.08); border: 1px solid rgba(72,187,120,0.35); border-radius: 10px; padding: 14px 18px; margin-bottom: 24px;">
                    <svg width="18" height="18" fill="none" stroke="#38a169" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M9 12l2 2 4-4M12 2a10 10 0 100 20A10 10 0 0012 2z"/>
                    </svg>
                    <span style="color: #38a169; font-size: 14px; font-weight: 500;">{{ session('success') }}</span>
                </div>
            @endif

            {{-- Table Card --}}
            <div style="background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.07); border: 1px solid #e2e8f0;">

                {{-- Table --}}
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: linear-gradient(135deg, #1a7fe8 0%, #1652a8 100%);">
                                <th style="padding: 16px 20px; text-align: left; color: rgba(255,255,255,0.9); font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; width: 60px;">No</th>
                                <th style="padding: 16px 20px; text-align: left; color: rgba(255,255,255,0.9); font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em;">Nama</th>
                                <th style="padding: 16px 20px; text-align: left; color: rgba(255,255,255,0.9); font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em;">Lantai</th>
                                <th style="padding: 16px 20px; text-align: left; color: rgba(255,255,255,0.9); font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em;">Harga</th>
                                <th style="padding: 16px 20px; text-align: center; color: rgba(255,255,255,0.9); font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em;">Status</th>
                                <th style="padding: 16px 20px; text-align: center; color: rgba(255,255,255,0.9); font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($kamars as $kamar)
                                <tr style="border-bottom: 1px solid #f0f4f8; transition: background 0.15s;"
                                    onmouseover="this.style.background='#f7faff'"
                                    onmouseout="this.style.background='transparent'">

                                    {{-- No --}}
                                    <td style="padding: 16px 20px; color: #a0aec0; font-size: 14px; font-weight: 600;">
                                        {{ $loop->iteration }}
                                    </td>

                                    {{-- Nama --}}
                                    <td style="padding: 16px 20px;">
                                        <div style="display: flex; align-items: center; gap: 10px;">
                                            <div style="width: 34px; height: 34px; background: rgba(26,127,232,0.1); border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                                <svg width="16" height="16" fill="none" stroke="#1a7fe8" stroke-width="1.8" viewBox="0 0 24 24">
                                                    <path d="M3 10.5V19a1 1 0 001 1h16a1 1 0 001-1v-8.5M3 10.5L12 4l9 6.5"/>
                                                </svg>
                                            </div>
                                            <span style="color: #2d3748; font-size: 15px; font-weight: 600;">{{ $kamar->nama }}</span>
                                        </div>
                                    </td>

                                    {{-- Lantai --}}
                                    <td style="padding: 16px 20px; color: #718096; font-size: 14px;">
                                        {{ $kamar->lantai }}
                                    </td>

                                    {{-- Harga --}}
                                    <td style="padding: 16px 20px; color: #2d3748; font-size: 14px; font-weight: 500;">
                                        Rp {{ is_numeric($kamar->harga) ? number_format($kamar->harga, 0, ',', '.') : $kamar->harga }}
                                    </td>

                                    {{-- Status --}}
                                    <td style="padding: 16px 20px; text-align: center;">
                                        @if($kamar->status === 'tersedia')
                                            <span style="display: inline-flex; align-items: center; gap: 6px; padding: 5px 12px; background: rgba(56,161,105,0.08); border: 1px solid rgba(56,161,105,0.3); border-radius: 20px; color: #38a169; font-size: 12px; font-weight: 600;">
                                                <span style="width: 6px; height: 6px; background: #38a169; border-radius: 50%;"></span>
                                                Tersedia
                                            </span>
                                        @else
                                            <span style="display: inline-flex; align-items: center; gap: 6px; padding: 5px 12px; background: rgba(229,62,62,0.08); border: 1px solid rgba(229,62,62,0.25); border-radius: 20px; color: #e53e3e; font-size: 12px; font-weight: 600;">
                                                <span style="width: 6px; height: 6px; background: #e53e3e; border-radius: 50%;"></span>
                                                Terisi
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Aksi --}}
                                    <td style="padding: 16px 20px; text-align: center;">
                                        <div style="display: inline-flex; align-items: center; gap: 8px;">
                                            <a href="{{ route('kamars.edit', $kamar->id) }}"
                                               style="display: inline-flex; align-items: center; gap: 5px; padding: 7px 14px; background: rgba(26,127,232,0.08); border: 1px solid rgba(26,127,232,0.25); border-radius: 8px; color: #1a7fe8; font-size: 13px; font-weight: 600; text-decoration: none; transition: all 0.2s;"
                                               onmouseover="this.style.background='rgba(26,127,232,0.16)'" onmouseout="this.style.background='rgba(26,127,232,0.08)'">
                                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                                </svg>
                                                Edit
                                            </a>

                                            <form action="{{ route('kamars.destroy', $kamar->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        onclick="return confirm('Yakin ingin menghapus kamar ini?')"
                                                        style="display: inline-flex; align-items: center; gap: 5px; padding: 7px 14px; background: rgba(229,62,62,0.08); border: 1px solid rgba(229,62,62,0.25); border-radius: 8px; color: #e53e3e; font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.2s;"
                                                        onmouseover="this.style.background='rgba(229,62,62,0.16)'" onmouseout="this.style.background='rgba(229,62,62,0.08)'">
                                                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                        <path d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6"/>
                                                    </svg>
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach

                            {{-- Empty State --}}
                            @if($kamars->isEmpty())
                                <tr>
                                    <td colspan="6" style="padding: 60px 20px; text-align: center;">
                                        <svg width="48" height="48" fill="none" stroke="#cbd5e0" stroke-width="1.5" viewBox="0 0 24 24" style="margin: 0 auto 14px;">
                                            <path d="M3 10.5V19a1 1 0 001 1h16a1 1 0 001-1v-8.5M3 10.5L12 4l9 6.5M3 10.5h18"/>
                                        </svg>
                                        <p style="color: #4a5568; font-size: 15px; font-weight: 600; margin: 0;">Belum ada data kamar</p>
                                        <p style="color: #a0aec0; font-size: 13px; margin: 6px 0 0;">Klik tombol "Tambah Kamar" untuk memulai</p>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                {{-- Table Footer --}}
                <div style="padding: 14px 20px; border-top: 1px solid #e2e8f0; background: #f8fafc; display: flex; align-items: center; justify-content: space-between;">
                    <span style="color: #718096; font-size: 13px;">
                        Total: <strong style="color: #2d3748;">{{ $kamars->count() }} kamar</strong>
                    </span>
                    <div style="display: flex; gap: 16px;">
                        <span style="display: inline-flex; align-items: center; gap: 5px; color: #718096; font-size: 12px;">
                            <span style="width: 6px; height: 6px; background: #38a169; border-radius: 50%;"></span>
                            Tersedia: {{ $kamars->where('status','tersedia')->count() }}
                        </span>
                        <span style="display: inline-flex; align-items: center; gap: 5px; color: #718096; font-size: 12px;">
                            <span style="width: 6px; height: 6px; background: #e53e3e; border-radius: 50%;"></span>
                            Terisi: {{ $kamars->where('status','terisi')->count() }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection