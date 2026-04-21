<section class="space-y-6">

    {{-- HEADER --}}
    <div>
        <h2 class="text-lg font-semibold text-gray-800">
            Hapus Akun
        </h2>
        <p class="mt-1 text-sm text-gray-500">
            Akun akan dihapus permanen dan tidak bisa dikembalikan. Pastikan kamu sudah yakin.
        </p>
    </div>

    {{-- BUTTON --}}
    <button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="px-5 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition">
        Hapus Akun
    </button>

    {{-- MODAL --}}
    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-semibold text-gray-800">
                Konfirmasi Hapus Akun
            </h2>

            <p class="mt-2 text-sm text-gray-500">
                Masukkan password untuk melanjutkan penghapusan akun secara permanen.
            </p>

            {{-- INPUT --}}
            <div class="mt-5">
                <input
                    id="password"
                    name="password"
                    type="password"
                    placeholder="Password"
                    class="w-full bg-gray-50 border border-gray-300 rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-red-400 focus:border-red-400"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            {{-- ACTION --}}
            <div class="mt-6 flex justify-end gap-3">

                <button
                    type="button"
                    x-on:click="$dispatch('close')"
                    class="px-4 py-2 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-100">
                    Batal
                </button>

                <button
                    type="submit"
                    class="px-5 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">
                    Hapus
                </button>

            </div>
        </form>
    </x-modal>

</section>