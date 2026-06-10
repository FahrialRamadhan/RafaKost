<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gray-100 px-6">

        <div class="flex items-stretch gap-12 max-w-6xl w-full">

            <!-- LEFT -->
            <div class="hidden md:flex w-1/2">
                <div class="w-full h-full min-h-[700px] rounded-[24px] shadow-xl overflow-hidden relative">

                    <img src="/images/lorong.png"
                        class="absolute inset-0 w-full h-full object-cover">

                    <div class="absolute inset-0 bg-gradient-to-t from-white/50 via-black/50 to-transparent"></div>

                    <div class="relative z-10 p-6 flex flex-col justify-between h-full">
                        <img src="/images/secondlogo.png" class="w-10 h-auto">

                        <h2 class="text-white text-3xl font-semibold leading-snug text-start p-5">
                            Reset Password Kamu dengan Mudah.
                        </h2>
                    </div>

                </div>
            </div>

            <!-- RIGHT -->
            <div class="w-full md:w-1/2 flex items-center">
                <div class="w-full max-w-md">

                    <!-- HEADER -->
                    <div class="mb-5">
                        <img src="/images/logo.png" class="w-[40px] h-auto mb-3">

                        <h1 class="text-2xl font-bold text-gray-900">
                            Reset Password
                        </h1>

                        <p class="text-sm text-gray-500 mt-2 leading-relaxed">
                            Masukkan password baru kamu untuk melanjutkan.
                        </p>
                    </div>

                    <!-- FORM -->
                    <form method="POST" action="{{ route('password.store') }}">
                        @csrf

                        <!-- Password Reset Token -->
                        <input type="hidden" name="token" value="{{ $request->route('token') }}">

                        <!-- EMAIL -->
                        <div>
                            <label for="email" class="text-sm text-gray-600">Your email</label>

                            <input id="email"
                                type="email"
                                name="email"
                                value="{{ old('email', $request->email) }}"
                                required
                                autofocus
                                autocomplete="username"
                                class="w-full mt-1 px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">

                            @error('email')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- PASSWORD -->
                        <div class="mt-4">
                            <label for="password" class="text-sm text-gray-600">New password</label>

                            <input id="password"
                                type="password"
                                name="password"
                                required
                                autocomplete="new-password"
                                class="w-full mt-1 px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">

                            @error('password')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- CONFIRM PASSWORD -->
                        <div class="mt-4">
                            <label for="password_confirmation" class="text-sm text-gray-600">Confirm password</label>

                            <input id="password_confirmation"
                                type="password"
                                name="password_confirmation"
                                required
                                autocomplete="new-password"
                                class="w-full mt-1 px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">

                            @error('password_confirmation')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- BUTTON -->
                        <button type="submit"
                            class="w-full mt-5 bg-blue-500 text-white py-2.5 rounded-lg text-sm font-medium hover:bg-blue-600 transition">
                            Reset Password
                        </button>

                        <!-- BACK -->
                        <p class="text-xs text-center text-gray-500 mt-5">
                            Kembali ke
                            <a href="{{ route('login') }}" class="text-blue-500 font-medium">Login</a>
                        </p>

                    </form>

                </div>
            </div>

        </div>
    </div>
</x-guest-layout>