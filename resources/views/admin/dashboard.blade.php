@extends('layouts.app')
@section('content')

    @php
        $hunianPersen = $totalKamar > 0 ? round(($kamarTerisi / $totalKamar) * 100) : 0;
    @endphp

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  

    <div style="background:#ffffff; min-height:100vh; padding: 32px 0;">
        <div style="max-width:1280px; margin:0 auto; padding:0 24px;">

            {{-- Hero --}}
            <div style="background-color:#334155; background-image:url('{{ asset('images/frame.png') }}'); background-size:cover; background-position:center; border-radius:12px; padding:40px 48px; color:white; min-height:180px; display:flex; flex-direction:column; justify-content:center; margin-bottom:32px; box-shadow:0px 14px 35px rgba(0,0,0,0.09);">
                <h1 style="font-size:32px; font-weight:800; margin:0 0 8px; line-height:1.2; letter-spacing:-0.5px;">
                    Selamat Datang,<br>{{ auth()->user()->name }}
                </h1>
                <p style="font-size:14px; font-weight:500; color:#f8fafc; margin:0 0 24px;">
                    Kelola kamar, penyewa, dan laporan keuangan Rafa Kost dari satu tempat.
                </p>
                <div style="display:inline-flex; align-items:center; gap:8px; background:#0EA5E9; border-radius:100px; padding:8px 20px; font-size:13px; font-weight:600; width:fit-content; box-shadow:0 4px 12px rgba(14,165,233,0.3);">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                    Sistem Aktif - {{ now()->translatedFormat('l, d F Y') }}
                </div>
            </div>

            {{-- Section: Informasi Kost --}}
            <div style="display:flex; align-items:center; gap:8px; font-size:15px; font-weight:700; color:#1E293B; margin-bottom:16px; margin-top:32px;">
                <img src="{{ asset('images/frameworkpartikel.png') }}" style="width:20px; height:20px; opacity:0.9;" alt="">
                Informasi Kost
            </div>
            <div class="rk-grid-4">

                <div class="rk-card" style="padding:24px 16px; text-align:center; display:flex; flex-direction:column; align-items:center;">
                    <svg style="width:24px;height:24px;margin-bottom:12px;stroke-width:2;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="14" width="18" height="4" rx="2"></rect><path d="M5 14v-4a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v4"></path><path d="M3 18v2"></path><path d="M21 18v2"></path><path d="M8 10V7a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v3"></path></svg>
                    <div style="font-size:13px;font-weight:600;color:#1E293B;margin-bottom:8px;">Total Kamar</div>
                    <div style="font-size:28px;font-weight:800;color:#000;line-height:1;margin-bottom:8px;">{{ $totalKamar }}</div>
                    <div style="font-size:10px;color:#94A3B8;font-weight:500;">Semua kamar terdaftar</div>
                </div>

                <div class="rk-card" style="padding:24px 16px; text-align:center; display:flex; flex-direction:column; align-items:center;">
                    <svg style="width:24px;height:24px;margin-bottom:12px;stroke-width:2;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><path d="M9 15l2 2 4-4"></path></svg>
                    <div style="font-size:13px;font-weight:600;color:#1E293B;margin-bottom:8px;">Kamar Terisi</div>
                    <div style="font-size:28px;font-weight:800;color:#000;line-height:1;margin-bottom:8px;">{{ $kamarTerisi }}</div>
                    <div style="font-size:10px;color:#94A3B8;font-weight:500;">{{ $hunianPersen }}% Tingkat hunian</div>
                </div>

                <div class="rk-card" style="padding:24px 16px; text-align:center; display:flex; flex-direction:column; align-items:center;">
                    <svg style="width:24px;height:24px;margin-bottom:12px;stroke-width:2;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="6"></circle><line x1="21" y1="21" x2="15.5" y2="15.5"></line><polyline points="9 11 10.5 12.5 13 9.5"></polyline></svg>
                    <div style="font-size:13px;font-weight:600;color:#1E293B;margin-bottom:8px;">Kamar Kosong</div>
                    <div style="font-size:28px;font-weight:800;color:#000;line-height:1;margin-bottom:8px;">{{ $kamarTersedia }}</div>
                    <div style="font-size:10px;color:#94A3B8;font-weight:500;">Siap disewakan</div>
                </div>

                <div class="rk-card" style="padding:24px 16px; text-align:center; display:flex; flex-direction:column; align-items:center;">
                    <svg style="width:24px;height:24px;margin-bottom:12px;stroke-width:2;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"></circle><path d="M12 7v10"></path><path d="M14.5 9a2.5 2.5 0 0 0-5 0c0 3 5 2 5 5a2.5 2.5 0 0 1-5 0"></path></svg>
                    <div style="font-size:13px;font-weight:600;color:#1E293B;margin-bottom:8px;">Total Nilai Kamar</div>
                    <div style="font-size:22px;font-weight:800;color:#000;line-height:1;margin-bottom:8px;">Rp {{ number_format((int) $totalNilaiKamar, 0, ',', '.') }}</div>
                    <div style="font-size:10px;color:#94A3B8;font-weight:500;">Akumulasi harga semua kamar</div>
                </div>

            </div>

            {{-- Section: Aksi Cepat --}}
            <div style="display:flex; align-items:center; gap:8px; font-size:15px; font-weight:700; color:#1E293B; margin-bottom:16px; margin-top:32px;">
                <img src="{{ asset('images/frameworkpartikel.png') }}" style="width:20px; height:20px; opacity:0.9;" alt="">
                Aksi Cepat
            </div>
            <div class="rk-grid-4">

                @php
                $actions = [
                    ['route' => route('admin.bookings.index'), 'title' => 'Status Order', 'desc' => 'Lihat status booking dan konfirmasi pembayaran manual.', 'svg' => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline>'],
                    ['route' => route('kamars.index'), 'title' => 'Data Kamar', 'desc' => 'Lihat, edit, dan kelola seluruh unit kamar kost.', 'svg' => '<path d="M2 4v16"></path><path d="M2 8h18a2 2 0 0 1 2 2v10"></path><path d="M2 17h20"></path><path d="M6 8v9"></path>'],
                    ['route' => route('kamars.create'), 'title' => 'Tambah Kamar', 'desc' => 'Daftarkan unit kamar baru ke sistem.', 'svg' => '<rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line>'],
                    ['route' => route('admin.tenants.index'), 'title' => 'Data Penyewa', 'desc' => 'Lihat penyewa aktif, tanggal masuk, tanggal habis, dan sisa masa sewa.', 'svg' => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><circle cx="12" cy="13" r="2"></circle><path d="M15 19c0-1.66-1.34-3-3-3s-3 1.34-3 3"></path>'],
                    ['route' => route('admin.payment-gateways.index'), 'title' => 'Payment Gateway', 'desc' => 'ON/OFF gateway utama Cashify atau TokoPay.', 'svg' => '<rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line>'],
                    ['route' => route('admin.payment-methods.index'), 'title' => 'Metode Pembayaran', 'desc' => 'Tambah metode, upload logo, dan aktifkan DANA, QRIS, OVO, BNI.', 'svg' => '<rect x="2" y="6" width="20" height="12" rx="2" ry="2"></rect><circle cx="12" cy="12" r="2"></circle><path d="M6 12h.01M18 12h.01"></path>'],
                    ['route' => route('admin.identity-verifications.index'), 'title' => 'Verifikasi Dokumen', 'desc' => 'Approve atau reject verifikasi identitas penyewa.', 'svg' => '<path d="M9 12l2 2 4-4"></path><path d="M21 12c.552 0 1-.448 1-1V5c0-.552-.448-1-1-1H3c-.552 0-1 .448-1 1v6c0 .552.448 1 1 1"></path><path d="M3 12v7c0 .552.448 1 1 1h16c.552 0 1-.448 1-1v-7"></path>'],
                    ['route' => route('admin.notification-settings.index'), 'title' => 'Notifikasi', 'desc' => 'Atur Fonnte, email, denda telat bayar, dan reminder sewa.', 'svg' => '<path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 7h18s-3 0-3-7"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path><path d="M21 3l-6 6"></path><path d="M15 3h6v6"></path>'],
                    ['route' => route('profile.edit'), 'title' => 'Pengaturan', 'desc' => 'Konfigurasi profil.', 'svg' => '<circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>'],
                ];
                @endphp

                @foreach($actions as $action)
                <a href="{{ $action['route'] }}" class="rk-card rk-action-card" style="padding:32px 20px; text-align:center; text-decoration:none; display:flex; flex-direction:column; align-items:center; color:#1E293B;">
                    <svg style="width:24px;height:24px;margin-bottom:16px;stroke-width:2;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">{!! $action['svg'] !!}</svg>
                    <h3 style="font-size:14px;font-weight:700;margin:0 0 8px;">{{ $action['title'] }}</h3>
                    <p style="font-size:11px;color:#94A3B8;line-height:1.4;margin:0;">{{ $action['desc'] }}</p>
                </a>
                @endforeach

            </div>

            {{-- Bottom Section --}}
            <div class="rk-grid-bottom" style="margin-top:32px; align-items:flex-start;">

                {{-- Aktivitas Terbaru --}}
                <div class="rk-card" style="padding:24px;">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
                        <h3 style="font-size:15px;font-weight:700;color:#1E293B;display:flex;align-items:center;gap:8px;margin:0;">
                            <img src="{{ asset('images/frameworkpartikel.png') }}" style="width:18px;height:18px;object-fit:contain;" alt="">
                            Aktivitas Terbaru
                        </h3>
                        <a href="{{ route('kamars.index') }}" style="font-size:13px;color:#0EA5E9;text-decoration:none;font-weight:500;display:flex;align-items:center;gap:4px;">
                            Lihat Semuanya
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                        </a>
                    </div>
                    @forelse($kamarsTerbaru as $kamar)
                        <div class="rk-activity-item">
                            <div style="display:flex;align-items:center;gap:16px;">
                                <div style="width:8px;height:8px;border:1.5px solid #94A3B8;border-radius:50%;flex-shrink:0;"></div>
                                <div style="font-size:13px;color:#1E293B;font-weight:500;">
                                    <strong>{{ $kamar->nama }}</strong> — Lantai {{ $kamar->lantai }} (Rp {{ number_format((int) $kamar->harga, 0, ',', '.') }})
                                </div>
                            </div>
                            <span style="font-size:10px;font-weight:600;padding:4px 16px;border-radius:100px;text-transform:capitalize;min-width:60px;text-align:center;
                                background:{{ $kamar->status == 'terisi' ? '#DCFCE7' : '#FEF3C7' }};
                                color:{{ $kamar->status == 'terisi' ? '#16A34A' : '#D97706' }};">
                                {{ ucfirst($kamar->status) }}
                            </span>
                        </div>
                    @empty
                        <div style="padding:12px 16px;text-align:center;">
                            <span style="font-size:13px;color:#94A3B8;">Belum ada data kamar. Silakan tambahkan data.</span>
                        </div>
                    @endforelse
                </div>

                {{-- Ringkasan Hunian --}}
                <div class="rk-card" style="padding:24px;">
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;">
                        <h3 style="font-size:15px;font-weight:700;color:#1E293B;display:flex;align-items:center;gap:8px;margin:0;">
                            <img src="{{ asset('images/frameworkpartikel.png') }}" style="width:18px;height:18px;object-fit:contain;" alt="">
                            Ringkasan Hunian
                        </h3>
                    </div>
                    <div style="font-size:36px;font-weight:700;color:#1E293B;margin-bottom:20px;display:flex;align-items:baseline;gap:4px;">
                        {{ $hunianPersen }}<span style="font-size:16px;font-weight:600;">%</span>
                    </div>
                    <div style="background:#F1F5F9;height:8px;border-radius:10px;width:100%;overflow:hidden;margin-bottom:12px;">
                        <div style="background:#0EA5E9;height:100%;border-radius:10px;width:{{ $hunianPersen }}%;transition:width 0.5s ease-in-out;"></div>
                    </div>
                    <div style="font-size:11px;color:#94A3B8;font-weight:500;">
                        {{ $kamarTerisi }} dari {{ $totalKamar }} kamar sudah terisi
                    </div>
                </div>

            </div>

        </div>
    </div>

  <style>
        body, html { font-family: 'DM Sans', sans-serif; }

        /* Card hover & shadow */
        .rk-card {
            background: #ffffff;
            border-radius: 12px;
            border: 1px solid #f0f0f0;
            box-shadow: 0px 14px 35px rgba(0,0,0,0.09);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        .rk-card:hover {
            transform: translateY(-3px);
            box-shadow: 0px 20px 45px rgba(0,0,0,0.14);
        }

        /* Garis biru bawah action card */
        .rk-action-card::before {
            content: '';
            position: absolute;
            bottom: 0; left: 0;
            width: 100%; height: 3px;
            background: #0EA5E9;
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.3s ease;
        }
        .rk-action-card:hover::before { transform: scaleX(1); }

        /* Grid 4 kolom */
        .rk-grid-4 {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }
        @media (max-width: 1024px) { .rk-grid-4 { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 640px)  { .rk-grid-4 { grid-template-columns: 1fr; } }

        /* Grid bottom 2 kolom */
        .rk-grid-bottom {
            display: grid;
            grid-template-columns: 2fr 1.1fr;
            gap: 20px;
        }
        @media (max-width: 1024px) { .rk-grid-bottom { grid-template-columns: 1fr; } }

        /* Activity item hover */
        .rk-activity-item {
            display: flex; align-items: center; justify-content: space-between;
            padding: 12px 16px;
            border-bottom: 1px solid #f0f0f0;
            transition: background 0.15s, padding-left 0.15s;
        }
        .rk-activity-item:hover { background: #fcfcfc; padding-left: 20px; border-radius: 6px; }
        .rk-activity-item:last-child { border-bottom: none; }
    </style>
@endsection