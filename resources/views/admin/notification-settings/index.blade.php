@extends('layouts.app')

@section('content')
<script src="https://cdn.tailwindcss.com"></script>
<div class="min-h-screen" style="background:#EEF2F7;">
    {{-- Mengubah max-w-4xl menjadi max-w-7xl agar lebarnya menyesuaikan navbar/logo --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
		
        {{-- HERO BANNER --}}
        <div style="background-image: url('{{ asset('images/frame.png') }}'); background-size: cover; background-position: center; background-repeat: no-repeat; border-radius:16px; padding:32px 36px; margin-bottom:24px; position:relative; overflow:hidden;">

            <h1 style="font-size:26px;font-weight:800;color:white;margin:0 0 6px;">Pengaturan Notifikasi</h1>
            <p style="color:rgba(255,255,255,.72);font-size:14px;margin:0 0 22px;max-width:420px;line-height:1.5;">
                Atur Fonnte, email, nominal denda, dan template reminder Rafa Kost.
            </p>

            <div style="display:flex;gap:10px;flex-wrap:wrap;">
                <a href="{{ route('admin.dashboard') }}"
                    style="background:rgba(255,255,255,.18);color:white;padding:7px 16px;border-radius:20px;font-size:13px;font-weight:700;text-decoration:none;display:inline-flex;align-items:center;gap:6px;"
                    onmouseover="this.style.backgroundColor='rgba(255,255,255,.25)'"
                    onmouseout="this.style.backgroundColor='rgba(255,255,255,.18)'">
                    ← Kembali ke Dashboard
                </a>
            </div>
        </div>

        {{-- ALERTS --}}
        @if(session('success'))
            <div style="background:#DCFCE7;color:#166534;padding:13px 18px;border-radius:12px;margin-bottom:18px;border-left:4px solid #16A34A;font-weight:600;font-size:14px;display:flex;align-items:center;gap:8px;">
                ✅ {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div style="background:#FEE2E2;color:#991B1B;padding:13px 18px;border-radius:12px;margin-bottom:18px;border-left:4px solid #DC2626;font-weight:600;font-size:14px;display:flex;align-items:center;gap:8px;">
                ❌ {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.notification-settings.update') }}">
            @csrf
            @method('PUT')
            
            {{-- SECTION: STATUS AKTIF --}}
            <div class="mb-3 flex items-center gap-2 text-gray-700">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/>
                </svg>
                <span class="text-[11px] font-bold uppercase tracking-widest">Status Aktif</span>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm mb-7">
                <h3 class="text-[14px] font-bold text-gray-800 mb-1">Pemberitahuan</h3>
                <p class="text-[11.5px] text-gray-400 font-medium mb-5">Pilih cara pemberitahuan saat ada kamar kosong di Rafa Kost.</p>

                <div class="space-y-3">
                    {{-- Email Toggle --}}
                    <div class="flex items-center justify-between border border-gray-100 rounded-lg p-3.5">
                        <div class="flex items-center gap-4">
                            <div class="bg-sky-50 p-2.5 rounded-md text-sky-500">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                            </div>
                            <div>
                                <div class="text-[13px] font-bold text-gray-800">Email</div>
                                <div class="text-[11px] text-gray-400 mt-0.5">Notifikasi email</div>
                            </div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="email_enabled" value="1" class="sr-only peer" {{ $settings['email_enabled'] ? 'checked' : '' }}>
                            <div class="w-10 h-[22px] bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-[18px] after:w-[18px] after:transition-all peer-checked:bg-[#0ea5e9]"></div>
                        </label>
                    </div>

                    {{-- WhatsApp Toggle --}}
                    <div class="flex items-center justify-between border border-gray-100 rounded-lg p-3.5">
                        <div class="flex items-center gap-4">
                            <div class="bg-sky-50 p-2.5 rounded-md text-sky-500">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                            </div>
                            <div>
                                <div class="text-[13px] font-bold text-gray-800">WhatsApp</div>
                                <div class="text-[11px] text-gray-400 mt-0.5">Fonnte aktif</div>
                            </div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="whatsapp_enabled" value="1" class="sr-only peer" {{ $settings['whatsapp_enabled'] ? 'checked' : '' }}>
                            <div class="w-10 h-[22px] bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-[18px] after:w-[18px] after:transition-all peer-checked:bg-[#0ea5e9]"></div>
                        </label>
                    </div>

                    {{-- Denda Toggle --}}
                    <div class="flex items-center justify-between border border-gray-100 rounded-lg p-3.5">
                        <div class="flex items-center gap-4">
                            <div class="bg-sky-50 p-2.5 rounded-md text-sky-500">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            </div>
                            <div>
                                <div class="text-[13px] font-bold text-gray-800">Denda telat</div>
                                <div class="text-[11px] text-gray-400 mt-0.5">Aktifkan denda</div>
                            </div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="late_fee_enabled" value="1" class="sr-only peer" {{ $settings['late_fee_enabled'] ? 'checked' : '' }}>
                            <div class="w-10 h-[22px] bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-[18px] after:w-[18px] after:transition-all peer-checked:bg-[#0ea5e9]"></div>
                        </label>
                    </div>
                </div>
                <p class="text-[10px] text-gray-400 mt-4 italic">WhatsApp akan dikirim ke nomor admin yang tercantum di atas.</p>
            </div>

            {{-- SECTION: KONFIGURASI FONNTE --}}
            <div class="mb-3 flex items-center gap-2 text-gray-700">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                <span class="text-[11px] font-bold uppercase tracking-widest">Konfigurasi Fonnte / WhatsApp</span>
            </div>
            
            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm mb-7">
                <div class="mb-4">
                    <label class="block text-[10px] font-bold text-gray-500 mb-1.5 uppercase tracking-wider">Token Fonnte</label>
                    <input type="text" name="fonnte_token" placeholder="Kosongkan jika tidak ingin mengganti token" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-[13px] focus:border-sky-400 focus:ring-0 outline-none transition placeholder-gray-300">
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 mb-1.5 uppercase tracking-wider">Country Code</label>
                        <input type="text" name="fonnte_country_code" value="{{ $settings['fonnte_country_code'] }}" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-[13px] focus:border-sky-400 focus:ring-0 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 mb-1.5 uppercase tracking-wider">Nomor Admin</label>
                        <input type="text" name="fonnte_admin_phone" value="{{ $settings['fonnte_admin_phone'] }}" placeholder="08123456789" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-[13px] focus:border-sky-400 focus:ring-0 outline-none transition">
                    </div>
                </div>
            </div>

            {{-- SECTION: DENDA TELAT BAYAR --}}
            <div class="mb-3 flex items-center gap-2 text-gray-700">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                <span class="text-[11px] font-bold uppercase tracking-widest">Denda Telat Bayar</span>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm mb-7">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 mb-1.5 uppercase tracking-wider">Denda per Hari (Rp)</label>
                        <input type="number" name="late_fee_amount_per_day" value="{{ $settings['late_fee_amount_per_day'] }}" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-[13px] focus:border-sky-400 focus:ring-0 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 mb-1.5 uppercase tracking-wider">Grace Periode (Hari)</label>
                        <input type="number" name="late_fee_grace_days" value="{{ $settings['late_fee_grace_days'] }}" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-[13px] focus:border-sky-400 focus:ring-0 outline-none transition">
                    </div>
                </div>
            </div>

            {{-- SECTION: REMINDER SEWA --}}
            <div class="mb-3 flex items-center gap-2 text-gray-700">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                <span class="text-[11px] font-bold uppercase tracking-widest">Reminder Sewa</span>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm mb-7">
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 mb-1.5 uppercase tracking-wider">Reminder Sewa Habis (H-)</label>
                    <input type="text" name="rent_end_days" value="{{ $settings['rent_end_days'] }}" placeholder="7,3,1,0" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-[13px] focus:border-sky-400 focus:ring-0 outline-none transition">
                </div>
            </div>

            {{-- SECTION: TEMPLATE PESAN --}}
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-2 text-gray-700">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                    <span class="text-[11px] font-bold uppercase tracking-widest">Template Pesan</span>
                </div>
                <div class="text-[9.5px] font-bold text-gray-400">
                    Variabel: {nama} {kamar} {invoice} {tanggal_habis} {telat_hari} {denda} {total} {lantai} {kamar_mandi} {harga}
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                {{-- Template Sewa Akan Habis --}}
                <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                    <div class="flex items-center gap-1.5 mb-3">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-gray-500"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                        <label class="text-[11px] font-bold text-gray-700">Template Sewa Akan Habis</label>
                    </div>
                    <textarea name="template_rent_ending" rows="5" class="w-full border border-gray-200 rounded-lg p-3 text-[12px] focus:border-sky-400 focus:ring-0 outline-none transition resize-y">{{ $settings['template_rent_ending'] }}</textarea>
                </div>

                {{-- Template Telat Bayar --}}
                <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                    <div class="flex items-center gap-1.5 mb-3">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-gray-500"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        <label class="text-[11px] font-bold text-gray-700">Template Telat Bayar</label>
                    </div>
                    <textarea name="template_late_payment" rows="5" class="w-full border border-gray-200 rounded-lg p-3 text-[12px] focus:border-sky-400 focus:ring-0 outline-none transition resize-y">{{ $settings['template_late_payment'] }}</textarea>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                {{-- Template Kamar Kosong --}}
                <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                    <div class="flex items-center gap-1.5 mb-3">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-gray-500"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                        <label class="text-[11px] font-bold text-gray-700">Template Kamar Kosong</label>
                    </div>
                    <textarea name="template_empty_room" rows="5" class="w-full border border-gray-200 rounded-lg p-3 text-[12px] focus:border-sky-400 focus:ring-0 outline-none transition resize-y">{{ $settings['template_empty_room'] ?? '' }}</textarea>
                </div>
                
                {{-- Tombol Aksi Simpan & Reset --}}
                <div class="flex items-end justify-end pb-5">
                    <div class="flex items-center gap-3 w-full md:w-auto">
                        <button type="reset" class="px-7 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-lg text-[13px] font-bold transition w-full md:w-auto">
                            Reset
                        </button>
                        <button type="submit" class="px-7 py-2.5 bg-[#0ea5e9] hover:bg-sky-600 text-white rounded-lg text-[13px] font-bold transition w-full md:w-auto">
                            Simpan
                        </button>
                    </div>
                </div>
            </div>
        </form>

        {{-- SECTION: TEST KIRIM FONNTE --}}
        <div class="mb-3 mt-10 flex items-center gap-2 text-gray-700">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
            <span class="text-[11px] font-bold uppercase tracking-widest">Test Kirim Fonnte</span>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm mb-10">
            <form method="POST" action="{{ route('admin.notification-settings.test-fonnte') }}">
                @csrf
                <div class="flex flex-col md:flex-row items-end gap-4">
                    <div class="flex-1 w-full">
                        <label class="block text-[10px] font-bold text-gray-500 mb-1.5 uppercase tracking-wider">Nomor WhatsApp</label>
                        <input type="text" name="test_phone" placeholder="08123456789" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-[13px] focus:border-sky-400 focus:ring-0 outline-none transition">
                    </div>
                    <button type="submit" class="px-6 py-2 bg-[#0ea5e9] hover:bg-sky-600 text-white rounded-lg text-[13px] font-bold transition w-full md:w-auto whitespace-nowrap">
                        Kirim Test
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection