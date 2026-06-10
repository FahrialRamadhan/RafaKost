@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#e9ecef] py-8 px-4">
    <div class="max-w-7xl mx-auto">

       {{-- Hero Banner --}}
<div class="relative overflow-hidden rounded-2xl mb-8"
     style="background-image: url('{{ asset('images/frame.png') }}'); background-size: cover; background-position: center; background-repeat: no-repeat;">

    <div class="relative px-8 py-10 md:py-12">
        <h1 class="text-3xl md:text-4xl font-bold text-white mb-2">
            Verifikasi Identitas
        </h1>
        <p class="text-sm md:text-base mb-5 max-w-2xl" style="color: rgba(255,255,255,0.75);">
            Kelola dan tinjau dokumen verifikasi identitas pengguna. Pastikan kevalidan data yang dikirim untuk menjaga keamanan dan kepatuhan sistem.
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

        {{-- Ringkasan Status --}}
        <div class="flex items-center gap-2 mb-4">
            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <h2 class="font-bold text-gray-700">Ringkasan Status</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-2xl p-6 shadow-sm border-none">
                <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center mb-4 text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                </div>
                <div class="text-sm font-semibold text-gray-500">Unverified</div>
                <div class="text-3xl font-bold text-gray-800">{{ $stats['unverified'] }}</div>
                <div class="text-xs text-gray-400 mt-1">Belum kirim berkas</div>
            </div>

            <div class="bg-white rounded-2xl p-6 shadow-sm border-none">
                <div class="w-10 h-10 bg-yellow-50 rounded-lg flex items-center justify-center mb-4 text-yellow-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <div class="text-sm font-semibold text-gray-500">Pending</div>
                <div class="text-3xl font-bold text-yellow-600">{{ $stats['pending'] }}</div>
                <div class="text-xs text-gray-400 mt-1">Menunggu review</div>
            </div>

            <div class="bg-white rounded-2xl p-6 shadow-sm border-none">
                <div class="w-10 h-10 bg-green-50 rounded-lg flex items-center justify-center mb-4 text-green-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" /></svg>
                </div>
                <div class="text-sm font-semibold text-gray-500">Approved</div>
                <div class="text-3xl font-bold text-green-600">{{ $stats['approved'] }}</div>
                <div class="text-xs text-gray-400 mt-1">Sudah terverifikasi</div>
            </div>

            <div class="bg-white rounded-2xl p-6 shadow-sm border-none">
                <div class="w-10 h-10 bg-red-50 rounded-lg flex items-center justify-center mb-4 text-red-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" /></svg>
                </div>
                <div class="text-sm font-semibold text-gray-500">Rejected</div>
                <div class="text-3xl font-bold text-red-600">{{ $stats['rejected'] }}</div>
                <div class="text-xs text-gray-400 mt-1">Gagal verifikasi</div>
            </div>
        </div>

        {{-- Filter & Table --}}
        <div class="bg-white rounded-2xl shadow-sm border-none overflow-hidden">
            <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                <form method="GET" action="{{ route('admin.identity-verifications.index') }}" class="flex flex-wrap gap-4">
                    <div class="flex-1 min-w-[300px]">
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Cari user..." 
                               class="w-full border-gray-200 rounded-xl px-4 py-2 text-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <select name="status" class="border-gray-200 rounded-xl px-7 py-2 text-sm focus:ring-blue-500">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                    </select>
                    <button class="bg-gray-800 text-white px-6 py-2 rounded-xl text-sm font-bold hover:bg-black transition">
                        Filter
                    </button>
                </form>
            </div>

            <div class="overflow-x-auto">
    <table class="w-full text-sm text-left">
        <thead class="bg-black text-white uppercase text-[10px] tracking-wider">
            <tr>
                <th class="px-6 py-4 font-bold">Informasi User</th>
                <th class="px-6 py-4 font-bold">Status</th>
                <th class="px-6 py-4 font-bold">Dokumen</th>
                <th class="px-6 py-4 font-bold">Waktu Submit</th>
                <th class="px-6 py-4 font-bold text-right">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($users as $user)
            <tr class="hover:bg-gray-50 transition">
                <td class="px-6 py-4">
                    <div class="font-bold text-gray-900">{{ $user->name }}</div>
                    <div class="text-xs text-gray-500">{{ $user->email }}</div>
                </td>
                <td class="px-6 py-4">
                    @php
                        $status = strtolower(trim($user->identity_status ?? 'unverified'));

                        $statusStyles = [
                            'approved'   => 'background-color:#dcfce7; color:#15803d;',
                            'pending'    => 'background-color:#fef9c3; color:#a16207;',
                            'rejected'   => 'background-color:#fee2e2; color:#b91c1c;',
                            'unverified' => 'background-color:#f3f4f6; color:#4b5563;',
                        ];

                        $style = $statusStyles[$status] ?? $statusStyles['unverified'];
                    @endphp
                    <span style="{{ $style }}" class="px-3 py-1 rounded-full text-[10px] font-black uppercase">
                        {{ strtoupper($status) }}
                    </span>
                </td>
                <td class="px-6 py-4 text-xs font-medium text-gray-600">
                    @if($user->ktp_photo && $user->selfie_photo)
                        <span class="flex items-center gap-1 text-green-600">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                            </svg>
                            Lengkap
                        </span>
                    @else
                        <span class="text-gray-400">Tidak Lengkap</span>
                    @endif
                </td>
                <td class="px-6 py-4 text-gray-500 text-xs">
                    {{ $user->identity_submitted_at ? $user->identity_submitted_at->diffForHumans() : '-' }}
                </td>
                <td class="px-6 py-4 text-right">
                    <a href="{{ route('admin.identity-verifications.show', $user) }}"
                       class="inline-flex bg-black hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-xs font-bold transition shadow-sm">
                        Review
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-10 text-center text-gray-400 italic">
                    Belum ada data verifikasi untuk ditampilkan.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
            @if($users->hasPages())
                <div class="p-6 border-t border-gray-100">
                    {{ $users->links() }}
                </div>
            @endif
        </div>

    </div>
</div>
@endsection