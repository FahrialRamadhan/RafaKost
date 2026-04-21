<nav id="navbar" class="fixed top-0 left-0 w-full z-50 transition-all duration-300 bg-white ">
    <div class="max-w-7xl mx-auto px-10 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">

            <!-- Logo -->
            <div class="flex items-center gap-2">
                <img src="/images/logo.png" alt="Logo" class="h-[30px] w-auto">
                <span class="text-2xl font-medium tracking-wide text-blue-500 mt-2">
                    Rafa Kost
                </span>
            </div>

            <!-- Menu -->
            <div class="hidden md:flex space-x-10 text-base text-gray-700">
                <a href="{{ request()->is('/dashboard') ? '#beranda' : url('/#beranda') }}" class="hover:text-gray-500">Beranda</a>
                <a href="{{ request()->is('/dashboard') ? '#fasilitas' : url('/#fasilitas') }}" class="hover:text-gray-500">Fasilitas</a>
                <a href="{{ request()->is('/dashboard') ? '#kamar' : url('/#kamar') }}" class="hover:text-gray-500">Kamar</a>
                <a href="/maps" class="hover:text-gray-500">Maps</a>
            </div>

            <!-- Auth -->
            <div class="flex items-center">

                @guest
                    <a href="/login"
                        class="px-6 py-1 border border-gray-400 rounded text-sm text-gray-700 hover:bg-gray-200">
                        Login
                    </a>
                @endguest

                @auth
                   <div class="relative flex items-center gap-2">

    <!-- TRIGGER (ICON + NAMA) -->
    <button id="profileBtn"
        class="flex items-center gap-2 px-2 py-1 rounded-lg hover:bg-gray-100 transition">

        <!-- ICON -->
        <div class="w-10 h-10 flex items-center justify-center rounded-full border border-gray-400">
            <svg xmlns="http://www.w3.org/2000/svg" 
                class="w-6 h-6 text-gray-600" 
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M5.121 17.804A9 9 0 1118.879 17.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
        </div>

        <!-- NAMA (PINDAH KE SINI) -->
        <span class="text-sm font-medium text-gray-700">
            {{ Auth::user()->name }}
        </span>
    </button>

    <!-- DROPDOWN -->
    <div id="profileMenu"
        class="absolute right-0 top-full mt-2 w-48 bg-white shadow-lg rounded-lg z-50
                origin-top-right
                opacity-0 scale-95 pointer-events-none
                transition-all duration-200">


        <div class="px-4 py-2 border-b text-sm text-gray-700">
            {{ Auth::user()->name }}
        </div>
     

        <a href="/profile" class="block px-4 py-2 text-sm hover:bg-gray-100">
            Profile
        </a>

        <a href="#" class="block px-4 py-2 text-sm hover:bg-gray-100">
            Kost Sekarang
        </a>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="w-full text-left px-4 py-2 text-sm text-red-500 hover:bg-gray-100">
                Logout
            </button>
        </form>

    </div>

</div>
                @endauth

            </div>

        </div>
    </div>

</nav>