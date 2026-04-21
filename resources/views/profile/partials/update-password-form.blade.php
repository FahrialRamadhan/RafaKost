<section class="space-y-6">

    {{-- HEADER --}}
    <div>
        <h2 class="text-lg font-semibold text-gray-800">
            Ubah Password
        </h2>
        <p class="mt-1 text-sm text-gray-500">
            Gunakan password yang kuat agar akun tetap aman.
        </p>
    </div>

    <form method="post" action="{{ route('password.update') }}" class="space-y-5">
        @csrf
        @method('put')

        {{-- PASSWORD LAMA --}}
        <div>
            <label class="block text-sm font-semibold text-gray-600 mb-1">
                Password Lama
            </label>
            <input
                type="password"
                name="current_password"
                class="w-full bg-gray-50 border border-gray-300 rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-1" />
        </div>

        {{-- PASSWORD BARU --}}
        <div>
            <label class="block text-sm font-semibold text-gray-600 mb-1">
                Password Baru
            </label>
            <input
                type="password"
                name="password"
                class="w-full bg-gray-50 border border-gray-300 rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-1" />
        </div>

        {{-- KONFIRMASI --}}
        <div>
            <label class="block text-sm font-semibold text-gray-600 mb-1">
                Konfirmasi Password
            </label>
            <input
                type="password"
                name="password_confirmation"
                class="w-full bg-gray-50 border border-gray-300 rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-1" />
        </div>

        {{-- ACTION --}}
        <div class="flex items-center justify-end gap-3 pt-2">

            <button
                type="submit"
                class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                Simpan
            </button>

        </div>

        {{-- SUCCESS MESSAGE --}}
        @if (session('status') === 'password-updated')
            <p class="text-sm text-green-600">
                Password berhasil diperbarui.
            </p>
        @endif

    </form>
</section>