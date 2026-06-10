@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8 px-6">
    <div class="max-w-7xl mx-auto">

		{{-- Hero Banner --}}
<div class="relative overflow-hidden rounded-2xl mb-8"
     style="background-image: url('{{ asset('images/frame.png') }}'); background-size: cover; background-position: center; background-repeat: no-repeat;">
    
    {{-- Catatan: Hapus bagian svg ini jika gambar frame.png sudah memiliki elemen gunung/segitiga bawaan --}}
    <div class="absolute inset-0 opacity-10">
        <svg class="absolute right-0 top-0 h-full" viewBox="0 0 400 300" xmlns="http://www.w3.org/2000/svg">
            <polygon points="200,50 280,250 120,250" fill="white" opacity="0.6"/>
            <polygon points="280,80 380,260 200,260" fill="white" opacity="0.4"/>
            <polygon points="100,120 180,260 30,260" fill="white" opacity="0.3"/>
        </svg>
    </div>

    <div class="relative px-8 py-10 md:py-12">
        <h1 class="text-3xl md:text-4xl font-bold text-white mb-2">
            Payment Gateway
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
		
{{-- ALERT --}}
        @if(session('success'))
            <div class="mb-6 flex items-start gap-3 p-4 bg-green-50 border border-green-200 rounded-xl">
                <svg class="w-5 h-5 text-green-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-sm text-green-700">{{ session('success') }}</p>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
                <ul class="list-disc pl-5 text-sm text-red-700">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- SECTION TITLE --}}
        <div class="flex items-center gap-2 mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-800">
                <circle cx="12" cy="12" r="4"/><path d="M12 2v2"/><path d="M12 20v2"/><path d="m4.93 4.93 1.41 1.41"/><path d="m17.66 17.66 1.41 1.41"/><path d="M2 12h2"/><path d="M20 12h2"/><path d="m6.34 17.66-1.41 1.41"/><path d="m19.07 4.93-1.41 1.41"/>
            </svg>
            <h2 class="text-lg font-bold text-gray-800">Daftar Gateway</h2>
        </div>

