<nav id="navbar" class="fixed top-0 left-0 w-full z-50 transition-all duration-300 bg-white">
    <div class="max-w-7xl mx-auto px-10 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">

            {{-- LOGO --}}
            <a href="{{ url('/') }}" class="flex items-center gap-2">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-[30px] w-auto">

                <span class="text-2xl font-medium tracking-wide text-blue-500 mt-2">
                    Rafa Kost
                </span>
            </a>

            {{-- MENU --}}
            <div class="hidden md:flex space-x-10 text-base text-gray-700">
                <a href="{{ request()->is('dashboard') ? '#beranda' : url('/#beranda') }}" class="hover:text-gray-500">
                    Beranda
                </a>

                <a href="{{ request()->is('dashboard') ? '#fasilitas' : url('/#fasilitas') }}" class="hover:text-gray-500">
                    Fasilitas
                </a>

                <a href="{{ request()->is('dashboard') ? '#kamar' : url('/#kamar') }}" class="hover:text-gray-500">
                    Kamar
                </a>

                <a href="/maps" class="hover:text-gray-500">
                    Maps
                </a>
            </div>

            {{-- AUTH --}}
            <div class="flex items-center">

                @guest
                    <a href="/login"
                        class="px-6 py-1 border border-gray-400 rounded text-sm text-gray-700 hover:bg-gray-200">
                        Login
                    </a>
                @endguest

                @auth
                    <div class="rk-profile-wrap flex items-center gap-2">

                        {{-- TRIGGER --}}
                        <button id="profileBtn"
                            type="button"
                            class="flex items-center gap-2 px-2 py-1 rounded-lg hover:bg-gray-100 transition">

                            <div class="w-10 h-10 flex items-center justify-center rounded-full border border-gray-400 overflow-hidden">
                                @if(Auth::user()->photo)
                                    <img src="{{ asset('storage/' . Auth::user()->photo) }}"
                                        alt="{{ Auth::user()->name }}"
                                        class="w-full h-full object-cover">
                                @else
                                    <img src="{{ asset('images/profilenav.png') }}"
                                        alt="Profile"
                                        class="w-7 h-7 object-contain">
                                @endif
                            </div>

                            <span class="text-sm font-medium text-gray-700">
                                {{ Auth::user()->name }}
                            </span>
                        </button>

                        {{-- DROPDOWN --}}
                        <div id="profileMenu" class="rk-profile-menu is-hidden">

                            {{-- USER HEADER --}}
                            <div class="rk-profile-header">
                                <div class="rk-profile-user">
                                    @if(Auth::user()->photo)
                                        <img src="{{ asset('storage/' . Auth::user()->photo) }}"
                                            alt="{{ Auth::user()->name }}"
                                            class="rk-profile-avatar rounded-full object-cover">
                                    @else
                                        <img src="{{ asset('images/profilenav.png') }}"
                                            alt="Profile"
                                            class="rk-profile-avatar">
                                    @endif

                                    <div>
                                        <div class="rk-profile-name">
                                            {{ Auth::user()->name }}
                                        </div>

                                        <div class="rk-profile-sub">
                                            Akun Pribadi
                                        </div>
                                    </div>
                                </div>

                                {{-- STATUS CARD --}}
                              @if(Auth::user()->identity_status === 'approved')
    <div class="rk-status-card border border-green-200 bg-green-50">
        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#11ff00" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shield-check-icon lucide-shield-check"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/><path d="m9 12 2 2 4-4"/></svg>

        <div>
            <div class="rk-status-title text-green-600">
                Terverifikasi
            </div>

            <div class="rk-status-text text-green-700">
                Dokumen identitas kamu sudah disetujui.
            </div>
        </div>
    </div>
@elseif(Auth::user()->identity_status === 'pending')
    <div class="rk-status-card border border-yellow-200 bg-yellow-50">
        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#ffbb00" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shield-icon lucide-shield"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/></svg>

        <div>
            <div class="rk-status-title text-yellow-600">
                Menunggu verifikasi
            </div>

            <div class="rk-status-text text-yellow-700">
                Dokumen sedang dicek oleh admin Rafa Kost.
            </div>
        </div>
    </div>
@elseif(Auth::user()->identity_status === 'rejected')
    <div class="rk-status-card border border-red-200 bg-red-100">
        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#ff0000" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shield-alert-icon lucide-shield-alert"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/><path d="M12 8v4"/><path d="M12 16h.01"/></svg>

        <div>
            <div class="rk-status-title text-red-600">
                Verifikasi ditolak
            </div>

            <div class="rk-status-text text-red-800">
                Silakan periksa dokumen dan ajukan ulang verifikasi.
            </div>
        </div>
    </div>
@else
    <div class="rk-status-card border border-blue-200 bg-blue-50">
        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shield-icon lucide-shield"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/></svg>

        <div>
            <div class="rk-status-title text-blue-600">
                Belum verifikasi
            </div>

            <div class="rk-status-text text-blue-800">
                Upload dokumen untuk mengaktifkan fitur booking.
            </div>
        </div>
    </div>
