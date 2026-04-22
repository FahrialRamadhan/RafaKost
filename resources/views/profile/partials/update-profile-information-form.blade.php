<section class="space-y-6">
    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('patch')

    {{-- FOTO --}}
  <div class="flex items-center border-b pb-6">

    {{-- TEXT --}}
    <div class="w-2/3">
        <p class="font-semibold text-gray-700">Foto Kamu</p>
        <p class="text-sm text-gray-500">Foto ini akan ditampilkan sebagai profil kamu</p>
        <input type="file" name="photo" class="mt-3 block w-full text-sm text-gray-600">
        @if($user->photo)
            <div class="mt-3">
                <button type="submit"
                    name="remove_photo"
                    value="1"
                    onclick="return confirm('Yakin ingin menghapus foto profil?')"
                    class="text-sm text-red-600 hover:text-red-700 font-medium">
                    Hapus foto profil
                </button>
            </div>
        @endif
        @error('photo')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    {{-- FOTO --}}
    <div class="w-1/3 flex justify-right mr-40">
        @if($user->photo)
        <img src="{{ asset('storage/' . $user->photo) }}"
            class="w-16 h-16 rounded-full object-cover">
        @else
        <div class="w-16 h-16 bg-blue-200 rounded-full flex items-center justify-center">
            👤
        </div>
        @endif
    </div>

</div>


        {{-- NAMA --}}
        <div class="grid grid-cols-3 items-center gap-4">
    <label class="text-sm font-medium text-gray-700">Nama Lengkap</label>
    @error('name')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror

    <div class="col-span-2 relative">

        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
            <svg xmlns="http://www.w3.org/2000/svg"
                class="w-5 h-5"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M5.121 17.804A9 9 0 1118.879 17.8M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
        </span>

        <input type="text" name="name"
            value="{{ old('name', $user->name) }}"
            class="w-full bg-gray-50 border border-gray-300 rounded-lg px-4 py-3 pl-10 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />

    </div>
</div>

        {{-- EMAIL --}}
        <div class="grid grid-cols-3 items-center gap-4">
    <label class="text-sm font-medium text-gray-700">Alamat Email</label>
    @error('email')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror

    <div class="col-span-2 relative">

        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
            <svg xmlns="http://www.w3.org/2000/svg"
                class="w-5 h-5"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M3 8l9 6 9-6M4 6h16v12H4z" />
            </svg>
        </span>

        <input type="email" name="email"
            value="{{ old('email', $user->email) }}"
            class="w-full bg-gray-50 border border-gray-300 rounded-lg px-4 py-3 pl-10 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />

    </div>
</div>

        {{-- TELEPON --}}
        <div class="grid grid-cols-3 items-center gap-4">
    <label class="text-sm font-medium text-gray-700">Nomor Telepon</label>

    <div class="col-span-2 relative">

        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
            <svg xmlns="http://www.w3.org/2000/svg"
                class="w-5 h-5"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M3 5a2 2 0 012-2h2.28a2 2 0 011.94 1.515l.516 2.064a2 2 0 01-.45 1.847l-1.27 1.27a16 16 0 006.586 6.586l1.27-1.27a2 2 0 011.847-.45l2.064.516A2 2 0 0121 16.72V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
            </svg>
        </span>

        <input type="text" name="phone"
            value="{{ old('phone', $user->phone) }}"
            class="w-full bg-gray-50 border border-gray-300 rounded-lg px-4 py-3 pl-10 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />

        @error('phone')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div>
        {{-- GENDER --}}
       <div class="grid grid-cols-3 items-center gap-4">
    <label class="text-sm font-medium text-gray-700">Jenis Kelamin</label>

    <div class="col-span-2 relative">

        {{-- ICON --}}
        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
            <svg xmlns="http://www.w3.org/2000/svg"
                class="w-5 h-5"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M17 7h-6a4 4 0 100 8h6M13 7v10" />
            </svg>
        </span>

        {{-- SELECT --}}
        <select name="gender"
            class="w-full bg-gray-50 border border-gray-300 rounded-lg pl-10 pr-4 py-3 text-sm appearance-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            <option value="">Pilih</option>
            <option value="laki-laki" {{ old('gender', $user->gender) == 'laki-laki' ? 'selected' : '' }}>Laki-laki</option>
            <option value="perempuan" {{ old('gender', $user->gender) == 'perempuan' ? 'selected' : '' }}>Perempuan</option>
        </select>

        @error('gender')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div>

        {{-- PEKERJAAN --}}
        <div class="grid grid-cols-3 items-center gap-4">
    <label class="text-sm font-medium text-gray-700">Pekerjaan</label>

    <div class="col-span-2 relative">

        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
            <svg xmlns="http://www.w3.org/2000/svg"
                class="w-5 h-5"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M6 7V6a2 2 0 012-2h8a2 2 0 012 2v1M6 7h12M6 7v11a2 2 0 002 2h8a2 2 0 002-2V7" />
            </svg>
        </span>

        <input type="text" name="pekerjaan"
            value="{{ old('pekerjaan', $user->pekerjaan) }}"
            class="w-full bg-gray-50 border border-gray-300 rounded-lg px-4 py-3 pl-10 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />

        @error('pekerjaan')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror

    </div>
</div>

        {{-- ACTION --}}
        <div class="flex justify-end gap-3 pt-4">
            <button type="button"
                class="px-5 py-2 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-100">
                Batal
            </button>

            <button type="submit"
                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Simpan
            </button>
        </div>

    </form>
</section>
