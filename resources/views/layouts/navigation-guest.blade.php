<nav id="navbar" class="fixed top-0 left-0 w-full z-50 transition-all duration-300">

    <div class="max-w-7xl mx-auto px-10 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">

            <!-- Logo -->
            <div class="flex items-center gap-1 w-[231px] h-[60px]">
                <img src="/images/logo.png" alt="Logo" class="h-[30px] w-auto">
                <span class="text-2xl font-medium tracking-wide text-blue-500 leading-none pt-3">
                    Rafa Kost
                </span>
            </div>

            <!-- Menu -->
            <div class="hidden md:flex space-x-10 text-base text-gray-700">
                <a href="{{ request()->is('/') ? '#beranda' : url('/#beranda') }}" class="hover:text-gray-500">Beranda</a>
                <a href="{{ request()->is('/') ? '#fasilitas' : url('/#fasilitas') }}" class="hover:text-gray-500">Fasilitas</a>
                <a href="{{ request()->is('/') ? '#kamar' : url('/#kamar') }}" class="hover:text-gray-500">Kamar</a>
                <a href="{{ url('/maps') }}" class="hover:text-gray-500">Maps</a>
            </div>

            <!-- Auth -->
            <div class="flex items-center space-x-3">
                <a href="/login"
                    class="px-8 py-1 border border-gray-400 rounded text-base text-gray-700 hover:bg-gray-200">
                    Login
                </a>
                <a href="/register"
                    class="px-8 py-1 bg-blue-500 text-white rounded text-base hover:bg-blue-600">
                    Sign Up
                </a>
            </div>

        </div>
    </div>

</nav>