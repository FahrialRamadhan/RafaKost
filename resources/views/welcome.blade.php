@extends('layouts.app')

@section('content')
    <x-slot name="title">Home - Rafa Kost</x-slot>

<div id="beranda" class="pt-20">
    <section class="px-6 mt-10 home-hero-section">
        <div class="relative w-full max-w-6xl mx-auto rounded-2xl overflow-hidden home-hero-wrapper">
            <img src="{{ asset('images/benner.png') }}" class="w-full h-[720px] object-cover home-hero-image">

            <div class="absolute inset-0 bg-black/30"></div>

            <div class="absolute inset-0 flex flex-col items-center justify-start text-center text-white px-4 pt-28 home-hero-content">
                <!-- Title -->
                <div class="flex items-center gap-3 home-hero-title">
                    <img src="{{ asset('images/secondlogo.png') }}" class="h-11 md:h-14 w-auto home-hero-logo">

                    <h1 class="text-4xl md:text-5xl font-medium leading-none justify-start pt-5 home-hero-heading">
                        Rafa Kost
                    </h1>
                </div>

                <p class="mt-2 text-xl md:text-4xl font-medium leading-snug home-hero-subtitle">
                    Nyaman, Aman, Terjangkau
                </p>

                {{-- SEARCH KAMAR --}}
                <div class="mt-5 flex items-center bg-white rounded-full shadow-lg w-full max-w-xl p-2 relative z-10 home-search-box">

                    <div class="pl-3 text-gray-400 home-search-icon">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-5 w-5"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M21 21l-4.35-4.35m1.35-5.65a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>

                    <input
                        id="searchKamarInput"
                        type="text"
                        value="{{ request('search') }}"
                        placeholder="Cari kamar mandi luar, lantai 2, kamar 12..."
                        class="flex-1 px-3 py-3 text-gray-700 outline-none border-none focus:ring-0 bg-transparent home-search-input"
                        onkeydown="if(event.key === 'Enter') cariKamarManual();"
                    >

                    <button type="button"
                            onclick="cariKamarManual()"
                            class="bg-blue-500 text-white px-6 py-3 rounded-full hover:bg-blue-600 transition relative z-10 home-search-button">
                        Cari
                    </button>
                </div>
            </div>
        </div>
    </section>

    <section class="max-w-6xl mx-auto px-6 mt-20 home-about-section">

        <div class="grid md:grid-cols-2 gap-10 items-center home-about-grid">

            <!-- LEFT CONTENT -->
            <div>

                <div class="flex items-center gap-2 mb-2">
                    <img src="{{ asset('images/frameworkpartikel.png') }}" class="w-4 h-4">
                    <p class="text-sm text-gray-500 uppercase tracking-wide">
                        Tentang Kami
                    </p>
                </div>

                <h2 class="text-2xl md:text-3xl font-semibold text-gray-800 leading-snug home-section-title">
                    Kadang, tempat terbaik itu nggak perlu dicari jauh-jauh.
                </h2>

                <p class="mt-2 text-gray-600 home-section-text">
                    Hunian nyaman bisa jadi lebih dekat dari yang kamu kira.
                </p>

                <p class="mt-2 text-blue-500 font-medium">
                    #RAFAKOST
                </p>

                <!-- Images -->
                <div class="flex gap-4 mt-5 home-about-images">

                    <img src="{{ asset('images/koridorkamarkiri.png') }}" class="w-1/3 h-4.01 object-cover rounded-lg home-about-image">

                    <img src="{{ asset('images/koridorkamarkanan.png') }}" class="w-1/3 h-4.01 object-cover rounded-lg home-about-image">

                </div>

            </div>

            <!-- RIGHT CONTENT -->
            <div class="relative w-full opacity-60 mt-20 home-about-right">

                <!-- ICON -->
                <img src="{{ asset('images/bintangpartikel.png') }}"
                    class="absolute top-[-20px] right-0 w-5 h-5 opacity-60">

                <!-- TEXT -->
                <div class="text-gray-600 leading-relaxed text-sm md:text-base max-w-xl">
                    <p>
                        <span class="text-blue-500 font-medium">Rafa Kost</span> hadir dengan fasilitas lengkap,
                        lingkungan aman, dan lokasi strategis untuk hunian nyaman tanpa ribet—
                        cocok untuk mahasiswa maupun pekerja.
                    </p>
                </div>

            </div>

        </div>

    </section>

    <section class="max-w-6xl mx-auto px-6 mt-20 home-facility-section">

        <!-- Header -->
        <div id="fasilitas" class="relative z-10 bg-gray-200 py-1 scroll-mt-24">

            <div class="flex items-center gap-2 text-gray-500 text-sm mb-2">
                <img src="{{ asset('images/frameworkpartikel.png') }}" class="w-4 h-4">
                <span>FASILITAS</span>
            </div>

            <h2 class="text-3xl md:text-3xl font-semibold text-gray-800 home-section-title">
                Beberapa Fasilitas Rafa Kost
            </h2>

            <p class="text-gray-600 mt-2 max-w-xl home-section-text">
                Nikmati berbagai fasilitas yang dirancang untuk menunjang kenyamanan dan kebutuhan harian Anda.
            </p>

        </div>

        <!-- Card Grid -->
        <div class="relative">
            <!-- SLIDER -->
            <div id="slider"
                class="flex gap-5 overflow-x-auto scroll-smooth no-scrollbar px-2 home-facility-slider">

                <x-card-fasilitas image="{{ asset('images/listrik.jpg') }}" title="Bebas Listrik" />
                <x-card-fasilitas image="{{ asset('images/air.jpg') }}" title="Air" />
                <x-card-fasilitas image="{{ asset('images/dapur.png') }}" title="Dapur Bersama" />
                <x-card-fasilitas image="{{ asset('images/parkiran.png') }}" title="Parkiran" />
                <x-card-fasilitas image="{{ asset('images/lokasistrategis.jpg') }}" title="Lokasi Strategis" />
                <x-card-fasilitas image="{{ asset('images/wifi.jpg') }}" title="Wifi" />
                <x-card-fasilitas image="{{ asset('images/cctv.jpg') }}" title="CCTV" />

            </div>

        </div>

        <div class="flex items-center justify-center gap-6 mt-8 home-facility-control">

            <!-- LEFT BUTTON -->
            <button onclick="scrollLeftFunc()"
                class="w-12 h-12 flex items-center justify-center rounded-full bg-white shadow hover:bg-gray-100 transition home-slider-button">
                &#8592;
            </button>

            <!-- TEXT -->
            <span class="text-gray-700 font-medium home-facility-text">
                Lihat semua fasilitas
            </span>

            <!-- RIGHT BUTTON -->
            <button onclick="scrollRightFunc()"
                class="w-12 h-12 flex items-center justify-center rounded-full bg-white shadow hover:bg-gray-100 transition home-slider-button">
                &#8594;
            </button>

        </div>

    </section>

    <section class="max-w-6xl mx-auto px-4 mt-20 home-room-section">

        <!-- HEADER -->
        <div class="mb-8">

            <!-- Label -->
            <div id="kamar" class="flex items-center gap-2 text-sm text-gray-500 mb-2 py-1 scroll-mt-24">
                <img src="{{ asset('images/frameworkpartikel.png') }}" class="w-4 h-4">
                <span>DAFTAR KAMAR</span>
            </div>

            <!-- Title -->
            <h2 class="text-2xl md:text-3xl font-semibold text-gray-800 home-section-title">
                Ada {{ $kamars->count() }} Kamar Kosong di Rafa Kost
            </h2>

            <!-- Subtitle -->
            <p class="text-gray-600 mt-2 max-w-xl text-sm md:text-base home-section-text">
                Rafa Kost menyediakan total 10 kamar dengan pembagian 7 kamar mandi dalam dan 3 kamar mandi luar, memberikan kenyamanan serta privasi bagi setiap penghuni.
            </p>

        </div>

        <!-- GRID CARD -->
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6 mt-6 home-room-grid">

            @foreach ($kamars as $kamar)
                <x-card-kamar :kamar="$kamar" />
            @endforeach

        </div>

    </section>

    <section class="max-w-6xl mx-auto px-4 mt-24 text-center home-testimonial-section">

        <h2 class="text-2xl md:text-3xl font-semibold home-section-title">
            Apa Kata <span class="text-blue-500">#Penghuni</span>
        </h2>

        <p class="text-gray-500 mt-2 text-sm md:text-base home-section-text">
            Setiap penghuni punya cerita pengalaman mereka menemukan kost terbaik
        </p>

        <!-- SLIDER -->
        <div class="relative mt-10 home-testimonial-wrapper">

            <!-- LEFT -->
            <button onclick="prevTesti()"
                class="absolute left-0 top-1/2 -translate-y-1/2 w-10 h-10 bg-black text-white rounded-full z-10 home-testi-button home-testi-left">
                ←
            </button>

            <!-- SLIDE -->
            <div class="overflow-hidden">
                <div id="testiSlider" class="flex transition-transform duration-500 ease-in-out">

                    @forelse($testimonials as $testimonial)
                        <div class="w-full flex-shrink-0 flex justify-center px-4 home-testi-slide">
                            <x-testimonial-card
                                :text="$testimonial->message ?? '-'"
                                :name="optional($testimonial->user)->name ?? 'Penghuni Rafa Kost'"
                                role="Penghuni Rafa Kost"
                            />
                        </div>
                    @empty
                        <div class="w-full flex-shrink-0 flex justify-center px-4 home-testi-slide">
                            <x-testimonial-card
                                text="Kosan nyaman, view bagus depan lapangan enak buat piknik"
                                name="Khasanah Uswatun"
                                role="Mahasiswa"
                            />
                        </div>
                    @endforelse

                </div>
            </div>

            <!-- RIGHT -->
            <button onclick="nextTesti()"
                class="absolute right-0 top-1/2 -translate-y-1/2 w-10 h-10 bg-black text-white rounded-full z-10 home-testi-button home-testi-right">
                →
            </button>

        </div>

    </section>

    @include('layouts.footer')
