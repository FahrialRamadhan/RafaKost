<section class="w-full flex-1 p-8 md:p-10 bg-white border border-gray-100 rounded-[20px] shadow-sm">
    
    {{-- 🔹 HEADER PROFILKU 🔹 --}}
    <div class="mb-8">
        <h2 class="text-[26px] font-bold text-gray-900">Profilku</h2>
        <p class="text-sm text-gray-500 mt-1">Update foto profil dan detail di sini</p>
    </div>

    <form id="profileUpdateForm" method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('patch')

        {{-- 🔹 FOTO KAMU CARD 🔹 --}}
        <div class="border border-gray-100 rounded-[20px] p-6 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.02)] flex flex-col-reverse md:flex-row items-start md:items-center justify-between gap-6 bg-white">
            
            {{-- Bagian Teks & Tombol Kiri --}}
            <div>
                <h3 class="font-bold text-gray-900 text-base">Foto Kamu</h3>
                <p class="text-sm text-gray-500 mt-1 mb-5">Foto ini akan ditampilkan sebagai profil kamu</p>
                
                {{-- Tombol Custom File Input --}}
                <div class="relative inline-block">
                    <button type="button" 
                        onclick="document.getElementById('photoInput').click()"
                        class="bg-[#00A3FF] text-white px-6 py-2.5 rounded-full text-sm font-medium flex items-center gap-2 hover:bg-blue-600 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                        </svg>
                        Ubah Foto
                    </button>
                    {{-- ✅ id ditambahkan agar bisa dipanggil JS --}}
                    <input 
                        type="file" 
                        id="photoInput"
                        name="photo" 
                        class="hidden" 
                        accept="image/png, image/jpeg"
                        onchange="previewPhoto(event)"
                    >
                </div>
                
                <p class="text-xs text-[#B0B0B0] mt-3 font-medium">JPG, PNG maksimal 2 MB.</p>
                
                @error('photo')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Bagian Avatar Kanan --}}
            <div class="relative shrink-0">
                {{-- ✅ id="profilePhotoPreview" untuk target preview JS --}}
                @if($user->photo)
                    <img 
                        id="profilePhotoPreview"
                        src="{{ asset('storage/' . $user->photo) }}" 
                        class="w-[88px] h-[88px] rounded-full border border-gray-200 object-cover"
                    >
                @else
                    {{-- ✅ Wrapper avatar default dengan id agar bisa di-swap --}}
                    <div id="profilePhotoWrapper">
                        <img 
                            id="profilePhotoPreview"
                            src=""
                            class="w-[88px] h-[88px] rounded-full border border-gray-200 object-cover hidden"
                        >
                        <div id="profilePhotoPlaceholder" class="w-[88px] h-[88px] bg-[#CDE8FF] border border-gray-800 rounded-full flex items-center justify-center">
                            <svg class="w-10 h-10 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                    </div>
                @endif
                
                {{-- Ikon Kamera Badge --}}
                <div 
                    onclick="document.getElementById('photoInput').click()"
                    class="absolute bottom-0 right-0 bg-[#00A3FF] p-1.5 rounded-full text-white border-2 border-white cursor-pointer hover:bg-blue-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        {{-- 🔹 INPUT NAMA LENGKAP 🔹 --}}
        <div class="space-y-2">
            <label class="text-sm font-semibold text-gray-700">Nama Lengkap</label>
            <div class="relative">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </span>
                {{-- ✅ oninput: cegah angka di nama --}}
                <input 
                    type="text" 
                    id="nameInput"
                    name="name" 
                    value="{{ old('name', $user->name) }}" 
                    class="w-full bg-[#FCFCFC] border border-gray-200 rounded-xl px-4 py-3 pl-12 text-sm text-gray-800 focus:ring-2 focus:ring-blue-100 focus:border-[#00A3FF] outline-none transition-all"
                    oninput="validateStringOnly(this)"
                    placeholder="Masukkan nama lengkap"
                />
            </div>
            <p id="nameError" class="text-xs text-red-500 hidden">Nama hanya boleh huruf dan spasi.</p>
            @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        {{-- 🔹 INPUT EMAIL 🔹 --}}
        <div class="space-y-2">
            <label class="text-sm font-semibold text-gray-700">Alamat Email</label>
            <div class="relative">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 8l9 6 9-6M4 6h16v12H4z"></path>
                    </svg>
                </span>
                <input 
                    type="email" 
                    name="email" 
                    value="{{ old('email', $user->email) }}" 
                    class="w-full bg-[#FCFCFC] border border-gray-200 rounded-xl px-4 py-3 pl-12 text-sm text-gray-800 focus:ring-2 focus:ring-blue-100 focus:border-[#00A3FF] outline-none transition-all" 
                />
            </div>
            @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        {{-- 🔹 INPUT NOMOR TELEPON 🔹 --}}
        <div class="space-y-2">
            <label class="text-sm font-semibold text-gray-700">Nomor Telepon</label>
            <div class="relative">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 5a2 2 0 012-2h2.28a2 2 0 011.94 1.515l.516 2.064a2 2 0 01-.45 1.847l-1.27 1.27a16 16 0 006.586 6.586l1.27-1.27a2 2 0 011.847-.45l2.064.516A2 2 0 0121 16.72V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                    </svg>
                </span>
                {{-- ✅ type="tel", inputmode="numeric", hanya angka --}}
                <input 
                    type="tel" 
                    id="phoneInput"
                    name="phone" 
                    value="{{ old('phone', $user->phone) }}" 
                    inputmode="numeric"
                    class="w-full bg-[#FCFCFC] border border-gray-200 rounded-xl px-4 py-3 pl-12 text-sm text-gray-800 focus:ring-2 focus:ring-blue-100 focus:border-[#00A3FF] outline-none transition-all"
                    oninput="validateIntegerOnly(this)"
                    placeholder="08xxxxxxxxxx"
                />
            </div>
            <p id="phoneError" class="text-xs text-red-500 hidden">Nomor telepon hanya boleh angka.</p>
            @error('phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        {{-- 🔹 PEMBERITAHUAN CARD 🔹 --}}
        <div class="border border-gray-100 rounded-[20px] p-6 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.02)] bg-white mt-8">
            <h3 class="font-bold text-gray-900 text-base">Pemberitahuan</h3>
            <p class="text-sm text-gray-500 mt-1 mb-5">Pilih cara pemberitahuan saat ada kamar kosong di Rafa Kost.</p>

            <div class="space-y-4">
                {{-- Toggle Email --}}
                <div class="border border-gray-100 bg-[#FAFAFA] rounded-xl p-4 flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-[#DDF0FF] text-[#00A3FF] rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l9 6 9-6M4 6h16v12H4z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-bold text-gray-900 text-[13px]">Email</p>
                            <p class="text-[12px] text-gray-400 font-medium">Kirim email kalau ada kamar kosong</p>
                        </div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="hidden" name="notify_empty_room_email" value="0">
                        <input type="checkbox" name="notify_empty_room_email" value="1" class="sr-only peer" {{ old('notify_empty_room_email', $user->notify_empty_room_email) ? 'checked' : '' }}>
                        <div class="w-12 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#00A3FF]"></div>
                    </label>
                </div>

                {{-- Toggle WhatsApp --}}
                <div class="border border-gray-100 bg-[#FAFAFA] rounded-xl p-4 flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-[#DDF0FF] text-[#00A3FF] rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.77 9.77 0 01-4-.83L3 20l1.35-3.6A7.55 7.55 0 013 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-bold text-gray-900 text-[13px]">WhatsApp</p>
                            <p class="text-[12px] text-gray-400 font-medium">Kirim WhatsApp kalau ada kamar kosong</p>
                        </div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="hidden" name="notify_empty_room_whatsapp" value="0">
                        <input type="checkbox" name="notify_empty_room_whatsapp" value="1" class="sr-only peer" {{ old('notify_empty_room_whatsapp', $user->notify_empty_room_whatsapp) ? 'checked' : '' }}>
                        <div class="w-12 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#00A3FF]"></div>
                    </label>
                </div>
            </div>
            
            <p class="text-[11px] text-[#A0A0A0] mt-5 font-medium">WhatsApp akan dikirim ke nomor telepon yang kamu isi di atas.</p>
        </div>

        {{-- 🔹 GRID GENDER & PEKERJAAN 🔹 --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
            
            {{-- Gender --}}
            <div class="space-y-2">
                <label class="text-[11px] uppercase font-bold text-gray-500 tracking-wider">JENIS KELAMIN</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 11c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"></path>
                        </svg>
                    </span>
                    <select name="gender" class="w-full bg-[#FCFCFC] border border-gray-200 rounded-xl pl-12 pr-10 py-3 text-sm text-gray-800 appearance-none focus:ring-2 focus:ring-blue-100 focus:border-[#00A3FF] outline-none cursor-pointer">
                        <option value="">Pilih</option>
                        <option value="laki-laki" {{ old('gender', $user->gender) == 'laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="perempuan" {{ old('gender', $user->gender) == 'perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </span>
                </div>
            </div>

            {{-- Pekerjaan --}}
            <div class="space-y-2">
                <label class="text-[11px] uppercase font-bold text-gray-500 tracking-wider">PEKERJAAN</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </span>
                    {{-- ✅ oninput: cegah angka di pekerjaan --}}
                    <input 
                        type="text" 
                        id="pekerjaanInput"
                        name="pekerjaan" 
                        value="{{ old('pekerjaan', $user->pekerjaan) }}" 
                        placeholder="Mahasiswa" 
                        class="w-full bg-[#FCFCFC] border border-gray-200 rounded-xl pl-12 pr-4 py-3 text-sm text-gray-800 focus:ring-2 focus:ring-blue-100 focus:border-[#00A3FF] outline-none transition-all"
                        oninput="validateStringOnly(this)"
                    />
                </div>
                <p id="pekerjaanError" class="text-xs text-red-500 hidden">Pekerjaan hanya boleh huruf dan spasi.</p>
            </div>

        </div>

        {{-- 🔹 ACTION BUTTONS 🔹 --}}
        <div class="flex justify-end gap-3 pt-6">
            <button type="button" onclick="window.location.reload()" class="px-7 py-2.5 bg-[#F5F5F5] text-gray-600 rounded-xl font-semibold text-sm hover:bg-gray-200 transition-colors">
                Batal
            </button>
            <button type="submit" id="btnProfileSave" class="px-7 py-2.5 bg-[#00A3FF] text-white rounded-xl font-semibold text-sm shadow-sm hover:bg-blue-600 transition-colors">
                Simpan
            </button>
        </div>

    </form>
</section>

{{-- ✅ JAVASCRIPT VALIDASI & PREVIEW FOTO --}}
<script>
    // ─── PREVIEW FOTO LANGSUNG SETELAH DIPILIH ─────────────────────────
    function previewPhoto(event) {
        const file = event.target.files[0];
        if (!file) return;

        // Validasi ukuran maksimal 2MB
        if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran foto maksimal 2 MB.');
            event.target.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('profilePhotoPreview');
            const placeholder = document.getElementById('profilePhotoPlaceholder');

            if (preview) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
            }
            // Sembunyikan placeholder SVG jika ada
            if (placeholder) {
                placeholder.classList.add('hidden');
            }
        };
        reader.readAsDataURL(file);
    }

    // ─── VALIDASI HANYA HURUF (nama & pekerjaan) ───────────────────────
    function validateStringOnly(input) {
        // Izinkan huruf (a-z, A-Z), spasi, dan karakter aksen/unicode (nama Indonesia)
        const regex = /^[a-zA-ZÀ-ÿ\s]*$/;
        const errorId = input.id === 'nameInput' ? 'nameError' : 'pekerjaanError';
        const errorEl = document.getElementById(errorId);

        if (!regex.test(input.value)) {
            // Hapus karakter yang bukan huruf
            input.value = input.value.replace(/[^a-zA-ZÀ-ÿ\s]/g, '');
            if (errorEl) errorEl.classList.remove('hidden');
            setTimeout(() => { if (errorEl) errorEl.classList.add('hidden'); }, 2000);
        }
    }

    // ─── VALIDASI HANYA ANGKA (nomor telepon) ──────────────────────────
    function validateIntegerOnly(input) {
        const errorEl = document.getElementById('phoneError');

        // Hapus semua karakter non-digit
        const cleaned = input.value.replace(/[^0-9]/g, '');
        if (input.value !== cleaned) {
            input.value = cleaned;
            if (errorEl) errorEl.classList.remove('hidden');
            setTimeout(() => { if (errorEl) errorEl.classList.add('hidden'); }, 2000);
        }
    }

    // ─── CEGAH SUBMIT JIKA ADA VALIDASI GAGAL ──────────────────────────
    document.getElementById('profileUpdateForm').addEventListener('submit', function(e) {
        const name = document.getElementById('nameInput').value.trim();
        const phone = document.getElementById('phoneInput').value.trim();
        const pekerjaan = document.getElementById('pekerjaanInput').value.trim();

        const stringRegex = /^[a-zA-ZÀ-ÿ\s]+$/;
        const intRegex = /^[0-9]+$/;

        if (name && !stringRegex.test(name)) {
            e.preventDefault();
            document.getElementById('nameError').classList.remove('hidden');
            document.getElementById('nameInput').focus();
            return;
        }

        if (phone && !intRegex.test(phone)) {
            e.preventDefault();
            document.getElementById('phoneError').classList.remove('hidden');
            document.getElementById('phoneInput').focus();
            return;
        }

        if (pekerjaan && !stringRegex.test(pekerjaan)) {
            e.preventDefault();
            document.getElementById('pekerjaanError').classList.remove('hidden');
            document.getElementById('pekerjaanInput').focus();
            return;
        }
    });
</script>