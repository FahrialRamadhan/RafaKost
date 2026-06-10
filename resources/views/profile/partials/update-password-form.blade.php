<section class="w-full bg-white border border-gray-100 rounded-[20px] shadow-sm p-6 md:p-8 mt-6">

    {{-- HEADER --}}
    <div class="mb-6">
        <h2 class="text-base font-bold text-gray-900 uppercase">
            UBAH PASSWORD
        </h2>
        <p class="mt-1 text-sm text-[#A0A0A0]">
            Gunakan password akun yang kuat agar akun aman
        </p>
    </div>

    <form id="passwordUpdateForm" method="post" action="{{ route('password.update') }}" class="space-y-6">
        @csrf
        @method('put')

        {{-- PASSWORD LAMA --}}
        <div>
            <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-2">
                PASSWORD LAMA
            </label>
            <div class="relative">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </span>
                
                <input
                    type="password"
                    name="current_password"
                    autocomplete="current-password"
                    placeholder="Masukkan password lama"
                    class="w-full bg-[#FAFAFA] border border-gray-200 rounded-xl pl-12 pr-12 py-3 text-sm text-gray-800 focus:ring-2 focus:ring-blue-100 focus:border-[#00A3FF] outline-none transition-all"
                />

                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 cursor-pointer hover:text-gray-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                </span>
            </div>
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-1" />
        </div>

        {{-- PASSWORD BARU --}}
        <div>
            <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-2">
                PASSWORD BARU
            </label>
            <div class="relative">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </span>
                
                <input
                    type="password"
                    name="password"
                    autocomplete="new-password"
                    placeholder="Masukkan password baru"
                    class="w-full bg-[#FAFAFA] border border-gray-200 rounded-xl pl-12 pr-12 py-3 text-sm text-gray-800 focus:ring-2 focus:ring-blue-100 focus:border-[#00A3FF] outline-none transition-all"
                />

                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 cursor-pointer hover:text-gray-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                </span>
            </div>
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-1" />
        </div>

        {{-- KONFIRMASI PASSWORD --}}
        <div>
            <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-2">
                KONFIRMASI PASSWORD
            </label>
            <div class="relative">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </span>
                
                <input
                    type="password"
                    name="password_confirmation"
                    autocomplete="new-password"
                    placeholder="Konfirmasi password baru"
                    class="w-full bg-[#FAFAFA] border border-gray-200 rounded-xl pl-12 pr-12 py-3 text-sm text-gray-800 focus:ring-2 focus:ring-blue-100 focus:border-[#00A3FF] outline-none transition-all"
                />

                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 cursor-pointer hover:text-gray-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                </span>
            </div>
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-1" />
        </div>

        {{-- ACTION --}}
        <div class="flex justify-end pt-2">
            <button
                type="submit"
                class="px-7 py-2.5 bg-[#00A3FF] text-white rounded-xl font-semibold text-sm hover:bg-blue-600 transition-colors shadow-sm">
                Simpan Password
            </button>
        </div>

    </form>
</section>