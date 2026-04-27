@extends('layouts.app')
@section('content')

    @php
        $hunianPersen = $totalKamar > 0 ? round(($kamarTerisi / $totalKamar) * 100) : 0;
    @endphp

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --blue-primary: #0EA5E9; 
            --slate-dark: #1E293B;
            --slate-mid: #475569;
            --slate-light: #94A3B8;
            --white: #ffffff;
            --border-color: #F1F5F9;
            /* Shadow tebal */
            --card-shadow: 0px 14px 35px rgba(0, 0, 0, 0.09);
            --card-shadow-hover: 0px 20px 45px rgba(0, 0, 0, 0.14);
        }

        /* MEMAKSA WARNA PUTIH CERAH */
        html, body { 
            background-color: #ffffff !important; 
            font-family: 'DM Sans', sans-serif; 
            color: var(--slate-dark);
            margin: 0;
        }

        .dashboard-wrapper {
            background-color: #ffffff !important;
            min-height: 100vh;
            width: 100%;
            padding-bottom: 60px;
        }

        .container {
            max-width: 1140px;
            margin: 0 auto;
            padding: 32px 20px;
            display: flex;
            flex-direction: column;
            gap: 32px;
            background-color: #ffffff !important;
        }

        .svg-icon {
            width: 24px;
            height: 24px;
            stroke: currentColor;
            stroke-width: 2; /* Dibuat lebih tebal sesuai gambar referensi */
            fill: none;
            stroke-linecap: round;
            stroke-linejoin: round;
        }
        .svg-icon-sm { width: 16px; height: 16px; stroke-width: 1.5; }

        /* Ikon Logo Bintang Bersinar dari file frameworkpartikel.png */
        .section-logo {
            width: 22px;
            height: 22px;
            object-fit: contain;
        }

        .section-title {
            font-size: 15px;
            font-weight: 700;
            color: var(--slate-dark);
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* --- HERO SECTION --- */
        .hero-banner {
            background: url("{{ asset('images/frame.png') }}") center/cover no-repeat;
            border-radius: 12px;
            padding: 48px;
            position: relative;
            overflow: hidden;
            color: white;
            box-shadow: var(--card-shadow);
        }

        .hero-content {
            position: relative;
            z-index: 10;
            max-width: 60%;
        }

        .hero-content h1 {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 8px;
            line-height: 1.2;
            letter-spacing: -0.5px;
        }

        .hero-content p {
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 24px;
            opacity: 0.95;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--blue-primary);
            border-radius: 100px;
            padding: 8px 16px;
            font-size: 12px;
            font-weight: 500;
            color: white;
            box-shadow: 0 4px 12px rgba(14, 165, 233, 0.3);
        }

        /* --- STAT CARDS --- */
        .grid-4 { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; }
        
        .stat-card {
            background: #ffffff;
            border-radius: 12px;
            padding: 24px 16px;
            border: 1px solid #f0f0f0;
            text-align: center;
            box-shadow: var(--card-shadow);
            transition: transform 0.3s ease, box-shadow 0.3s ease; 
        }

        .stat-card:hover { 
            transform: translateY(-3px); 
            box-shadow: var(--card-shadow-hover); 
        }

        .stat-icon-wrapper {
            margin-bottom: 12px;
            color: #000000; /* Warna ikon hitam solid seperti digambar */
            display: flex;
            justify-content: center;
        }

        .stat-label { font-size: 13px; font-weight: 600; color: var(--slate-dark); margin-bottom: 8px; }
        .stat-value { font-size: 28px; font-weight: 800; color: #000000; margin-bottom: 8px; line-height: 1; }
        .stat-sub { font-size: 10px; color: var(--slate-light); font-weight: 500; }

        /* --- ACTION CARDS --- */
        .grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }

        .action-card {
            background: #ffffff;
            border-radius: 12px;
            padding: 24px 20px;
            border: 1px solid #f0f0f0;
            text-align: center;
            box-shadow: var(--card-shadow);
            text-decoration: none;
            color: var(--slate-dark);
            display: block;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .action-card::before {
            content: '';
            position: absolute;
            bottom: 0; left: 0;
            width: 100%; height: 3px;
            background: var(--blue-primary);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.3s ease;
        }

        .action-card:hover { transform: translateY(-2px); box-shadow: var(--card-shadow-hover); }
        .action-card:hover::before { transform: scaleX(1); }

        .action-title { font-size: 13px; font-weight: 600; margin-bottom: 6px; }
        .action-desc { font-size: 11px; color: var(--slate-light); font-weight: 400; line-height: 1.4; }

        /* --- BOTTOM PANELS --- */
        .bottom-grid { display: grid; grid-template-columns: 2fr 1.1fr; gap: 20px; align-items: flex-start; }
        .panel-card { background: #ffffff; border-radius: 12px; border: 1px solid #f0f0f0; padding: 24px; box-shadow: var(--card-shadow); }
        .panel-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
        .link-blue { color: var(--blue-primary); font-size: 13px; font-weight: 500; text-decoration: none; display: flex; align-items: center; gap: 4px; }
        
        .activity-list { display: flex; flex-direction: column; gap: 8px; }
        .activity-row { display: flex; align-items: center; padding: 8px 0; transition: background 0.15s, padding-left 0.15s; }
        .activity-row:hover { background: #fcfcfc; padding-left: 8px; border-radius: 6px; }
        
        .activity-icon {
            margin-right: 12px;
            display: flex;
            align-items: center;
        }
        
        /* Ikon Timeline Bulat Hollow */
        .timeline-dot {
            width: 8px; 
            height: 8px; 
            border: 1.5px solid #94A3B8; 
            border-radius: 50%; 
            background-color: transparent;
        }

        .activity-left { display: flex; flex-direction: column; min-width: 90px; }
        .activity-room { font-size: 13px; font-weight: 600; color: var(--slate-dark); }
        .activity-time { font-size: 10px; color: var(--slate-light); margin-top: 4px; }
        .activity-line { flex-grow: 1; height: 1px; background-color: #CBD5E1; margin: 0 16px; }
        .activity-desc { font-size: 12px; color: var(--slate-dark); font-weight: 500; margin-right: 16px; white-space: nowrap; }
        .badge-status { font-size: 10px; font-weight: 600; padding: 4px 16px; border-radius: 100px; text-transform: capitalize; min-width: 60px; text-align: center; }
        .badge-green { background: #DCFCE7; color: #16A34A; }
        .badge-yellow { background: #FEF3C7; color: #D97706; }

        .revenue-amount { font-size: 36px; font-weight: 700; color: var(--slate-dark); margin-bottom: 20px; display: flex; align-items: baseline; gap: 4px; }
        .revenue-amount span { font-size: 16px; font-weight: 600; }
        .progress-bar-bg { background: #F1F5F9; height: 8px; border-radius: 10px; width: 100%; overflow: hidden; margin-bottom: 12px; }
        .progress-bar-fill { background: var(--blue-primary); height: 100%; border-radius: 10px; transition: width 0.5s ease-in-out; }
        .revenue-desc { font-size: 11px; color: var(--slate-light); font-weight: 500; }

        @media (max-width: 768px) {
            .grid-4, .grid-3, .bottom-grid { grid-template-columns: 1fr; }
            .hero-content { max-width: 100%; }
        }
    </style>

    <div class="dashboard-wrapper">
        <div class="container">

            {{-- Hero Welcome --}}
            <div class="hero-banner">
                <div class="hero-content">
                    <h1>Selamat Datang,<br>{{ auth()->user()->name }}</h1>
                    <p>Kelola kamar, penyewa, dan laporan keuangan Rafa Kost dari satu tempat.</p>
                    <div class="hero-badge">
                        <svg class="svg-icon svg-icon-sm" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                        Sistem Aktif - {{ now()->translatedFormat('l, d F Y') }}
                    </div>
                </div>
            </div>

            {{-- Stat Cards --}}
            <div>
                <div class="section-title">
                    <img src="{{ asset('images/frameworkpartikel.png') }}" class="section-logo" alt="icon">
                    Informasi Kost
                </div>
                <div class="grid-4">
                    <div class="stat-card">
                        <div class="stat-icon-wrapper">
                            {{-- LOGO KASUR BARU (Sesuai Gambar 8) --}}
                            <svg class="svg-icon" viewBox="0 0 24 24">
                                <rect x="7" y="6" width="10" height="5" rx="1"></rect>
                                <rect x="5" y="11" width="14" height="5" rx="1"></rect>
                                <path d="M7 16v2"></path>
                                <path d="M17 16v2"></path>
                            </svg>
                        </div>
                        <div class="stat-label">Total Kamar</div>
                        <div class="stat-value">{{ $totalKamar }}</div>
                        <div class="stat-sub">Semua Kamar Terdaftar</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon-wrapper">
                            {{-- LOGO DOKUMEN CHECKLIST --}}
                            <svg class="svg-icon" viewBox="0 0 24 24">
                                <rect x="7" y="4" width="10" height="16" rx="1"></rect>
                                <path d="M7 8h10"></path>
                                <polyline points="10 13 11.5 14.5 14 11.5"></polyline>
                            </svg>
                        </div>
                        <div class="stat-label">Kamar Terisi</div>
                        <div class="stat-value">{{ $kamarTerisi }}</div>
                        <div class="stat-sub">{{ $hunianPersen }}% Tingkat Hunian</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon-wrapper">
                            {{-- LOGO PENCARIAN CHECKLIST --}}
                            <svg class="svg-icon" viewBox="0 0 24 24">
                                <circle cx="11" cy="11" r="6"></circle>
                                <line x1="21" y1="21" x2="15.5" y2="15.5"></line>
                                <polyline points="9 11 10.5 12.5 13 9.5"></polyline>
                            </svg>
                        </div>
                        <div class="stat-label">Kamar Kosong</div>
                        <div class="stat-value">{{ $kamarTersedia }}</div>
                        <div class="stat-sub">Siap Disewakan</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon-wrapper">
                            {{-- LOGO DOLLAR / UANG --}}
                            <svg class="svg-icon" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="9"></circle>
                                <path d="M12 7v10"></path>
                                <path d="M14.5 9a2.5 2.5 0 0 0-5 0c0 3 5 2 5 5a2.5 2.5 0 0 1-5 0"></path>
                            </svg>
                        </div>
                        <div class="stat-label">Total Nilai Kamar</div>
                        <div class="stat-value" style="font-size: 20px;">
                            Rp {{ number_format((int) $totalNilaiKamar, 0, ',', '.') }}
                        </div>
                        <div class="stat-sub">Akumulasi harga semua kamar</div>
                    </div>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div>
                <div class="section-title">
                    <img src="{{ asset('images/frameworkpartikel.png') }}" class="section-logo" alt="icon">
                    Aksi Cepat
                </div>
                <div class="grid-3">
                    <a href="{{ route('kamars.index') }}" class="action-card">
                        <div class="stat-icon-wrapper">
                            {{-- LOGO KASUR BARU (Sesuai Gambar 8) --}}
                            <svg class="svg-icon" viewBox="0 0 24 24">
                                <rect x="7" y="6" width="10" height="5" rx="1"></rect>
                                <rect x="5" y="11" width="14" height="5" rx="1"></rect>
                                <path d="M7 16v2"></path>
                                <path d="M17 16v2"></path>
                            </svg>
                        </div>
                        <div class="action-title">Data Kamar</div>
                        <div class="action-desc">Lihat, edit, dan kelola seluruh unit kamar kost.</div>
                    </a>
                    <a href="{{ route('kamars.create') }}" class="action-card">
                        <div class="stat-icon-wrapper">
                            {{-- LOGO KOTAK TAMBAH (+) --}}
                            <svg class="svg-icon" viewBox="0 0 24 24">
                                <rect x="5" y="5" width="14" height="14" rx="2"></rect>
                                <line x1="12" y1="9" x2="12" y2="15"></line>
                                <line x1="9" y1="12" x2="15" y2="12"></line>
                            </svg>
                        </div>
                        <div class="action-title">Tambah Kamar</div>
                        <div class="action-desc">Daftarkan unit kamar baru ke sistem.</div>
                    </a>
                    <a href="#" class="action-card">
                        <div class="stat-icon-wrapper">
                            {{-- LOGO DOKUMEN USER --}}
                            <svg class="svg-icon" viewBox="0 0 24 24">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                                <circle cx="12" cy="13" r="2"></circle>
                                <path d="M9 18c0-1.5 1.5-2.5 3-2.5s3 1 3 2.5"></path>
                            </svg>
                        </div>
                        <div class="action-title">Data Penyewa</div>
                        <div class="action-desc">Kelola informasi dan status penyewa aktif.</div>
                    </a>
                    <a href="#" class="action-card">
                        <div class="stat-icon-wrapper">
                            {{-- LOGO UANG KERTAS --}}
                            <svg class="svg-icon" viewBox="0 0 24 24">
                                <rect x="3" y="6" width="18" height="12" rx="2"></rect>
                                <circle cx="12" cy="12" r="2"></circle>
                                <path d="M6 12h.01"></path>
                                <path d="M18 12h.01"></path>
                            </svg>
                        </div>
                        <div class="action-title">Pembayaran</div>
                        <div class="action-desc">Catat dan verifikasi pembayaran sewa bulanan.</div>
                    </a>
                    <a href="#" class="action-card">
                        <div class="stat-icon-wrapper">
                            {{-- LOGO LAPORAN/LAYOUT (Gambar 6) --}}
                            <svg class="svg-icon" viewBox="0 0 24 24">
                                <rect x="4" y="4" width="16" height="16" rx="2"></rect>
                                <line x1="4" y1="10" x2="20" y2="10"></line>
                                <line x1="10" y1="10" x2="10" y2="20"></line>
                            </svg>
                        </div>
                        <div class="action-title">Laporan keuangan</div>
                        <div class="action-desc">Lihat laporan keuangan dan statistik hunian.</div>
                    </a>
                    <a href="{{ route('profile.edit') }}" class="action-card">
                        <div class="stat-icon-wrapper">
                            {{-- LOGO PENGATURAN --}}
                            <svg class="svg-icon" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="3"></circle>
                                <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                            </svg>
                        </div>
                        <div class="action-title">Pengaturan</div>
                        <div class="action-desc">Konfigurasi profil, harga, dan preferensi sistem.</div>
                    </a>
                </div>
            </div>

            {{-- Bottom: Activity + Info --}}
            <div class="bottom-grid">

                {{-- Recent Activity --}}
                <div class="panel-card">
                    <div class="panel-header">
                        <div class="section-title" style="margin:0;">
                            <img src="{{ asset('images/frameworkpartikel.png') }}" class="section-logo" alt="icon">
                            Aktifitas Terbaru
                        </div>
                        <a href="{{ route('kamars.index') }}" class="link-blue">
                            Lihat Semuanya
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                        </a>
                    </div>
                    
                    <div class="activity-list">
                        @forelse($kamarsTerbaru as $kamar)
                            <div class="activity-row">
                                <div class="activity-icon">
                                    <div class="timeline-dot"></div>
                                </div>
                                <div class="activity-left">
                                    <div class="activity-room">{{ $kamar->nama }}</div>
                                    <div class="activity-time">{{ $kamar->created_at->diffForHumans() }}</div>
                                </div>
                                
                                <div class="activity-line"></div>
                                
                                <div class="activity-desc">Rp {{ number_format((int) $kamar->harga, 0, ',', '.') }}</div>
                                
                                <div class="badge-status {{ $kamar->status == 'terisi' ? 'badge-green' : 'badge-yellow' }}">
                                    {{ ucfirst($kamar->status) }}
                                </div>
                            </div>
                        @empty
                            <div class="activity-row" style="justify-content: center;">
                                <span class="activity-desc">Belum ada data kamar. Silakan tambahkan data.</span>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Ringkasan Hunian --}}
                <div class="panel-card" style="align-self: start;">
                    <div class="panel-header" style="margin-bottom: 24px;">
                        <div class="section-title" style="margin:0;">
                            <img src="{{ asset('images/frameworkpartikel.png') }}" class="section-logo" alt="icon">
                            Ringkasan Hunian
                        </div>
                    </div>
                    <div class="revenue-amount">
                        {{ $hunianPersen }}<span>%</span>
                    </div>
                    <div class="progress-bar-bg">
                        <div class="progress-bar-fill" style="width: {{ $hunianPersen }}%;"></div>
                    </div>
                    <div class="revenue-desc">
                        {{ $kamarTerisi }} dari {{ $totalKamar }} kamar sudah terisi
                    </div>
                </div>

            </div>

        </div>
    </div>
@endsection