@endif
                           {{-- PROFILE --}}
<a href="{{ route('profile.edit') }}" class="rk-menu-row">
    <div class="rk-menu-left">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" class="rk-menu-icon lucide lucide-circle-user-icon lucide-circle-user"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="10" r="3"/><path d="M7 20.662V19a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v1.662"/></svg>

        <span class="rk-menu-text">
            Profile
        </span>
    </div>

    <span class="rk-menu-arrow text-gray-900">›</span>
</a>

                            {{-- KAMAR SAYA --}}
<a href="{{ route('my-rentals.index') }}" class="rk-menu-row">
    <div class="rk-menu-left">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" class="rk-menu-icon lucide lucide-house-icon lucide-house"><path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8"/><path d="M3 10a2 2 0 0 1 .709-1.528l7-6a2 2 0 0 1 2.582 0l7 6A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>

        <span class="rk-menu-text">
            Kamar saya
        </span>
    </div>
</a>

                            <div class="rk-menu-divider"></div>

                           {{-- VERIFIKASI --}}
@if(Auth::user()->identity_status === 'approved')
    <a href="{{ route('identity-verification.create') }}" class="rk-menu-row">
        <div class="rk-menu-left">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#4d75ef" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" class="rk-menu-icon lucide lucide-circle-fading-arrow-up-icon lucide-circle-fading-arrow-up"><path d="M12 2a10 10 0 0 1 7.38 16.75"/><path d="m16 12-4-4-4 4"/><path d="M12 16V8"/><path d="M2.5 8.875a10 10 0 0 0-.5 3"/><path d="M2.83 16a10 10 0 0 0 2.43 3.4"/><path d="M4.636 5.235a10 10 0 0 1 .891-.857"/><path d="M8.644 21.42a10 10 0 0 0 7.631-.38"/></svg>

            <span class="rk-menu-text text-green-600">
                Status dokumen
            </span>
        </div>

        <span class="rk-menu-arrow text-green-600">›</span>
    </a>
@elseif(Auth::user()->identity_status === 'pending')
    <a href="{{ route('identity-verification.create') }}" class="rk-menu-row">
        <div class="rk-menu-left">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#4d75ef" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" class="rk-menu-icon lucide lucide-circle-fading-arrow-up-icon lucide-circle-fading-arrow-up"><path d="M12 2a10 10 0 0 1 7.38 16.75"/><path d="m16 12-4-4-4 4"/><path d="M12 16V8"/><path d="M2.5 8.875a10 10 0 0 0-.5 3"/><path d="M2.83 16a10 10 0 0 0 2.43 3.4"/><path d="M4.636 5.235a10 10 0 0 1 .891-.857"/><path d="M8.644 21.42a10 10 0 0 0 7.631-.38"/></svg>

            <span class="rk-menu-text text-yellow-600">
                Cek verifikasi
            </span>
        </div>

        <span class="rk-menu-arrow text-yellow-600">›</span>
    </a>
@elseif(Auth::user()->identity_status === 'rejected')
    <a href="{{ route('identity-verification.create') }}" class="rk-menu-row">
        <div class="rk-menu-left">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#4d75ef" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" class="rk-menu-icon lucide lucide-circle-fading-arrow-up-icon lucide-circle-fading-arrow-up"><path d="M12 2a10 10 0 0 1 7.38 16.75"/><path d="m16 12-4-4-4 4"/><path d="M12 16V8"/><path d="M2.5 8.875a10 10 0 0 0-.5 3"/><path d="M2.83 16a10 10 0 0 0 2.43 3.4"/><path d="M4.636 5.235a10 10 0 0 1 .891-.857"/><path d="M8.644 21.42a10 10 0 0 0 7.631-.38"/></svg>

            <span class="rk-menu-text text-sky-500">
                Upload ulang dokumen
            </span>
        </div>

        <span class="rk-menu-arrow text-sky-500">›</span>
    </a>
@else
    <a href="{{ route('identity-verification.create') }}" class="rk-menu-row">
        <div class="rk-menu-left">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#4d75ef" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" class="rk-menu-icon lucide lucide-circle-fading-arrow-up-icon lucide-circle-fading-arrow-up"><path d="M12 2a10 10 0 0 1 7.38 16.75"/><path d="m16 12-4-4-4 4"/><path d="M12 16V8"/><path d="M2.5 8.875a10 10 0 0 0-.5 3"/><path d="M2.83 16a10 10 0 0 0 2.43 3.4"/><path d="M4.636 5.235a10 10 0 0 1 .891-.857"/><path d="M8.644 21.42a10 10 0 0 0 7.631-.38"/></svg>

            <span class="rk-menu-text text-sky-500">
                Verifikasi dokumen
            </span>
        </div>

        <span class="rk-menu-arrow text-sky-500">›</span>
    </a>
@endif

                            <div class="rk-menu-divider"></div>

                           {{-- LOGOUT --}}
