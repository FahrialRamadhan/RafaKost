@extends('layouts.app')

@section('content')
{{-- Script CDN Tailwind --}}
<script src="https://cdn.tailwindcss.com"></script>

<div class="min-h-screen bg-[#f8fafc] py-8 px-6 font-sans text-gray-800">
    <div class="max-w-[1100px] mx-auto">

        {{-- Navigasi Kembali --}}
        <a href="{{ route('admin.identity-verifications.index') }}" class="inline-flex items-center gap-1.5 text-[13px] font-semibold text-gray-600 hover:text-black mb-6 transition">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M15 18l-6-6 6-6"/>
            </svg>
            Kembali
        </a>

        {{-- Header Halaman --}}
        <div class="mb-8">
            <h1 class="text-[26px] font-bold text-gray-900 leading-tight mb-1.5">Review Identitas User</h1>
            <p class="text-[13px] text-gray-500 font-medium">Periksa kelengkapan foto KTP dan Live Selfie sebelum memberikan persetujuan.</p>
        </div>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="mb-6 px-4 py-3 bg-[#ecfdf5] border border-[#6ee7b7] rounded-xl text-[13px] font-semibold text-[#065f46] flex items-center gap-2">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any() || session('error'))
            <div class="mb-6 px-4 py-3 bg-[#fef2f2] border border-[#fca5a5] rounded-xl text-[13px] font-semibold text-[#991b1b] flex items-center gap-2">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('error') ?? $errors->first() }}
            </div>
        @endif

        {{-- SECTION: DATA PENGGUNA --}}
        <div class="flex items-center gap-2 mb-4 text-gray-800">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/>
            </svg>
            <span class="text-[14px] font-bold">Data Pengguna</span>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 p-8 shadow-sm mb-10">
            {{-- Invoice --}}
            <div class="mb-7">
                <span class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1.5">INVOICE</span>
                <div class="flex items-center gap-1.5 text-gray-800">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    <span class="text-[13px] font-bold uppercase">{{ $user->latest_invoice ?? 'RK-1778643847-942' }}</span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-y-6 gap-x-4 mb-6">
                <div>
                    <span class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">NAMA PENYEWA</span>
                    <span class="text-[13px] font-bold text-gray-900">{{ $user->name ?? 'Alfin Adriansyah' }}</span>
                </div>
                <div>
                    <span class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">ALAMAT EMAIL</span>
                    <span class="text-[13px] font-bold text-gray-900">{{ $user->email ?? 'alvinadriansyah99@gmail.com' }}</span>
                </div>
                <div>
                    <span class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">NOMOR TELEPON</span>
                    <span class="text-[13px] font-bold text-gray-900">{{ $user->phone ?? '089234923742' }}</span>
                </div>
                <div>
                    <span class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1.5">STATUS IDENTITAS</span>
                    @if(strtolower($user->identity_status) === 'approved')
                        <span class="bg-[#dcfce7] text-[#16a34a] px-2.5 py-1 rounded text-[9px] font-bold tracking-widest uppercase inline-flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 bg-[#16a34a] rounded-full"></span> APPROVED
                        </span>
                    @elseif(strtolower($user->identity_status) === 'rejected')
                        <span class="bg-[#fee2e2] text-[#dc2626] px-2.5 py-1 rounded text-[9px] font-bold tracking-widest uppercase inline-flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 bg-[#dc2626] rounded-full"></span> REJECTED
                        </span>
                    @else
                        <span class="bg-[#fef9c3] text-[#ca8a04] px-2.5 py-1 rounded text-[9px] font-bold tracking-widest uppercase inline-flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 bg-[#ca8a04] rounded-full"></span> PENDING
                        </span>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-y-6 gap-x-4">
                <div>
                    <span class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">WAKTU SUBMIT</span>
                    <span class="text-[13px] font-bold text-gray-900">{{ $user->identity_submitted_at ? $user->identity_submitted_at->format('d/m/Y H:i') : '25/05/2026 01:48' }}</span>
                </div>
                <div>
                    <span class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">WAKTU VERIFIKASI</span>
                    <span class="text-[13px] font-bold text-gray-900">{{ $user->identity_verified_at ? $user->identity_verified_at->format('d/m/Y H:i') : '-' }}</span>
                </div>
            </div>
            
            @if($user->identity_rejection_reason)
                <div class="mt-6 px-4 py-3 bg-[#fef2f2] border border-[#fecaca] rounded-xl text-[12.5px] font-medium text-[#991b1b]">
                    <strong>Alasan Penolakan:</strong> {{ $user->identity_rejection_reason }}
                </div>
            @endif
        </div>

        {{-- SECTION: FOTO IDENTITAS --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            @foreach([
                ['Foto KTP', 'ktp', $user->ktp_photo, '<rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/><path d="M9 16l2 2 4-4"/>'],
                ['Live Selfie Wajah', 'selfie', $user->selfie_photo, '<circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="3"/>'],
                ['Live Selfie Wajah', 'selfie_ktp', $user->selfie_ktp_photo, '<circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="3"/>'],
            ] as [$title, $type, $photo, $svgPath])
            
            <div class="bg-white rounded-2xl border border-gray-200 p-5 shadow-sm flex flex-col">
                <div class="flex items-center gap-2 mb-4">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-700">
                        {!! $svgPath !!}
                    </svg>
                    <span class="text-[13px] font-bold text-gray-900">{{ $title }}</span>
                </div>
                
                <div class="flex-1 bg-gray-50 rounded-xl overflow-hidden flex items-center justify-center border border-gray-100 min-h-[180px]">
                    @if($photo)
                        <img src="{{ route('admin.identity-verifications.file', [$user, $type]) }}?v={{ $user->updated_at?->timestamp }}" alt="{{ $title }}" class="w-full h-full object-cover cursor-pointer hover:opacity-90 transition">
                    @else
                        <span class="text-[12px] font-medium text-gray-400">Belum diunggah</span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        {{-- SECTION: TINDAKAN ADMIN --}}
        <div class="flex items-center gap-2 mb-2 text-gray-800">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/>
            </svg>
            <span class="text-[14px] font-bold">Tindakan Admin</span>
        </div>
        <p class="text-[11px] text-gray-400 font-medium mb-6">Tentukan keputusan terhadap identitas yang diajukan oleh user</p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
            
            {{-- Approve Card --}}
            <form id="form-approve" method="POST" action="{{ route('admin.identity-verifications.approve', $user) }}" class="bg-white rounded-2xl border border-gray-200 p-6 flex flex-col shadow-sm">
                @csrf
                <div class="flex items-start gap-3.5 mb-5">
                    <div class="w-9 h-9 border border-gray-200 rounded-lg flex items-center justify-center text-gray-700 shrink-0">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    </div>
                    <div>
                        <h3 class="text-[14px] font-bold text-gray-900 leading-tight mb-1">Setujui identitas</h3>
                        <p class="text-[11px] text-gray-400 font-medium leading-relaxed">Pilih ini jika semua foto KTP dan foto selfie sudah sesuai dan valid.</p>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-xl p-5 flex-1 mb-5">
                    <h4 class="text-[12px] font-bold text-gray-800 mb-3">Yang akan terjadi :</h4>
                    <ul class="text-[11.5px] text-gray-600 font-medium space-y-2.5 pl-4 list-disc marker:text-[#0ea5e9]">
                        <li>User akan diverifikasi sebagai identitas valid</li>
                        <li>User dapat melanjutkan penggunaan sistem</li>
                        <li>Data identitas akan disimpan sebagai terverifikasi</li>
                    </ul>
                </div>

                {{-- Tombol untuk trigger Modal Approve --}}
                <button type="button" onclick="document.getElementById('modal-approve').classList.remove('hidden')" class="w-full bg-[#0ea5e9] hover:bg-sky-600 text-white font-bold text-[13px] py-3 rounded-xl transition">
                    Approve
                </button>
            </form>

            {{-- Reject Card --}}
            <form id="form-reject" method="POST" action="{{ route('admin.identity-verifications.reject', $user) }}" class="bg-white rounded-2xl border border-gray-200 p-6 flex flex-col shadow-sm">
                @csrf
                <div class="flex items-start gap-3.5 mb-5">
                    <div class="w-9 h-9 border border-gray-200 rounded-lg flex items-center justify-center text-gray-700 shrink-0">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2z"/><line x1="9" y1="9" x2="15" y2="15"/><line x1="15" y1="9" x2="9" y2="15"/></svg>
                    </div>
                    <div>
                        <h3 class="text-[14px] font-bold text-gray-900 leading-tight mb-1">Tolak identitas</h3>
                        <p class="text-[11px] text-gray-400 font-medium leading-relaxed">Berikan alasan penolakan agar user dapat memperbaikinya.</p>
                    </div>
                </div>

                <div class="flex-1 mb-5">
                    <textarea name="identity_rejection_reason" required class="w-full h-full min-h-[140px] border border-gray-200 rounded-xl p-4 text-[12px] text-gray-700 outline-none focus:border-[#0ea5e9] resize-none transition" placeholder="Contoh: Foto KTP terpotong dan buram..."></textarea>
                </div>

                {{-- Tombol untuk trigger Modal Reject --}}
                <button type="button" onclick="document.getElementById('modal-reject').classList.remove('hidden')" class="w-full bg-white border border-gray-200 hover:bg-gray-50 text-gray-600 font-bold text-[13px] py-3 rounded-xl transition">
                    Reject
                </button>
            </form>

        </div>

    </div>
</div>

{{-- MODAL APPROVE --}}
<div id="modal-approve" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    {{-- Overlay Background --}}
    <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm" onclick="document.getElementById('modal-approve').classList.add('hidden')"></div>
    
    {{-- Modal Box --}}
    <div class="relative bg-white rounded-3xl p-8 max-w-sm w-full mx-4 shadow-xl flex flex-col items-center text-center">
        {{-- Ikon Tanda Seru --}}
        <div class="w-14 h-14 border-[2.5px] border-gray-800 rounded-full flex items-center justify-center mb-5 text-gray-800">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="7" x2="12" y2="13"/><circle cx="12" cy="17" r="1"/>
            </svg>
        </div>
        
        <h3 class="text-lg font-bold text-gray-900 mb-2">Setujui identitas?</h3>
        <p class="text-sm text-gray-500 mb-8">Apakah Anda yakin ingin menyetujui user ini?</p>
        
        <div class="flex gap-3 w-full justify-center">
            <button type="button" onclick="document.getElementById('modal-approve').classList.add('hidden')" class="px-7 py-2.5 bg-white border border-gray-200 text-gray-600 rounded-lg text-sm font-bold hover:bg-gray-50 transition">
                Batal
            </button>
            <button type="button" onclick="document.getElementById('form-approve').submit()" class="px-7 py-2.5 bg-[#16a34a] text-white rounded-lg text-sm font-bold hover:bg-green-700 transition">
                Approve
            </button>
        </div>
    </div>
</div>

{{-- MODAL REJECT --}}
<div id="modal-reject" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    {{-- Overlay Background --}}
    <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm" onclick="document.getElementById('modal-reject').classList.add('hidden')"></div>
    
    {{-- Modal Box --}}
    <div class="relative bg-white rounded-3xl p-8 max-w-sm w-full mx-4 shadow-xl flex flex-col items-center text-center">
        {{-- Ikon Tanda Seru --}}
        <div class="w-14 h-14 border-[2.5px] border-gray-800 rounded-full flex items-center justify-center mb-5 text-gray-800">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="7" x2="12" y2="13"/><circle cx="12" cy="17" r="1"/>
            </svg>
        </div>
        
        <h3 class="text-lg font-bold text-gray-900 mb-2">Reject identitas?</h3>
        <p class="text-sm text-gray-500 mb-8">Apakah Anda yakin ingin menolak user ini?</p>
        
        <div class="flex gap-3 w-full justify-center">
            <button type="button" onclick="document.getElementById('modal-reject').classList.add('hidden')" class="px-7 py-2.5 bg-white border border-gray-200 text-gray-600 rounded-lg text-sm font-bold hover:bg-gray-50 transition">
                Batal
            </button>
            <button type="button" onclick="document.getElementById('form-reject').submit()" class="px-7 py-2.5 bg-[#ef4444] text-white rounded-lg text-sm font-bold hover:bg-red-600 transition">
                Reject
            </button>
        </div>
    </div>
</div>

@endsection