{{-- CARDS --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    @foreach($gateways as $gateway)
        <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">

            {{-- HEADER CARD --}}
            <div class="flex items-center justify-between pb-4 border-b border-gray-200 mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 shrink-0 rounded-lg border border-gray-200 flex items-center justify-center bg-white">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="text-gray-600">
                            <path d="M21 12V7H5a2 2 0 0 1 0-4h14v4"/><path d="M3 5v14a2 2 0 0 0 2 2h16v-5"/><path d="M18 12a2 2 0 0 0 0 4h4v-4Z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-[15px] font-bold text-gray-900 leading-tight">{{ $gateway->name }}</h3>
                        <p class="text-[10px] text-gray-400 uppercase tracking-widest font-semibold mt-0.5">{{ $gateway->code }}</p>
                    </div>
                </div>

                {{-- STATUS PILL (Aktif / Nonaktif) --}}
                @if($gateway->is_active)
                    <div class="bg-green-100 text-green-700 px-2.5 py-1 rounded-full text-[9px] font-bold tracking-widest uppercase flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 bg-green-600 rounded-full"></span> AKTIF
                    </div>
                @else
                    <div class="bg-red-100 text-red-600 px-2.5 py-1 rounded-full text-[9px] font-bold tracking-widest uppercase flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 bg-red-600 rounded-full"></span> NONAKTIF
                    </div>
                @endif
            </div>

            {{-- INFO TEXT BANNER --}}
            <div class="bg-green-100 py-2 px-4 rounded-md text-center mb-5">
                @if($gateway->code === 'cashify')
                    <p class="text-[11px] text-gray-700">
                        Jika <b class="text-gray-900">Cashify ON</b>, user hanya melihat metode pembayaran Cashify (QRIS Cashify).
                    </p>
                @endif
                @if($gateway->code === 'tokopay')
                    <p class="text-[11px] text-gray-700">
                        Jika <b class="text-gray-900">TokoPay ON</b>, user melihat semua metode TokoPay aktif: QRIS, DANA, OVO, ShopeePay, dan BNI VA.
                    </p>
                @endif
            </div>

            {{-- FORM CREDENTIAL --}}
            <form method="POST" action="{{ route('admin.payment-gateways.update', $gateway) }}" autocomplete="off">
                @csrf
                @method('PUT')

                @if($gateway->code === 'cashify')
                    {{-- Cashify License Key --}}
                    <div class="mb-4">
                        <label class="block text-[10px] font-bold text-gray-500 mb-1.5 uppercase tracking-widest">
                            CASHIFY LICENSE KEY
                        </label>
                        <div class="relative">
                            <input type="password" name="cashify_license_key"
                                   value="{{ old('cashify_license_key', $gateway->cashify_license_key) }}"
                                   autocomplete="new-password" spellcheck="false"
                                   class="w-full border border-gray-200 rounded-md pl-3 pr-10 py-2 text-[13px] text-gray-800 bg-white focus:border-blue-400 focus:ring-0 outline-none transition">
                            <button type="button" onclick="toggleSecret(this)"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 p-0.5 text-gray-400 hover:text-gray-600 transition">
                                <svg class="eye-show w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                <svg class="eye-hide w-4 h-4 hidden" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/><path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/><line x1="2" x2="22" y1="2" y2="22"/></svg>
                            </button>
                        </div>
                    </div>

                    {{-- Cashify QR ID --}}
                    <div class="mb-4">
                        <label class="block text-[10px] font-bold text-gray-500 mb-1.5 uppercase tracking-widest">
                            CASHIFY QR ID
                        </label>
                        <div class="relative">
                            <input type="password" name="cashify_qr_id"
                                   value="{{ old('cashify_qr_id', $gateway->cashify_qr_id) }}"
                                   autocomplete="new-password" spellcheck="false"
                                   class="w-full border border-gray-200 rounded-md pl-3 pr-10 py-2 text-[13px] text-gray-800 bg-white focus:border-blue-400 focus:ring-0 outline-none transition">
                            <button type="button" onclick="toggleSecret(this)"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 p-0.5 text-gray-400 hover:text-gray-600 transition">
                                <svg class="eye-show w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                <svg class="eye-hide w-4 h-4 hidden" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/><path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/><line x1="2" x2="22" y1="2" y2="22"/></svg>
                            </button>
                        </div>
                    </div>

                    {{-- Callback Cashify --}}
                    <div class="mb-5">
                        <label class="block text-[10px] font-bold text-gray-500 mb-1.5 uppercase tracking-widest">
                            CALLBACK URL
                        </label>
                        <div class="flex border border-gray-200 rounded-md overflow-hidden bg-white">
                            <input type="text" readonly value="{{ url('/payment/callback/cashify') }}"
                                   class="flex-1 min-w-0 bg-transparent pl-3 pr-3 py-2 text-[12px] text-gray-400 outline-none truncate">
                            <button type="button" onclick="copyUrl(this, '{{ url('/payment/callback/cashify') }}')"
                                    class="shrink-0 flex items-center gap-1.5 px-4 py-2 bg-gray-50 hover:bg-gray-100 text-gray-600 border-l border-gray-200 text-[12px] font-medium transition">
                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="14" height="14" x="8" y="8" rx="2" ry="2"/><path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"/></svg>
                                Salin
                            </button>
                        </div>
                    </div>
                @endif

                @if($gateway->code === 'tokopay')
                    {{-- TokoPay Merchant ID --}}
                    <div class="mb-4">
                        <label class="block text-[10px] font-bold text-gray-500 mb-1.5 uppercase tracking-widest">
                            TOKOPAY MERCHANT ID
                        </label>
                        <div class="relative">
                            <input type="password" name="tokopay_merchant_id"
                                   value="{{ old('tokopay_merchant_id', $gateway->tokopay_merchant_id) }}"
                                   autocomplete="new-password" spellcheck="false"
                                   class="w-full border border-gray-200 rounded-md pl-3 pr-10 py-2 text-[13px] text-gray-800 bg-white focus:border-blue-400 focus:ring-0 outline-none transition">
                            <button type="button" onclick="toggleSecret(this)"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 p-0.5 text-gray-400 hover:text-gray-600 transition">
                                <svg class="eye-show w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                <svg class="eye-hide w-4 h-4 hidden" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/><path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/><line x1="2" x2="22" y1="2" y2="22"/></svg>
                            </button>
                        </div>
                    </div>

                    {{-- TokoPay Secret Key --}}
                    <div class="mb-4">
                        <label class="block text-[10px] font-bold text-gray-500 mb-1.5 uppercase tracking-widest">
                            TOKOPAY SECRET KEY
                        </label>
                        <div class="relative">
                            <input type="password" name="tokopay_secret_key"
                                   value="{{ old('tokopay_secret_key', $gateway->tokopay_secret_key) }}"
                                   autocomplete="new-password" spellcheck="false"
                                   class="w-full border border-gray-200 rounded-md pl-3 pr-10 py-2 text-[13px] text-gray-800 bg-white focus:border-blue-400 focus:ring-0 outline-none transition">
                            <button type="button" onclick="toggleSecret(this)"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 p-0.5 text-gray-400 hover:text-gray-600 transition">
                                <svg class="eye-show w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                <svg class="eye-hide w-4 h-4 hidden" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/><path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/><line x1="2" x2="22" y1="2" y2="22"/></svg>
                            </button>
                        </div>
                    </div>

                    {{-- Callback TokoPay --}}
                    <div class="mb-5">
                        <label class="block text-[10px] font-bold text-gray-500 mb-1.5 uppercase tracking-widest">
                            CALLBACK URL
                        </label>
                        <div class="flex border border-gray-200 rounded-md overflow-hidden bg-white">
                            <input type="text" readonly value="{{ url('/payment/callback/tokopay') }}"
                                   class="flex-1 min-w-0 bg-transparent pl-3 pr-3 py-2 text-[12px] text-gray-400 outline-none truncate">
                            <button type="button" onclick="copyUrl(this, '{{ url('/payment/callback/tokopay') }}')"
                                    class="shrink-0 flex items-center gap-1.5 px-4 py-2 bg-gray-50 hover:bg-gray-100 text-gray-600 border-l border-gray-200 text-[12px] font-medium transition">
                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="14" height="14" x="8" y="8" rx="2" ry="2"/><path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"/></svg>
                                Salin
                            </button>
                        </div>
                    </div>
                @endif

                {{-- Button Simpan Credential (Statis dan Aktif Sesuai Referensi) --}}
                <button type="submit" class="w-full py-2.5 bg-sky-500 hover:bg-sky-600 text-white rounded-md text-[13px] font-semibold transition flex items-center justify-center gap-2 mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                    Simpan Credential
                </button>
            </form>

            {{-- TOGGLE ON/OFF --}}
            <div>
                @if($gateway->is_active)
                    <form method="POST" action="{{ route('admin.payment-gateways.deactivate', $gateway) }}">
                        @csrf
                        <button type="submit"
                                onclick="return confirm('Yakin ingin mematikan {{ $gateway->name }}?')"
                                class="w-full py-2.5 bg-gray-50 hover:bg-gray-100 text-gray-600 border border-gray-200 rounded-md text-[13px] font-semibold transition flex items-center justify-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 2v10"/><path d="M18.4 6.6a9 9 0 1 1-12.77.04"/>
                            </svg>
                            Nonaktifkan {{ $gateway->name }}
                        </button>
                    </form>
                @else
                    <form method="POST" action="{{ route('admin.payment-gateways.activate', $gateway) }}">
                        @csrf
                        <button type="submit"
                                onclick="return confirm('Aktifkan {{ $gateway->name }} sebagai gateway utama? Gateway lain akan otomatis OFF.')"
                                class="w-full py-2.5 bg-gray-50 hover:bg-gray-100 text-gray-600 border border-gray-200 rounded-md text-[13px] font-semibold transition flex items-center justify-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 2v10"/><path d="M18.4 6.6a9 9 0 1 1-12.77.04"/>
                            </svg>
                            Aktifkan {{ $gateway->name }}
                        </button>
                    </form>
                @endif
            </div>

        </div>
    @endforeach
</div>
        {{-- CATATAN --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center gap-2 mb-3">
                <span class="text-blue-500">📌</span>
                <h2 class="text-sm font-semibold text-gray-700">Catatan Penting</h2>
            </div>

            <div class="space-y-2 text-sm text-gray-600 leading-relaxed">
                <p>• Hanya satu gateway yang boleh aktif. Mengaktifkan TokoPay otomatis menonaktifkan Cashify, dan sebaliknya.</p>
                <p>• Metode pembayaran TokoPay (QRIS, DANA, OVO, ShopeePay, BNI VA) diambil dari tabel <b>payment_methods</b> dengan <code class="px-1.5 py-0.5 bg-gray-100 rounded text-xs">gateway_code</code> sesuai gateway aktif dan <code class="px-1.5 py-0.5 bg-gray-100 rounded text-xs">is_active = 1</code>.</p>
                <p>• Semua credential disimpan dalam bentuk <b>terenkripsi</b> (AES-256) di database. Akses log dicatat untuk audit.</p>
            </div>
        </div>

    </div>
</div>
<script src="https://cdn.tailwindcss.com"></script>
<script>
    function toggleSecret(btn) {
        const input = btn.previousElementSibling;
        const eyeShow = btn.querySelector('.eye-show');
        const eyeHide = btn.querySelector('.eye-hide');
        if (input.type === 'password') {
            input.type = 'text';
            eyeShow.classList.add('hidden');
            eyeHide.classList.remove('hidden');
        } else {
            input.type = 'password';
            eyeShow.classList.remove('hidden');
            eyeHide.classList.add('hidden');
        }
    }

    function copyUrl(btn, url) {
        navigator.clipboard.writeText(url).then(() => {
            const original = btn.innerText;
            btn.innerText = '✓ Disalin';
            setTimeout(() => btn.innerText = original, 1500);
        });
    }
</script>
@endsection