<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit" class="rk-menu-row logout w-full">
        <div class="rk-menu-left">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#ff0000" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" class="rk-menu-icon lucide lucide-door-open-icon lucide-door-open"><path d="M11 20H2"/><path d="M11 4.562v16.157a1 1 0 0 0 1.242.97L19 20V5.562a2 2 0 0 0-1.515-1.94l-4-1A2 2 0 0 0 11 4.561z"/><path d="M11 4H8a2 2 0 0 0-2 2v14"/><path d="M14 12h.01"/><path d="M22 20h-3"/></svg>

            <span class="rk-menu-text text-red-500">
                Log Out
            </span>
        </div>

        <span class="rk-menu-arrow text-red-500">›</span>
    </button>
</form>

                        </div>
                    </div>
                @endauth

            </div>

        </div>
    </div>
</nav>

<style>
        .rk-profile-wrap {
            position: relative;
        }

        .rk-profile-menu {
            position: absolute;
            top: calc(100% + 14px);
            right: -42px;
            width: 360px;
            background: #ffffff;
            border-radius: 18px;
            box-shadow: 0 18px 45px rgba(15, 23, 42, .18);
            overflow: hidden;
            z-index: 9999;
            transform-origin: top right;
            transition: opacity .2s ease, transform .2s ease;
        }

        .rk-profile-menu.is-hidden {
            opacity: 0;
            transform: scale(.96);
            pointer-events: none;
        }

        .rk-profile-menu.is-show {
            opacity: 1;
            transform: scale(1);
            pointer-events: auto;
        }

        .rk-profile-header {
            padding: 24px 24px 18px;
        }

        .rk-profile-user {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .rk-profile-avatar {
            width: 48px;
            height: 48px;
            object-fit: contain;
            flex-shrink: 0;
        }

        .rk-profile-name {
            font-size: 18px;
            font-weight: 700;
            line-height: 1.1;
            color: #111827;
        }

        .rk-profile-sub {
            margin-top: 3px;
            font-size: 12px;
            line-height: 1.2;
            color: #6b7280;
        }

        .rk-status-card {
            margin-top: 18px;
            display: flex;
            align-items: center;
            gap: 16px;
            width: 100%;
            border-radius: 10px;
            padding: 16px;
        }

        .rk-status-card img {
            width: 42px;
            height: 42px;
            object-fit: contain;
            flex-shrink: 0;
        }

        .rk-status-title {
            font-size: 20px;
            font-weight: 700;
            line-height: 1.1;
            margin-bottom: 4px;
            white-space: nowrap;
        }

        .rk-status-text {
            font-size: 11px;
            line-height: 1.25;
            max-width: 220px;
        }

        .rk-menu-divider {
            height: 1px;
            background: #d7d7d7;
        }

        .rk-menu-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            min-height: 62px;
            padding: 0 24px;
            transition: background .2s ease;
            text-decoration: none;
        }

        .rk-menu-left {
            display: flex;
            align-items: center;
            gap: 14px;
            min-width: 0;
        }

        .rk-menu-icon {
            width: 26px;
            height: 26px;
            object-fit: contain;
            flex-shrink: 0;
        }

        .rk-menu-text {
            font-size: 13px;
            font-weight: 500;
            color: #111827;
            white-space: nowrap;
        }

        .rk-menu-arrow {
            font-size: 24px;
            line-height: 1;
            flex-shrink: 0;
        }

        .rk-menu-row:hover {
            background: #f9fafb;
        }

        .rk-menu-row.logout:hover {
            background: #fff1f2;
        }

        @media (max-width: 640px) {
            .rk-profile-menu {
                right: -10px;
                width: 320px;
            }

            .rk-profile-header {
                padding: 20px 20px 16px;
            }

            .rk-status-card {
                gap: 12px;
                padding: 14px;
            }

            .rk-status-card img {
                width: 36px;
                height: 36px;
            }

            .rk-status-title {
                font-size: 17px;
            }

            .rk-status-text {
                max-width: 190px;
            }

            .rk-menu-row {
                padding: 0 20px;
            }
        }
    </style>

<script>
    (function () {
        function initProfileMenu() {
            const profileBtn = document.getElementById('profileBtn');
            const profileMenu = document.getElementById('profileMenu');

            if (!profileBtn || !profileMenu) return;

            // Hindari double-init
            if (profileBtn.dataset.rkInit) return;
            profileBtn.dataset.rkInit = '1';

            function openMenu() {
                profileMenu.classList.remove('is-hidden');
                profileMenu.classList.add('is-show');
            }

            function closeMenu() {
                profileMenu.classList.add('is-hidden');
                profileMenu.classList.remove('is-show');
            }

            profileBtn.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                profileMenu.classList.contains('is-hidden') ? openMenu() : closeMenu();
            });

            profileMenu.addEventListener('click', function (e) {
                e.stopPropagation();
            });

            document.addEventListener('click', closeMenu);

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') closeMenu();
            });
        }

        // Jalankan saat DOM siap (normal load)
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initProfileMenu);
        } else {
            // DOM sudah siap (misalnya partial load / Livewire / Turbo)
            initProfileMenu();
        }
    })();
</script>