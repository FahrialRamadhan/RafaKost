@extends('layouts.app')
@section('content')

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@400;600;700;800;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        :root {
            --blue-primary: #2563EB;
            --blue-dark: #1D4ED8;
            --blue-light: #3B82F6;
            --blue-soft: #EFF6FF;
            --slate-dark: #1e293b;
            --slate-mid: #475569;
            --slate-light: #94a3b8;
            --white: #ffffff;
            --gray-bg: #F1F5F9;
        }

        body { font-family: 'DM Sans', sans-serif; background: var(--gray-bg); }

        .rk-hero {
            background: linear-gradient(135deg, #1e293b 0%, #1D4ED8 60%, #2563EB 100%);
            border-radius: 20px;
            padding: 40px 48px;
            position: relative;
            overflow: hidden;
            color: white;
            box-shadow: 0 20px 60px rgba(37,99,235,0.25);
        }
        .rk-hero::before {
            content: '';
            position: absolute;
            top: -60px; right: -60px;
            width: 240px; height: 240px;
            border-radius: 50%;
            background: rgba(255,255,255,0.05);
        }
        .rk-hero::after {
            content: '';
            position: absolute;
            bottom: -40px; right: 80px;
            width: 160px; height: 160px;
            border-radius: 50%;
            background: rgba(255,255,255,0.04);
        }
        .rk-hero-label {
            font-family: 'Barlow Condensed', sans-serif;
            font-size: 11px;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: rgba(255,255,255,0.55);
            margin-bottom: 8px;
        }
        .rk-hero h1 {
            font-family: 'Barlow Condensed', sans-serif;
            font-size: 42px;
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 10px;
        }
        .rk-hero p {
            font-size: 15px;
            color: rgba(255,255,255,0.7);
            font-weight: 300;
        }
        .rk-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(255,255,255,0.12);
            border: 1px solid rgba(255,255,255,0.18);
            border-radius: 100px;
            padding: 5px 14px;
            font-size: 12px;
            font-weight: 500;
            color: white;
            margin-top: 20px;
        }
        .rk-badge-dot { width: 7px; height: 7px; border-radius: 50%; background: #4ade80; }

        /* Stat Cards */
        .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; }
        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 24px 20px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.06);
            border: 1px solid #e2e8f0;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .stat-card:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(37,99,235,0.12); }
        .stat-icon {
            width: 44px; height: 44px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 16px;
            font-size: 20px;
        }
        .stat-label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--slate-light);
            font-weight: 600;
            margin-bottom: 6px;
        }
        .stat-value {
            font-family: 'Barlow Condensed', sans-serif;
            font-size: 36px;
            font-weight: 800;
            color: var(--slate-dark);
            line-height: 1;
        }
        .stat-sub { font-size: 12px; color: var(--slate-light); margin-top: 6px; }
        .stat-up { color: #16a34a; font-weight: 600; }
        .stat-down { color: #dc2626; font-weight: 600; }

        /* Section Title */
        .section-title {
            font-family: 'Barlow Condensed', sans-serif;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--slate-light);
            margin-bottom: 14px;
        }

        /* Action Cards */
        .actions-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; }
        .action-card {
            background: white;
            border-radius: 16px;
            padding: 28px 24px;
            border: 1.5px solid #e2e8f0;
            box-shadow: 0 1px 4px rgba(0,0,0,0.04);
            text-decoration: none;
            display: block;
            transition: all 0.2s;
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
            transition: transform 0.25s ease;
        }
        .action-card:hover { border-color: var(--blue-primary); transform: translateY(-2px); box-shadow: 0 8px 24px rgba(37,99,235,0.12); }
        .action-card:hover::before { transform: scaleX(1); }
        .action-card-icon {
            width: 48px; height: 48px;
            background: var(--blue-soft);
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 16px;
            font-size: 22px;
        }
        .action-card h3 {
            font-family: 'Barlow Condensed', sans-serif;
            font-size: 20px;
            font-weight: 700;
            color: var(--slate-dark);
            margin-bottom: 6px;
        }
        .action-card p { font-size: 13px; color: var(--slate-light); line-height: 1.5; }
        .action-arrow {
            position: absolute;
            top: 24px; right: 24px;
            color: var(--blue-primary);
            opacity: 0;
            transition: opacity 0.2s, transform 0.2s;
            font-size: 18px;
        }
        .action-card:hover .action-arrow { opacity: 1; transform: translateX(3px); }

        /* Recent Activity */
        .activity-card {
            background: white;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            overflow: hidden;
            box-shadow: 0 1px 4px rgba(0,0,0,0.06);
        }
        .activity-header {
            padding: 20px 24px 16px;
            border-bottom: 1px solid #f1f5f9;
            display: flex; align-items: center; justify-content: space-between;
        }
        .activity-header h3 {
            font-family: 'Barlow Condensed', sans-serif;
            font-weight: 700; font-size: 18px;
            color: var(--slate-dark);
        }
        .activity-item {
            display: flex; align-items: center; gap: 14px;
            padding: 14px 24px;
            border-bottom: 1px solid #f8fafc;
            transition: background 0.15s;
        }
        .activity-item:hover { background: #f8fafc; }
        .activity-item:last-child { border-bottom: none; }
        .activity-dot {
            width: 10px; height: 10px;
            border-radius: 50%;
            flex-shrink: 0;
        }
        .activity-content { flex: 1; }
        .activity-title { font-size: 14px; font-weight: 500; color: var(--slate-dark); }
        .activity-time { font-size: 12px; color: var(--slate-light); margin-top: 2px; }
        .activity-status {
            font-size: 11px; font-weight: 600;
            padding: 3px 10px; border-radius: 100px;
        }

        /* Quick Info */
        .info-card {
            background: white;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            overflow: hidden;
            box-shadow: 0 1px 4px rgba(0,0,0,0.06);
        }

        @media (max-width: 768px) {
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
            .actions-grid { grid-template-columns: 1fr; }
            .rk-hero { padding: 28px 24px; }
            .rk-hero h1 { font-size: 30px; }
        }
    </style>

    <div class="py-8 min-h-screen" style="background: var(--gray-bg);">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8" style="display: flex; flex-direction: column; gap: 24px;">

            {{-- Hero Welcome --}}
            <div class="rk-hero">
                <div class="rk-hero-label">Panel Manajemen</div>
                <h1>Selamat Datang,<br>{{ auth()->user()->name }}</h1>
                <p>Kelola kamar, penyewa, dan laporan keuangan Rafa Kost dari satu tempat.</p>
                <div class="rk-badge">
                    <div class="rk-badge-dot"></div>
                    Sistem Aktif · {{ now()->translatedFormat('l, d F Y') }}
                </div>

                {{-- House SVG decoration --}}
                <div style="position: absolute; right: 48px; top: 50%; transform: translateY(-50%); opacity: 0.1;">
                    <svg width="140" height="140" viewBox="0 0 40 40" fill="none">
                        <path d="M20 2L38 16V38H2V16L20 2Z" fill="white"/>
                        <rect x="13" y="22" width="14" height="16" fill="white" opacity="0.5"/>
                    </svg>
                </div>
            </div>

            {{-- Stat Cards --}}
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon" style="background: #EFF6FF;">🏠</div>
                    <div class="stat-label">Total Kamar</div>
                    <div class="stat-value">24</div>
                    <div class="stat-sub">Semua unit terdaftar</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon" style="background: #F0FDF4;">✅</div>
                    <div class="stat-label">Kamar Terisi</div>
                    <div class="stat-value">18</div>
                    <div class="stat-sub"><span class="stat-up">75%</span> tingkat hunian</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon" style="background: #FFF7ED;">🔑</div>
                    <div class="stat-label">Kamar Kosong</div>
                    <div class="stat-value">6</div>
                    <div class="stat-sub">Siap disewakan</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon" style="background: #FFF1F2;">⏳</div>
                    <div class="stat-label">Belum Bayar</div>
                    <div class="stat-value">3</div>
                    <div class="stat-sub"><span class="stat-down">Perlu tindakan</span></div>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div>
                <div class="section-title">Aksi Cepat</div>
                <div class="actions-grid">
                    <a href="{{ route('kamars.index') }}" class="action-card">
                        <div class="action-card-icon">🏠</div>
                        <h3>Data Kamar</h3>
                        <p>Lihat, edit, dan kelola seluruh unit kamar kost.</p>
                        <span class="action-arrow">→</span>
                    </a>
                    <a href="{{ route('kamars.create') }}" class="action-card">
                        <div class="action-card-icon">➕</div>
                        <h3>Tambah Kamar</h3>
                        <p>Daftarkan unit kamar baru ke sistem.</p>
                        <span class="action-arrow">→</span>
                    </a>
                    <a href="#" class="action-card">
                        <div class="action-card-icon">👤</div>
                        <h3>Data Penyewa</h3>
                        <p>Kelola informasi dan status penyewa aktif.</p>
                        <span class="action-arrow">→</span>
                    </a>
                    <a href="#" class="action-card">
                        <div class="action-card-icon">💰</div>
                        <h3>Pembayaran</h3>
                        <p>Catat dan verifikasi pembayaran sewa bulanan.</p>
                        <span class="action-arrow">→</span>
                    </a>
                    <a href="#" class="action-card">
                        <div class="action-card-icon">📊</div>
                        <h3>Laporan</h3>
                        <p>Lihat laporan keuangan dan statistik hunian.</p>
                        <span class="action-arrow">→</span>
                    </a>
                    <a href="{{ route('profile.edit') }}" class="action-card">
                        <div class="action-card-icon">⚙️</div>
                        <h3>Pengaturan</h3>
                        <p>Konfigurasi profil, harga, dan preferensi sistem.</p>
                        <span class="action-arrow">→</span>
                    </a>
                </div>
            </div>

            {{-- Bottom: Activity + Info --}}
            <div style="display: grid; grid-template-columns: 1fr 340px; gap: 20px;">

                {{-- Recent Activity --}}
                <div class="activity-card">
                    <div class="activity-header">
                        <h3>Aktivitas Terbaru</h3>
                        <a href="#" style="font-size: 13px; color: var(--blue-primary); text-decoration: none; font-weight: 500;">Lihat semua →</a>
                    </div>
                    <div class="activity-item">
                        <div class="activity-dot" style="background: #2563EB;"></div>
                        <div class="activity-content">
                            <div class="activity-title">Kamar A-03 — Penyewa baru masuk</div>
                            <div class="activity-time">Hari ini, 09:14</div>
                        </div>
                        <span class="activity-status" style="background: #EFF6FF; color: #2563EB;">Baru</span>
                    </div>
                    <div class="activity-item">
                        <div class="activity-dot" style="background: #16a34a;"></div>
                        <div class="activity-content">
                            <div class="activity-title">Kamar B-07 — Pembayaran diterima</div>
                            <div class="activity-time">Kemarin, 15:30</div>
                        </div>
                        <span class="activity-status" style="background: #F0FDF4; color: #16a34a;">Lunas</span>
                    </div>
                    <div class="activity-item">
                        <div class="activity-dot" style="background: #f59e0b;"></div>
                        <div class="activity-content">
                            <div class="activity-title">Kamar C-01 — Menunggu konfirmasi</div>
                            <div class="activity-time">Kemarin, 11:00</div>
                        </div>
                        <span class="activity-status" style="background: #FFFBEB; color: #d97706;">Pending</span>
                    </div>
                    <div class="activity-item">
                        <div class="activity-dot" style="background: #dc2626;"></div>
                        <div class="activity-content">
                            <div class="activity-title">Kamar B-02 — Tagihan belum dibayar</div>
                            <div class="activity-time">3 hari lalu</div>
                        </div>
                        <span class="activity-status" style="background: #FFF1F2; color: #dc2626;">Telat</span>
                    </div>
                    <div class="activity-item">
                        <div class="activity-dot" style="background: #94a3b8;"></div>
                        <div class="activity-content">
                            <div class="activity-title">Kamar A-11 — Penyewa keluar</div>
                            <div class="activity-time">5 hari lalu</div>
                        </div>
                        <span class="activity-status" style="background: #F8FAFC; color: #64748b;">Selesai</span>
                    </div>
                </div>

                {{-- Info Panel --}}
                <div style="display: flex; flex-direction: column; gap: 16px;">
                    <div class="info-card">
                        <div class="activity-header">
                            <h3 style="font-family: 'Barlow Condensed', sans-serif; font-weight: 700; font-size: 18px; color: var(--slate-dark);">Pendapatan Bulan Ini</h3>
                        </div>
                        <div style="padding: 20px 24px;">
                            <div style="font-family: 'Barlow Condensed', sans-serif; font-size: 38px; font-weight: 800; color: var(--slate-dark); line-height: 1;">
                                Rp 27,6<span style="font-size: 22px; color: var(--slate-light);">jt</span>
                            </div>
                            <div style="font-size: 13px; color: #16a34a; font-weight: 600; margin-top: 6px;">↑ 12% dari bulan lalu</div>
                            <div style="margin-top: 16px; background: #f1f5f9; border-radius: 8px; overflow: hidden; height: 8px;">
                                <div style="width: 75%; height: 100%; background: linear-gradient(90deg, #2563EB, #3B82F6); border-radius: 8px;"></div>
                            </div>
                            <div style="font-size: 12px; color: var(--slate-light); margin-top: 6px;">18 dari 24 kamar sudah bayar</div>
                        </div>
                    </div>

                    <div class="info-card">
                        <div class="activity-header">
                            <h3 style="font-family: 'Barlow Condensed', sans-serif; font-weight: 700; font-size: 18px; color: var(--slate-dark);">Info Sistem</h3>
                        </div>
                        <div style="padding: 16px 24px; display: flex; flex-direction: column; gap: 12px;">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span style="font-size: 13px; color: var(--slate-mid);">Admin</span>
                                <span style="font-size: 13px; font-weight: 600; color: var(--slate-dark);">{{ auth()->user()->name }}</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span style="font-size: 13px; color: var(--slate-mid);">Role</span>
                                <span style="font-size: 11px; font-weight: 600; background: #EFF6FF; color: #2563EB; padding: 3px 10px; border-radius: 100px;">Administrator</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span style="font-size: 13px; color: var(--slate-mid);">Versi Sistem</span>
                                <span style="font-size: 13px; font-weight: 600; color: var(--slate-dark);">v1.0.0</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span style="font-size: 13px; color: var(--slate-mid);">Status</span>
                                <span style="font-size: 12px; font-weight: 600; color: #16a34a; display: flex; align-items: center; gap: 5px;">
                                    <span style="width: 7px; height: 7px; border-radius: 50%; background: #16a34a; display: inline-block;"></span>
                                    Online
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
@endsection