</div>

<style>
    /*
    |--------------------------------------------------------------------------
    | Responsive khusus mobile
    |--------------------------------------------------------------------------
    | Layout desktop/web tetap memakai class Tailwind asli dari kode awal.
    */

    @media (max-width: 640px) {
        #beranda {
            padding-top: 70px !important;
        }

        .home-hero-section {
            padding-left: 16px !important;
            padding-right: 16px !important;
            margin-top: 20px !important;
        }

        .home-hero-wrapper {
            border-radius: 18px !important;
        }

        .home-hero-image {
            height: 520px !important;
        }

        .home-hero-content {
            padding-top: 72px !important;
            padding-left: 18px !important;
            padding-right: 18px !important;
        }

        .home-hero-title {
            gap: 10px !important;
        }

        .home-hero-logo {
            height: 42px !important;
        }

        .home-hero-heading {
            font-size: 34px !important;
            padding-top: 14px !important;
        }

        .home-hero-subtitle {
            font-size: 22px !important;
            line-height: 1.3 !important;
        }

        .home-search-box {
            max-width: 100% !important;
            border-radius: 18px !important;
            padding: 8px !important;
            align-items: stretch !important;
        }

        .home-search-input {
            min-width: 0 !important;
            font-size: 13px !important;
            padding: 10px 8px !important;
        }

        .home-search-button {
            padding: 10px 16px !important;
            font-size: 13px !important;
            border-radius: 14px !important;
            white-space: nowrap !important;
        }

        .home-search-icon {
            padding-left: 8px !important;
            display: flex !important;
            align-items: center !important;
        }

        .home-about-section,
        .home-facility-section,
        .home-room-section,
        .home-testimonial-section {
            padding-left: 16px !important;
            padding-right: 16px !important;
        }

        .home-about-section {
            margin-top: 56px !important;
        }

        .home-about-grid {
            gap: 24px !important;
        }

        .home-section-title {
            font-size: 24px !important;
            line-height: 1.3 !important;
        }

        .home-section-text {
            font-size: 14px !important;
            line-height: 1.6 !important;
        }

        .home-about-images {
            gap: 12px !important;
        }

        .home-about-image {
            width: 50% !important;
            min-height: 120px !important;
            object-fit: cover !important;
        }

        .home-about-right {
            margin-top: 12px !important;
        }

        .home-facility-section {
            margin-top: 56px !important;
        }

        .home-facility-slider {
            gap: 14px !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
        }

        .home-facility-control {
            gap: 14px !important;
            margin-top: 24px !important;
        }

        .home-slider-button {
            width: 42px !important;
            height: 42px !important;
            flex-shrink: 0 !important;
        }

        .home-facility-text {
            font-size: 14px !important;
            text-align: center !important;
        }

        .home-room-section {
            margin-top: 56px !important;
        }

        .home-room-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
            gap: 14px !important;
        }

        .home-testimonial-section {
            margin-top: 64px !important;
        }

        .home-testimonial-wrapper {
            margin-top: 28px !important;
        }

        .home-testi-slide {
            padding-left: 28px !important;
            padding-right: 28px !important;
        }

        .home-testi-button {
            width: 34px !important;
            height: 34px !important;
            font-size: 14px !important;
        }

        .home-testi-left {
            left: -4px !important;
        }

        .home-testi-right {
            right: -4px !important;
        }
    }

    @media (max-width: 380px) {
        .home-hero-image {
            height: 480px !important;
        }

        .home-hero-heading {
            font-size: 30px !important;
        }

        .home-hero-subtitle {
            font-size: 19px !important;
        }

        .home-search-button {
            padding-left: 12px !important;
            padding-right: 12px !important;
        }

        .home-room-grid {
            gap: 12px !important;
        }
    }
