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
            <div class="hidden md:flex space-x-10 text-base text-gray-700 desktop-menu">
                <a href="{{ request()->is('/') ? '#beranda' : url('/#beranda') }}" class="hover:text-gray-500">Beranda</a>
                <a href="{{ request()->is('/') ? '#fasilitas' : url('/#fasilitas') }}" class="hover:text-gray-500">Fasilitas</a>
                <a href="{{ request()->is('/') ? '#kamar' : url('/#kamar') }}" class="hover:text-gray-500">Kamar</a>
                <a href="{{ url('/maps') }}" class="hover:text-gray-500">Maps</a>
            </div>

            <!-- Auth -->
            <div class="flex items-center space-x-3 desktop-auth">
                <a href="/login"
                    class="px-8 py-1 border border-gray-400 rounded text-base text-gray-700 hover:bg-gray-200">
                    Login
                </a>
                <a href="/register"
                    class="px-8 py-1 bg-blue-500 text-white rounded text-base hover:bg-blue-600">
                    Sign Up
                </a>
            </div>

            <!-- Hamburger Mobile -->
            <button type="button"
                    id="mobileMenuButton"
                    class="mobile-hamburger w-10 h-10 items-center justify-center rounded-lg border border-gray-300 text-gray-700 bg-white/80">
                <svg id="hamburgerIcon" xmlns="http://www.w3.org/2000/svg"
                     class="h-6 w-6"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M4 6h16M4 12h16M4 18h16" />
                </svg>

                <svg id="closeIcon" xmlns="http://www.w3.org/2000/svg"
                     class="h-6 w-6 hidden"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

        </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobileMenu" class="mobile-menu hidden px-4 pb-4">
        <div class="bg-white rounded-2xl shadow-lg p-4 space-y-2">

            <a href="{{ request()->is('/') ? '#beranda' : url('/#beranda') }}"
               class="block px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-100 mobile-menu-link">
                Beranda
            </a>

            <a href="{{ request()->is('/') ? '#fasilitas' : url('/#fasilitas') }}"
               class="block px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-100 mobile-menu-link">
                Fasilitas
            </a>

            <a href="{{ request()->is('/') ? '#kamar' : url('/#kamar') }}"
               class="block px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-100 mobile-menu-link">
                Kamar
            </a>

            <a href="{{ url('/maps') }}"
               class="block px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-100 mobile-menu-link">
                Maps
            </a>

            <div class="border-t pt-3 mt-3 grid grid-cols-2 gap-3">
                <a href="/login"
                   class="text-center px-4 py-2 border border-gray-400 rounded text-gray-700 hover:bg-gray-100">
                    Login
                </a>

                <a href="/register"
                   class="text-center px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                    Sign Up
                </a>
            </div>

        </div>
    </div>

</nav>

<style>
    .mobile-hamburger {
        display: none !important;
    }

    .mobile-menu {
        display: none !important;
    }

    @media (max-width: 640px) {
        #navbar {
            z-index: 99999 !important;
        }

        .desktop-menu,
        .desktop-auth {
            display: none !important;
        }

        .mobile-hamburger {
            display: flex !important;
        }

        .mobile-menu.is-open {
            display: block !important;
            position: fixed !important;
            top: 80px !important;
            left: 0 !important;
            right: 0 !important;
            width: 100% !important;
            z-index: 99999 !important;
            padding-left: 16px !important;
            padding-right: 16px !important;
            padding-bottom: 16px !important;
            max-height: calc(100vh - 80px) !important;
            overflow-y: auto !important;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const mobileMenuButton = document.getElementById('mobileMenuButton');
        const mobileMenu = document.getElementById('mobileMenu');
        const hamburgerIcon = document.getElementById('hamburgerIcon');
        const closeIcon = document.getElementById('closeIcon');
        const mobileMenuLinks = document.querySelectorAll('.mobile-menu-link');

        if (mobileMenuButton && mobileMenu && hamburgerIcon && closeIcon) {
            mobileMenuButton.addEventListener('click', function () {
                mobileMenu.classList.toggle('is-open');
                hamburgerIcon.classList.toggle('hidden');
                closeIcon.classList.toggle('hidden');
            });
        }

        mobileMenuLinks.forEach(function (link) {
            link.addEventListener('click', function () {
                mobileMenu.classList.remove('is-open');
                hamburgerIcon.classList.remove('hidden');
                closeIcon.classList.add('hidden');
            });
        });
    });
</script>