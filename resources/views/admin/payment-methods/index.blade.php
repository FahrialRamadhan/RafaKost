@extends('layouts.app')

@section('content')
<style>
    .pm-body {
        background: #f1f5f9;
        min-height: 100vh;
        padding: 24px 16px;
        font-family: 'Plus Jakarta Sans', 'Figtree', sans-serif;
    }
    .pm-container { max-width: 1100px; margin: 0 auto; }

    /* Header */
    .pm-header { margin-bottom: 20px; }
    .pm-header h1 { font-size: 18px; font-weight: 700; color: #0f172a; }
    .pm-header p { font-size: 12px; color: #94a3b8; margin-top: 3px; }

    /* Alert */
    .pm-alert {
        display: flex; align-items: flex-start; gap: 8px;
        padding: 11px 16px; border-radius: 10px;
        font-size: 12.5px; margin-bottom: 14px;
    }
    .pm-alert-success { background: #f0fdf4; border: 1px solid #bbf7d0; color: #15803d; }
    .pm-alert-error   { background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; }
    .pm-alert ul { margin: 0; padding-left: 16px; }

    /* Card */
    .pm-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 20px 22px;
        margin-bottom: 14px;
    }
    .pm-card-title {
        display: flex; align-items: center; gap: 8px;
        font-size: 13px; font-weight: 700; color: #0f172a;
        margin-bottom: 18px;
    }
    .pm-section-icon {
        width: 28px; height: 28px;
        background: #eff6ff; border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0; color: #3b82f6;
    }
    .pm-section-icon svg { width: 14px; height: 14px; }

    /* Form grid */
    .pm-form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }
    @media (max-width: 600px) { .pm-form-grid { grid-template-columns: 1fr; } }
    .pm-col-span-2 { grid-column: span 2; }
    @media (max-width: 600px) { .pm-col-span-2 { grid-column: span 1; } }

    .pm-form-label {
        display: block; font-size: 12px; font-weight: 600;
        color: #475569; margin-bottom: 5px;
    }
    .pm-input, .pm-select, .pm-textarea {
        width: 100%;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 8px 12px;
        font-size: 12.5px;
        color: #1e293b;
        font-family: inherit;
        background: #f8fafc;
        outline: none;
        transition: border-color 0.15s, background 0.15s;
    }
    .pm-input:focus, .pm-select:focus, .pm-textarea:focus {
        border-color: #3b82f6; background: #fff;
    }
    .pm-textarea { resize: vertical; }
    .pm-hint { font-size: 11px; color: #94a3b8; margin-top: 4px; }

    .pm-btn-primary {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 9px 18px;
        background: #2563eb; color: #fff;
        border: none; border-radius: 10px;
        font-size: 12.5px; font-weight: 700;
        cursor: pointer; font-family: inherit;
        transition: background 0.15s;
        margin-top: 14px;
    }
    .pm-btn-primary:hover { background: #1d4ed8; }

    /* Table card */
    .pm-table-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        overflow: hidden;
    }
    .pm-table-header {
        display: flex; align-items: center; gap: 8px;
        padding: 16px 22px;
        border-bottom: 1px solid #f1f5f9;
        font-size: 13px; font-weight: 700; color: #0f172a;
    }

    .pm-table { width: 100%; border-collapse: collapse; font-size: 12.5px; }
    .pm-table thead tr { background: #f8fafc; }
    .pm-table thead th {
        padding: 10px 14px;
        text-align: left;
        font-size: 11px; font-weight: 600;
        color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em;
        border-bottom: 1px solid #f1f5f9;
        white-space: nowrap;
    }
    .pm-table tbody tr { border-bottom: 1px solid #f8fafc; transition: background 0.1s; }
    .pm-table tbody tr:last-child { border-bottom: none; }
    .pm-table tbody tr:hover { background: #fafcff; }
    .pm-table td { padding: 10px 14px; vertical-align: middle; color: #1e293b; }

    /* Badge */
    .pm-badge {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 3px 9px; border-radius: 99px;
        font-size: 10.5px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;
    }
    .pm-badge-dot { width: 5px; height: 5px; border-radius: 50%; background: currentColor; }
    .pm-badge-on  { background: #dcfce7; color: #16a34a; }
    .pm-badge-off { background: #fee2e2; color: #dc2626; }

    /* Code tag */
    .pm-code {
        display: inline-block;
        background: #f1f5f9; border-radius: 6px;
        padding: 2px 7px; font-size: 11px;
        color: #475569; font-family: monospace;
    }

    /* Logo */
    .pm-logo { height: 28px; max-width: 80px; object-fit: contain; }
    .pm-no-logo { font-size: 11px; color: #cbd5e1; }

    /* Action buttons */
    .pm-actions { display: flex; flex-direction: column; gap: 6px; }

    .pm-btn-toggle-on, .pm-btn-toggle-off, .pm-btn-edit, .pm-btn-delete {
        display: inline-flex; align-items: center; justify-content: center; gap: 4px;
        padding: 5px 10px; border-radius: 8px;
        font-size: 11px; font-weight: 700;
        border: none; cursor: pointer; font-family: inherit;
        transition: opacity 0.15s;
        width: 100%;
    }
    .pm-btn-toggle-on  { background: #fee2e2; color: #dc2626; }
    .pm-btn-toggle-off { background: #dcfce7; color: #16a34a; }
    .pm-btn-toggle-on:hover, .pm-btn-toggle-off:hover { opacity: 0.8; }

    .pm-btn-edit {
        background: #eff6ff; color: #2563eb;
        border: none; cursor: pointer;
    }
    .pm-btn-edit:hover { background: #dbeafe; }

    .pm-btn-delete {
        background: #f1f5f9; color: #64748b;
        border: none; cursor: pointer;
    }
    .pm-btn-delete:hover { background: #e2e8f0; }

    /* Edit modal / dropdown */
    details.pm-edit-details summary { list-style: none; }
    details.pm-edit-details summary::-webkit-details-marker { display: none; }

    .pm-edit-panel {
        position: absolute;
        z-index: 50;
        right: 0; top: calc(100% + 6px);
        width: 300px;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 16px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.08);
    }
    .pm-edit-panel-title {
        font-size: 12px; font-weight: 700; color: #0f172a;
        margin-bottom: 12px; border-bottom: 1px solid #f1f5f9; padding-bottom: 8px;
    }
    .pm-edit-field { margin-bottom: 8px; }
    .pm-edit-label { font-size: 11px; font-weight: 600; color: #475569; margin-bottom: 3px; display: block; }
    .pm-edit-input, .pm-edit-select {
        width: 100%; border: 1px solid #e2e8f0; border-radius: 8px;
        padding: 6px 10px; font-size: 12px; color: #1e293b;
        font-family: inherit; background: #f8fafc; outline: none;
    }
    .pm-edit-input:focus, .pm-edit-select:focus { border-color: #3b82f6; background: #fff; }
    .pm-edit-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }

    .pm-btn-save-edit {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 7px 14px; background: #2563eb; color: #fff;
        border: none; border-radius: 8px;
        font-size: 11.5px; font-weight: 700;
        cursor: pointer; font-family: inherit;
        margin-top: 10px; transition: background 0.15s;
    }
    .pm-btn-save-edit:hover { background: #1d4ed8; }

    .pm-action-wrap { position: relative; }

    .pm-empty {
        padding: 32px; text-align: center;
        color: #94a3b8; font-size: 13px;
    }
    .pm-empty svg { width: 36px; height: 36px; margin: 0 auto 10px; display: block; opacity: 0.3; }

    .pm-name-strong { font-weight: 600; color: #1e293b; }
    .pm-name-sub { font-size: 11px; color: #94a3b8; margin-top: 2px; }

    .pm-fee-main { font-weight: 600; }
    .pm-fee-sub { font-size: 11px; color: #94a3b8; margin-top: 2px; }
</style>

<div class="pm-body">
    <div class="pm-container">

        {{-- Hero Banner --}}
        <div class="relative overflow-hidden rounded-2xl mb-8"
             style="background-image: url('{{ asset('images/frame.png') }}'); background-size: cover; background-position: center; background-repeat: no-repeat;">

            <div class="relative px-8 py-10 md:py-12">
                <h1 class="text-3xl md:text-4xl font-bold text-white mb-2">
                    Metode Pembayaran
                </h1>
                <p class="text-sm md:text-base mb-5 max-w-2xl" style="color: rgba(255,255,255,0.75);">
                    Atur metode payment gateway dan pantau status pembayaran secara real-time. Jika sistem tidak menerima callback, lakukan konfirmasi pembayaran secara manual.
                </p>

                <div class="flex flex-wrap items-center gap-3">
                    <a href="{{ route('admin.dashboard') }}"
                       class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-semibold text-white transition"
                       style="background-color: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.2);"
                       onmouseover="this.style.backgroundColor='rgba(255,255,255,0.18)'"
                       onmouseout="this.style.backgroundColor='rgba(255,255,255,0.08)'">
                        ← Kembali ke Dashboard
                    </a>
                </div>
            </div>
        </div>

        {{-- Alerts --}}
        @if(session('success'))
            <div class="pm-alert pm-alert-success">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="flex-shrink:0;margin-top:1px">
                    <path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="pm-alert pm-alert-error">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="flex-shrink:0;margin-top:1px">
                    <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        {{-- Form Tambah --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm mb-8">
            
            {{-- Header Form --}}
            <div class="flex items-center gap-2.5 mb-6 text-gray-800 border-b border-gray-100 pb-4">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/>
                </svg>
                <h2 class="text-[15px] font-bold">Tambah metode pembayaran</h2>
            </div>

            <form method="POST" action="{{ route('admin.payment-methods.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">

                    {{-- Nama Metode --}}
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 mb-1.5 uppercase tracking-wider">NAMA METODE</label>
                        <input type="text" name="name" placeholder="Contoh: QRIS, DANA, OVO" class="w-full border border-gray-200 rounded-md px-3 py-2 text-[13px] text-gray-800 placeholder-gray-300 bg-white focus:border-sky-400 focus:ring-0 outline-none transition">
                    </div>

                    {{-- Kode Unik --}}
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 mb-1.5 uppercase tracking-wider">KODE/KODE UNIK</label>
                        <input type="text" name="code" placeholder="Contoh: QRIS_REALTIME" class="w-full border border-gray-200 rounded-md px-3 py-2 text-[13px] text-gray-800 placeholder-gray-300 bg-white focus:border-sky-400 focus:ring-0 outline-none transition">
                    </div>

                    {{-- Provider --}}
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 mb-1.5 uppercase tracking-wider">PROVIDER</label>
                        <select name="gateway_code" class="w-full border border-gray-200 rounded-md py-2 pl-3 pr-8 text-[13px] text-gray-800 bg-white focus:border-sky-400 focus:ring-0 outline-none transition appearance-none bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2216%22%20height%3D%2216%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20stroke%3D%22%236b7280%22%20stroke-width%3D%222%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpolyline%20points%3D%226%209%2012%2015%2018%209%22%3E%3C%2Fpolyline%3E%3C%2Fsvg%3E')] bg-no-repeat bg-[position:right_12px_center]">
                            <option value="tokopay">TokoPay</option>
                            <option value="cashify">Cashify</option>
                        </select>
                    </div>

                    {{-- Deskripsi / Keterangan --}}
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 mb-1.5 uppercase tracking-wider">DESKRIPSI (OPSIONAL)</label>
                        <input type="text" name="info" placeholder="Contoh: QRIS Real-time" class="w-full border border-gray-200 rounded-md px-3 py-2 text-[13px] text-gray-800 placeholder-gray-300 bg-white focus:border-sky-400 focus:ring-0 outline-none transition">
                    </div>

                    {{-- Tipe Pembayaran --}}
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 mb-1.5 uppercase tracking-wider">TIPE</label>
                        <select name="category" class="w-full border border-gray-200 rounded-md py-2 pl-3 pr-8 text-[13px] text-gray-800 bg-white focus:border-sky-400 focus:ring-0 outline-none transition appearance-none bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2216%22%20height%3D%2216%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20stroke%3D%22%236b7280%22%20stroke-width%3D%222%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpolyline%20points%3D%226%209%2012%2015%2018%209%22%3E%3C%2Fpolyline%3E%3C%2Fsvg%3E')] bg-no-repeat bg-[position:right_12px_center]">
                            <option value="" disabled selected hidden>Pilih tipe</option>
                            <option value="qris">QRIS</option>
                            <option value="e-wallet">E-Wallet</option>
                            <option value="virtual-account">Virtual Account</option>
                            <option value="saldo">Saldo</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                    </div>

                    {{-- Kosong agar sejajar --}}
                    <div class="hidden md:block"></div>

                    {{-- Fee Percent --}}
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 mb-1.5 uppercase tracking-wider">FEE (PERSEN)</label>
                        <input type="text" name="fee_percent" value="0" placeholder="Contoh: 0.00" class="w-full border border-gray-200 rounded-md px-3 py-2 text-[13px] text-gray-800 placeholder-gray-300 bg-white focus:border-sky-400 focus:ring-0 outline-none transition">
                    </div>

                    {{-- Fix Fee --}}
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 mb-1.5 uppercase tracking-wider">FEE (NOMINAL)</label>
                        <input type="number" name="fee_fixed" value="0" placeholder="Contoh: 0" class="w-full border border-gray-200 rounded-md px-3 py-2 text-[13px] text-gray-800 placeholder-gray-300 bg-white focus:border-sky-400 focus:ring-0 outline-none transition">
                    </div>

                    {{-- Logo Pembayaran --}}
                    <div class="md:col-span-1">
                        <label class="block text-[10px] font-bold text-gray-500 mb-1.5 uppercase tracking-wider">LOGO (OPSIONAL)</label>
                        <div class="relative flex items-center">
                            <div class="absolute left-3 text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                    <polyline points="17 8 12 3 7 8"/>
                                    <line x1="12" y1="3" x2="12" y2="15"/>
                                </svg>
                            </div>
                            
                            <input type="file" name="logo" id="logo-upload" class="hidden" accept="image/jpeg, image/png, image/svg+xml, image/webp" onchange="document.getElementById('file-name').textContent = this.files[0] ? this.files[0].name : 'Pilih file logo'">
                            
                            <label for="logo-upload" class="w-full border border-gray-200 rounded-md pl-9 pr-20 py-2 text-[12.5px] text-gray-400 bg-white cursor-pointer flex items-center h-[38px] truncate">
                                <span id="file-name">Pilih file logo</span>
                            </label>
                            
                            <button type="button" onclick="document.getElementById('logo-upload').click()" class="absolute right-0 top-0 bottom-0 px-4 bg-[#f8f9fa] border-l border-gray-200 rounded-r-md text-[12px] font-medium text-gray-600 hover:bg-gray-100 transition flex items-center justify-center">
                                Browse
                            </button>
                        </div>
                        <p class="text-[8.5px] text-gray-600 mt-1.5 font-bold tracking-wide">Format: JPG, PNG, SVG, WEBP. Maksimal 2MB.</p>
                    </div>

                </div>

                {{-- Tombol Simpan --}}
                <div class="mt-8">
                    <button type="submit" class="w-full md:w-[calc(50%-12px)] py-2.5 bg-sky-500 hover:bg-sky-600 text-white rounded-md text-[13px] font-semibold transition flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                            <polyline points="17 21 17 13 7 13 7 21"/>
                            <polyline points="7 3 7 8 15 8"/>
                        </svg>
                        Simpan Metode
                    </button>
                </div>
            </form>
        </div>

        {{-- Table --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
            
            {{-- Table Header Title --}}
            <div class="flex items-center gap-2.5 mb-6 text-gray-800">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="3"/>
                    <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/>
                </svg>
                <h2 class="text-[15px] font-bold">Semua metode pembayaran</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead>
                        <tr class="bg-black text-white text-[10px] font-bold uppercase tracking-wider">
                            <th class="py-4 px-5 rounded-tl-xl font-bold">Nama</th>
                            <th class="py-4 px-5 font-bold">Status</th>
                            <th class="py-4 px-5 font-bold">Provider</th>
                            <th class="py-4 px-5 font-bold">Kode</th>
                            <th class="py-4 px-5 font-bold">Tipe</th>
                            <th class="py-4 px-5 font-bold">Fee</th>
                            <th class="py-4 px-5 font-bold">Logo</th>
                            <th class="py-4 px-5 rounded-tr-xl font-bold text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-800 text-[12px]">
                        @forelse($methods as $method)
                            <tr class="border-b border-gray-100 hover:bg-gray-50/50 transition">
                                
                                {{-- Nama & Tanggal --}}
                                <td class="py-4 px-5">
                                    <div class="font-bold text-[13px] text-gray-900">{{ $method->name }}</div>
                                    <div class="text-[10px] text-gray-400 mt-0.5">{{ $method->created_at->format('d/m/Y') }}</div>
                                </td>

                                {{-- Status Pill --}}
                                <td class="py-4 px-5">
                                    @if($method->is_active)
                                        <span class="bg-[#dcfce7] text-[#16a34a] px-2 py-1 rounded text-[9px] font-bold tracking-wider inline-flex items-center gap-1.5">
                                            <span class="w-1.5 h-1.5 bg-[#16a34a] rounded-full"></span> ON
                                        </span>
                                    @else
                                        <span class="bg-red-100 text-red-600 px-2 py-1 rounded text-[9px] font-bold tracking-wider inline-flex items-center gap-1.5">
                                            <span class="w-1.5 h-1.5 bg-red-600 rounded-full"></span> OFF
                                        </span>
                                    @endif
                                </td>

                                {{-- Provider --}}
                                <td class="py-4 px-5 font-medium">{{ ucfirst($method->gateway_code) }}</td>

                                {{-- Kode --}}
                                <td class="py-4 px-5 font-medium">{{ $method->code }}</td>

                                {{-- Tipe --}}
                                <td class="py-4 px-5 text-gray-600">{{ ucfirst($method->category) }}</td>

                                {{-- Fee --}}
                                <td class="py-4 px-5">
                                    <div class="font-bold text-gray-900">Rp {{ number_format($method->fee_fixed, 0, ',', '.') }}</div>
                                    <div class="text-[10px] text-gray-400 mt-0.5">{{ $method->fee_percent }}% percent</div>
                                </td>

                                {{-- Logo --}}
                                <td class="py-4 px-5">
                                    @if($method->logo)
                                        <img src="{{ asset(ltrim($method->logo, '/')) }}" alt="{{ $method->name }}" class="h-6 object-contain max-w-[80px]">
                                    @else
                                        <span class="text-gray-300">—</span>
                                    @endif
                                </td>

                                {{-- Aksi --}}
                                <td class="py-4 px-5 relative">
                                    <div class="flex items-center justify-center gap-2">
                                        
                                        {{-- Toggle OFF/ON Button --}}
                                        <form method="POST" action="{{ route('admin.payment-methods.toggle', $method) }}">
                                            @csrf
                                            <button type="submit" class="px-2.5 py-1.5 rounded-md text-[9px] font-bold tracking-wider flex items-center gap-1 transition {{ $method->is_active ? 'bg-red-50 text-red-500 hover:bg-red-100' : 'bg-green-50 text-green-600 hover:bg-green-100' }}">
                                                @if($method->is_active)
                                                    <span class="w-1 h-1 bg-red-500 rounded-full"></span> OFF
                                                @else
                                                    <span class="w-1 h-1 bg-green-600 rounded-full"></span> ON
                                                @endif
                                            </button>
                                        </form>

                                        {{-- Edit Dropdown --}}
                                        <details class="group relative [&_summary::-webkit-details-marker]:hidden">
                                            <summary class="list-none cursor-pointer">
                                                <div class="bg-sky-50 text-sky-500 hover:bg-sky-100 px-2.5 py-1.5 rounded-md text-[9px] font-bold tracking-wider flex items-center gap-1 transition">
                                                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                                        <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                                                        <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                                    </svg>
                                                    Edit
                                                </div>
                                            </summary>

                                            {{-- Panel Form Edit Popover --}}
                                            <div class="absolute right-0 mt-2 w-80 bg-white border border-gray-200 rounded-xl shadow-xl z-50 p-5 hidden group-open:block">
                                                <div class="text-[13px] font-bold text-gray-800 mb-4 border-b border-gray-100 pb-2">
                                                    Edit — {{ $method->name }}
                                                </div>

                                                <form method="POST" action="{{ route('admin.payment-methods.update', $method) }}" enctype="multipart/form-data" class="space-y-3">
                                                    @csrf
                                                    @method('PUT')

                                                    <div>
                                                        <label class="block text-[10px] font-bold text-gray-500 mb-1 uppercase tracking-wider">Nama</label>
                                                        <input type="text" name="name" value="{{ $method->name }}" class="w-full border border-gray-200 rounded-md px-3 py-1.5 text-[12px] focus:border-sky-400 focus:ring-0 outline-none">
                                                    </div>

                                                    <div>
                                                        <label class="block text-[10px] font-bold text-gray-500 mb-1 uppercase tracking-wider">Kode</label>
                                                        <input type="text" name="code" value="{{ $method->code }}" class="w-full border border-gray-200 rounded-md px-3 py-1.5 text-[12px] focus:border-sky-400 focus:ring-0 outline-none">
                                                    </div>

                                                    {{-- Provider: dari fungsi Kode 2 --}}
                                                    <div>
                                                        <label class="block text-[10px] font-bold text-gray-500 mb-1 uppercase tracking-wider">Provider</label>
                                                        <select name="gateway_code" class="w-full border border-gray-200 rounded-md py-1.5 pl-3 pr-8 text-[12px] text-gray-800 bg-white focus:border-sky-400 focus:ring-0 outline-none transition">
                                                            <option value="tokopay" {{ $method->gateway_code === 'tokopay' ? 'selected' : '' }}>TokoPay</option>
                                                            <option value="cashify" {{ $method->gateway_code === 'cashify' ? 'selected' : '' }}>Cashify</option>
                                                        </select>
                                                    </div>

                                                    {{-- Tipe: dari fungsi Kode 2 --}}
                                                    <div>
                                                        <label class="block text-[10px] font-bold text-gray-500 mb-1 uppercase tracking-wider">Tipe</label>
                                                        <select name="category" class="w-full border border-gray-200 rounded-md py-1.5 pl-3 pr-8 text-[12px] text-gray-800 bg-white focus:border-sky-400 focus:ring-0 outline-none transition">
                                                            <option value="qris" {{ $method->category === 'qris' ? 'selected' : '' }}>QRIS</option>
                                                            <option value="e-wallet" {{ $method->category === 'e-wallet' ? 'selected' : '' }}>E-Wallet</option>
                                                            <option value="virtual-account" {{ $method->category === 'virtual-account' ? 'selected' : '' }}>Virtual Account</option>
                                                            <option value="saldo" {{ $method->category === 'saldo' ? 'selected' : '' }}>Saldo</option>
                                                            <option value="lainnya" {{ $method->category === 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                                                        </select>
                                                    </div>

                                                    <div class="grid grid-cols-2 gap-3">
                                                        <div>
                                                            <label class="block text-[10px] font-bold text-gray-500 mb-1 uppercase tracking-wider">Fix Fee</label>
                                                            <input type="number" name="fee_fixed" value="{{ $method->fee_fixed }}" class="w-full border border-gray-200 rounded-md px-3 py-1.5 text-[12px] focus:border-sky-400 focus:ring-0 outline-none">
                                                        </div>
                                                        <div>
                                                            <label class="block text-[10px] font-bold text-gray-500 mb-1 uppercase tracking-wider">Fee %</label>
                                                            <input type="text" name="fee_percent" value="{{ $method->fee_percent }}" class="w-full border border-gray-200 rounded-md px-3 py-1.5 text-[12px] focus:border-sky-400 focus:ring-0 outline-none">
                                                        </div>
                                                    </div>

                                                    {{-- Keterangan/info: dari fungsi Kode 2 --}}
                                                    <div>
                                                        <label class="block text-[10px] font-bold text-gray-500 mb-1 uppercase tracking-wider">Keterangan</label>
                                                        <input type="text" name="info" value="{{ $method->info }}" class="w-full border border-gray-200 rounded-md px-3 py-1.5 text-[12px] focus:border-sky-400 focus:ring-0 outline-none">
                                                    </div>

                                                    <div>
                                                        <label class="block text-[10px] font-bold text-gray-500 mb-1 uppercase tracking-wider">Ganti Logo</label>
                                                        <input type="file" name="logo" accept="image/jpeg, image/png, image/svg+xml, image/webp" class="w-full border border-gray-200 rounded-md px-2 py-1 text-[11px] file:mr-3 file:py-1 file:px-2 file:rounded file:border-0 file:text-[10px] file:font-semibold file:bg-gray-50 file:text-gray-700 hover:file:bg-gray-100">
                                                    </div>

                                                    <button type="submit" class="w-full mt-2 py-2 bg-sky-500 hover:bg-sky-600 text-white rounded-md text-[12px] font-semibold transition flex items-center justify-center gap-1.5">
                                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                                            <polyline points="20 6 9 17 4 12"/>
                                                        </svg>
                                                        Simpan Perubahan
                                                    </button>
                                                </form>
                                            </div>
                                        </details>

                                        {{-- Hapus Button --}}
                                        <form method="POST" action="{{ route('admin.payment-methods.destroy', $method) }}" onsubmit="return confirm('Yakin hapus metode {{ $method->name }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-gray-100 text-gray-500 hover:bg-gray-200 hover:text-gray-700 px-2.5 py-1.5 rounded-md text-[9px] font-bold tracking-wider flex items-center gap-1 transition">
                                                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                                    <polyline points="3 6 5 6 21 6"/>
                                                    <path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/>
                                                    <path d="M10 11v6M14 11v6"/>
                                                    <path d="M9 6V4h6v2"/>
                                                </svg>
                                                Hapus
                                            </button>
                                        </form>

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-400">
                                        <svg class="w-10 h-10 mb-3 text-gray-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                            <rect x="1" y="4" width="22" height="16" rx="2" ry="2"/>
                                            <line x1="1" y1="10" x2="23" y2="10"/>
                                        </svg>
                                        <p class="text-[13px] font-medium">Belum ada metode pembayaran.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.tailwindcss.com"></script>
@endsection