</style>

<script>
    function cariKamarManual() {
        const input = document.getElementById('searchKamarInput');
        const keyword = input ? input.value.trim() : '';

        const baseUrl = window.location.pathname === '/dashboard'
            ? "{{ url('/dashboard') }}"
            : "{{ url('/') }}";

        if (keyword.length > 0) {
            window.location.href = baseUrl + "?search=" + encodeURIComponent(keyword) + "#kamar";
        } else {
            window.location.href = baseUrl + "#kamar";
        }

        return false;
    }

    @if(request('search'))
        document.addEventListener('DOMContentLoaded', function () {
            const kamarSection = document.getElementById('kamar');

            if (kamarSection) {
                kamarSection.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    @endif
</script>

<script>
    let testiIndex = 0;

    function getTestiSlides() {
        const slider = document.getElementById('testiSlider');

        if (!slider) {
            return {
                slider: null,
                total: 0,
            };
        }

        return {
            slider: slider,
            total: slider.children.length,
        };
    }

    function updateTestiSlider() {
        const data = getTestiSlides();

        if (!data.slider || data.total <= 0) return;

        if (testiIndex < 0) {
            testiIndex = data.total - 1;
        }

        if (testiIndex >= data.total) {
            testiIndex = 0;
        }

        data.slider.style.transform = 'translateX(-' + (testiIndex * 100) + '%)';
    }

    function nextTesti() {
        const data = getTestiSlides();

        if (data.total <= 1) return;

        testiIndex++;
        updateTestiSlider();
    }

    function prevTesti() {
        const data = getTestiSlides();

        if (data.total <= 1) return;

        testiIndex--;
        updateTestiSlider();
    }

    document.addEventListener('DOMContentLoaded', function () {
        updateTestiSlider();
    });
</script>

@endsection