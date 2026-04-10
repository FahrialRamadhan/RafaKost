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
                            Tempat Tinggal Nyaman, Serasa di Rumah.
                        </h2>
                    </div>

                </div>
            </div>

            <!-- RIGHT -->
            <div class="w-full md:w-1/2 flex items-center">
                <div class="w-full max-w-md">

                    <!-- HEADER -->
                    <div class="mb-5">
                        <img src="/images/logo.png" class="w-[40px] mb-3">

                        <h1 class="text-2xl font-bold text-gray-900">
                            Register Account
                        </h1>

                        <p class="text-sm text-gray-500 mt-2">
                            Buat akun untuk mulai menggunakan Rafa Kost
                        </p>
                    </div>

                    <!-- FORM -->
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <!-- NAME -->
                        <div>
                            <label class="text-sm text-gray-600">Full Name</label>
                            <input type="text" name="name"
                                class="w-full mt-1 px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
                        </div>

                        <!-- EMAIL -->
                        <div class="mt-3">
                            <label class="text-sm text-gray-600">Email</label>
                            <input type="email" name="email"
                                class="w-full mt-1 px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
                        </div>

                        <!-- PASSWORD -->
                        <div class="mt-3">
                            <label class="text-sm text-gray-600">Password</label>
                            <input type="password" name="password"
                                class="w-full mt-1 px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
                        </div>

                        <!-- CONFIRM PASSWORD -->
                        <div class="mt-3">
                            <label class="text-sm text-gray-600">Confirm Password</label>
                            <input type="password" name="password_confirmation"
                                class="w-full mt-1 px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
                        </div>

                        <!-- BUTTON -->
                        <button class="w-full mt-5 bg-blue-500 text-white py-2.5 rounded-lg text-sm font-medium hover:bg-blue-600">
                            Register
                        </button>

                        <!-- FOOTER -->
                        <p class="text-xs text-center text-gray-500 mt-5">
                            Already have an account?
                            <a href="{{ route('login') }}" class="text-blue-500 font-medium">Sign In</a>
                        </p>

                    </form>

                </div>
            </div>

        </div>
    </div>
</x-guest-layout>