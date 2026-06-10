@extends('layouts.app')

@section('body_class', 'dashboard-user')

@section('content')
    <x-slot name="title">Home - Rafa Kost</x-slot>

<div id="beranda" class="pt-20">
    <section class="px-6 mt-10 dashboard-hero-section">
        <div class="relative w-full max-w-6xl mx-auto rounded-2xl overflow-hidden dashboard-hero-wrapper">
            <img src="{{ asset('images/benner.png') }}" class="w-full h-[720px] object-cover dashboard-hero-image">
            <div class="absolute inset-0 bg-black/30"></div>

            <div class="absolute inset-0 flex flex-col items-center justify-start text-center text-white px-4 pt-28 dashboard-hero-content">
                <!-- Title -->
                <div class="flex items-center gap-3 dashboard-hero-title">
                    <img src="{{ asset('images/secondlogo.png') }}" class="h-11 md:h-14 w-auto dashboard-hero-logo">

                    <h1 class="text-4xl md:text-5xl font-medium leading-none justify-start pt-5 dashboard-hero-heading">
                        Rafa Kost
                    </h1>
                </div>

                <p class="mt-2 text-xl md:text-4xl font-medium leading-snug dashboard-hero-subtitle">
                    Nyaman, Aman, Terjangkau
                </p>

                {{-- FORM SEARCH SIMPLE --}}
                <form id="searchKamarForm"
                      onsubmit="return cariKamarManual();"
                      class="mt-5 flex items-center bg-white rounded-full shadow-lg w-full max-w-xl p-2 relative z-10 dashboard-search-box">

                    <div class="pl-3 text-gray-400 dashboard-search-icon">
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
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari kamar mandi luar, lantai 2, kamar 12..."
                        class="flex-1 px-3 py-3 text-gray-700 outline-none border-none focus:ring-0 bg-transparent dashboard-search-input"
                    >

                    <button type="submit"
                            class="bg-blue-500 text-white px-6 py-3 rounded-full hover:bg-blue-600 transition relative z-10 dashboard-search-button">
                        Cari
                    </button>
                </form>
            </div>
        </div>
    </section>

    <section class="max-w-6xl mx-auto px-6 mt-20 dashboard-about-section">
        <div class="grid md:grid-cols-2 gap-10 items-center dashboard-about-grid">

            <!-- LEFT CONTENT -->
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <img src="{{ asset('images/frameworkpartikel.png') }}" class="w-4 h-4">
                    <p class="text-sm text-gray-500 uppercase tracking-wide">
                        Tentang Kami
                    </p>
                </div>

                <h2 class="text-2xl md:text-3xl font-semibold text-gray-800 leading-snug dashboard-section-title">
                    Kadang, tempat terbaik itu nggak perlu dicari jauh-jauh.
                </h2>

                <p class="mt-2 text-gray-600 dashboard-section-text">
                    Hunian nyaman bisa jadi lebih dekat dari yang kamu kira.
                </p>

                <p class="mt-2 text-blue-500 font-medium">
                    #RAFAKOST
                </p>

                <!-- Images -->
                <div class="flex gap-4 mt-5 dashboard-about-images">
                    <img src="{{ asset('images/koridorkamarkiri.png') }}" class="w-1/3 h-4.01 object-cover rounded-lg dashboard-about-image">
                    <img src="{{ asset('images/koridorkamarkanan.png') }}" class="w-1/3 h-4.01 object-cover rounded-lg dashboard-about-image">
                </div>
            </div>

            <!-- RIGHT CONTENT -->
            <div class="relative w-full opacity-60 mt-20 dashboard-about-right">
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

    <section class="max-w-6xl mx-auto px-6 mt-20 dashboard-facility-section">

        <!-- Header -->
        <div id="fasilitas" class="relative z-10 bg-gray-200 py-1 scroll-mt-24">
            <div class="flex items-center gap-2 text-gray-500 text-sm mb-2">
                <img src="{{ asset('images/frameworkpartikel.png') }}" class="w-4 h-4">
                <span>FASILITAS</span>
            </div>

            <h2 class="text-3xl md:text-3xl font-semibold text-gray-800 dashboard-section-title">
                Beberapa Fasilitas Rafa Kost
            </h2>

            <p class="text-gray-600 mt-2 max-w-xl dashboard-section-text">
                Nikmati berbagai fasilitas yang dirancang untuk menunjang kenyamanan dan kebutuhan harian Anda.
            </p>
        </div>

        <!-- Card Grid -->
        <div class="relative">
            <!-- SLIDER -->
            <div id="slider" class="flex gap-5 overflow-x-auto scroll-smooth no-scrollbar px-2 dashboard-facility-slider">
                <x-card-fasilitas image="{{ asset('images/listrik.jpg') }}" title="Bebas Listrik" />
                <x-card-fasilitas image="{{ asset('images/air.jpg') }}" title="Air" />
                <x-card-fasilitas image="{{ asset('images/dapur.png') }}" title="Dapur Bersama" />
                <x-card-fasilitas image="{{ asset('images/parkiran.png') }}" title="Parkiran" />
                <x-card-fasilitas image="{{ asset('images/lokasistrategis.jpg') }}" title="Lokasi Strategis" />
                <x-card-fasilitas image="{{ asset('images/wifi.jpg') }}" title="Wifi" />
                <x-card-fasilitas image="{{ asset('images/cctv.jpg') }}" title="CCTV" />
            </div>
        </div>

        <div class="flex items-center justify-center gap-6 mt-8 dashboard-facility-control">
            <!-- LEFT BUTTON -->
            <button onclick="scrollLeftFunc()"
                class="w-12 h-12 flex items-center justify-center rounded-full bg-white shadow hover:bg-gray-100 transition dashboard-slider-button">
                &#8592;
            </button>

            <!-- TEXT -->
            <span class="text-gray-700 font-medium dashboard-facility-text">
                Lihat semua fasilitas
            </span>

            <!-- RIGHT BUTTON -->
            <button onclick="scrollRightFunc()"
                class="w-12 h-12 flex items-center justify-center rounded-full bg-white shadow hover:bg-gray-100 transition dashboard-slider-button">
                &#8594;
            </button>
        </div>

        <section class="max-w-6xl mx-auto px-4 mt-20 dashboard-room-section">

            <!-- HEADER -->
            <div class="mb-8">

                <!-- Label -->
                <div id="kamar" class="flex items-center gap-2 text-sm text-gray-500 mb-2 py-1 scroll-mt-24">
                    <img src="{{ asset('images/frameworkpartikel.png') }}" class="w-4 h-4">
                    <span>DAFTAR KAMAR</span>
                </div>

                <!-- Title -->
                <h2 class="text-2xl md:text-3xl font-semibold text-gray-800 dashboard-section-title">
                    Ada {{ $kamars->count() }} Kamar Ditemukan di Rafa Kost
                </h2>

                <!-- Subtitle -->
                <p class="text-gray-600 mt-2 max-w-xl text-sm md:text-base dashboard-section-text">
                    Rafa Kost menyediakan total 10 kamar dengan pembagian 7 kamar mandi dalam dan 3 kamar mandi luar,
                    memberikan kenyamanan serta privasi bagi setiap penghuni.
                </p>

                {{-- LABEL HASIL SEARCH --}}
                @if(request('search'))
                    <div class="mt-4 flex flex-wrap items-center gap-2 text-sm text-gray-500">
                        <span>Hasil pencarian:</span>

                        <span class="font-semibold text-gray-800">
                            "{{ request('search') }}"
                        </span>

                        <a href="{{ route('home') }}#kamar"
                           class="px-2 py-1 rounded-full bg-gray-100 text-gray-600 text-xs font-bold hover:bg-gray-200">
                            Reset
                        </a>
                    </div>
                @endif

            </div>

            <!-- GRID CARD -->
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6 mt-6 group dashboard-room-grid">

                @forelse ($kamars as $kamar)
                    <x-card-kamar :kamar="$kamar" />
                @empty
                    @if(request('search'))
                        <div class="col-span-full bg-white rounded-2xl border border-gray-100 shadow-sm p-10 text-center dashboard-empty-card">
                            <h3 class="text-lg font-semibold text-gray-800">
                                Kamar tidak ditemukan
                            </h3>

                            <p class="text-sm text-gray-500 mt-2">
                                Coba gunakan kata kunci lain, misalnya “lantai 2”, “kamar mandi luar”, atau “kamar mandi dalam”.
                            </p>

                            <a href="{{ route('home') }}#kamar"
                               class="inline-block mt-5 bg-blue-500 text-white px-5 py-2.5 rounded-full text-sm font-semibold hover:bg-blue-600">
                                Reset Pencarian
                            </a>
                        </div>
                    @endif
                @endforelse

            </div>

        </section>

        <section class="max-w-6xl mx-auto px-4 mt-24 text-center dashboard-testimonial-section">

            <h2 class="text-2xl md:text-3xl font-semibold dashboard-section-title">
                Apa Kata <span class="text-blue-500">#Penghuni</span>
            </h2>

            <p class="text-gray-500 mt-2 text-sm md:text-base dashboard-section-text">
                Setiap penghuni punya cerita pengalaman mereka menemukan kost terbaik
            </p>

            <!-- SLIDER -->
            <div class="relative mt-10 dashboard-testimonial-wrapper">

                <!-- LEFT -->
                <button onclick="prevTesti()"
                    class="absolute left-0 top-1/2 -translate-y-1/2 w-10 h-10 bg-black text-white rounded-full z-10 dashboard-testi-button dashboard-testi-left">
                    ←
                </button>

                <!-- SLIDE -->
                <div class="overflow-hidden">
                    <div id="testiSlider" class="flex transition-all duration-500">

                        @forelse($testimonials as $testimonial)
                            <div class="min-w-full flex justify-center dashboard-testi-slide">
                                <x-testimonial-card
                                    :text="$testimonial->message"
                                    :name="optional($testimonial->user)->name ?? 'Pengguna Rafa Kost'"
                                    role="Penghuni Rafa Kost"
                                />
                            </div>
                        @empty
                            <div class="min-w-full flex justify-center dashboard-testi-slide">
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
                    class="absolute right-0 top-1/2 -translate-y-1/2 w-10 h-10 bg-black text-white rounded-full z-10 dashboard-testi-button dashboard-testi-right">
                    →
                </button>

            </div>

        </section>
    </section>
</div>

@php
    $showTestimonialPopup = false;
    $testimonialBooking = null;

    if (auth()->check()) {
        $testimonialBooking = \App\Models\Booking::where('user_id', auth()->id())
            ->where('payment_status', 'paid')
            ->latest('paid_at')
            ->first();

        if ($testimonialBooking) {
            $hasTestimonial = \App\Models\Testimonial::where('user_id', auth()->id())
                ->where('booking_id', $testimonialBooking->id)
                ->exists();

            $showTestimonialPopup = ! $hasTestimonial;
        }
    }
@endphp

@if($showTestimonialPopup && $testimonialBooking)
    <div id="testimonialPopup" class="rating-overlay">
        <div class="rating-modal">

            {{-- HEADER --}}
            <div class="rating-header">
                <div class="rating-header-text">
                    <h3>
                        Bagaimana kesan<br>
                        kamu tinggal di Rafa<br>
                        Kost?
                    </h3>

                    <p>
                        Ceritakan pengalamanmu selama menyewa<br>
                        kamar di Rafa Kost.
                    </p>
                </div>

                <img src="{{ asset('images/teestimonilogo.png') }}"
                     alt="Rating Decoration"
                     class="rating-header-image">
            </div>

            {{-- BODY --}}
            <form method="POST" action="{{ route('testimonials.store') }}" class="rating-body">
                @csrf

                {{-- BOOKING INFO --}}
                <div class="rating-info-box">
                    <div class="rating-info-row">
                        <div class="rating-info-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 12h6m-6 4h6M7 3h7l5 5v13a1 1 0 01-1 1H7a1 1 0 01-1-1V4a1 1 0 011-1z" />
                            </svg>
                        </div>

                        <div>
                            <p class="rating-info-label">NO INVOICE</p>
                            <p class="rating-info-value">{{ $testimonialBooking->invoice }}</p>
                        </div>
                    </div>

                    @if($testimonialBooking->kamar)
                        <div class="rating-info-row">
                            <div class="rating-info-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                     viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M3 21h18M6 21V5a2 2 0 012-2h8a2 2 0 012 2v16M10 12h.01" />
                                </svg>
                            </div>

                            <div>
                                <p class="rating-info-label">KAMAR</p>
                                <p class="rating-info-value">{{ $testimonialBooking->kamar->nama }}</p>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- RATING --}}
                <div class="rating-field">
                    <label>Rating</label>

                    <select name="rating" required>
                        <option value="5">☆☆☆☆☆ Sangat Puas</option>
                        <option value="4">☆☆☆☆ Puas</option>
                        <option value="3">☆☆☆ Cukup</option>
                        <option value="2">☆☆ Kurang</option>
                        <option value="1">☆ Tidak Puas</option>
                    </select>

                    @error('rating')
                        <p class="rating-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- TESTIMONI --}}
                <div class="rating-field">
                    <label>Kesan/Testimoni</label>

                    <textarea name="message"
                              rows="4"
                              required
                              minlength="10"
                              maxlength="500"
                              placeholder="Ceritakan pengalaman terbaikmu selama tinggal di Rafa Kost... Contoh: Kostnya nyaman, fasilitas lengkap, lingkungan aman, dan lokasi strategis."></textarea>

                    @error('message')
                        <p class="rating-error">{{ $message }}</p>
                    @enderror
                </div>

                <p class="rating-note">
                    Testimoni kamu akan langsung tampil di bagian Apa Kata Penghuni.
                </p>

                {{-- BUTTON --}}
                <div class="rating-actions">
                    <button type="submit" class="rating-submit">
                        Kirim
                    </button>

                    <button type="button" onclick="closeTestimonialPopup()" class="rating-cancel">
                        Nanti Saja
                    </button>
                </div>
            </form>
        </div>
    </div>

    <style>
        .rating-overlay {
            position: fixed;
            inset: 0;
            z-index: 99999;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(0, 0, 0, 0.72);
            padding: 32px 16px;
        }

        .rating-modal {
            width: 100%;
            max-width: 920px;
            max-height: 92vh;
            background: #ffffff;
            border-radius: 28px;
            overflow: hidden;
            box-shadow: 0 28px 80px rgba(0, 0, 0, 0.35);
            display: flex;
            flex-direction: column;
        }

        .rating-header {
            position: relative;
            min-height: 260px;
            background: #000000;
            color: #ffffff;
            overflow: hidden;
            flex-shrink: 0;
        }

        .rating-header-text {
            position: relative;
            z-index: 2;
            padding: 54px 64px;
            max-width: 470px;
        }

        .rating-header-text h3 {
            margin: 0;
            font-size: 36px;
            line-height: 0.98;
            font-weight: 800;
            letter-spacing: -0.8px;
        }

        .rating-header-text p {
            margin-top: 18px;
            font-size: 15px;
            line-height: 1.45;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.95);
        }

        .rating-header-image {
            position: absolute;
            right: 0;
            top: 0;
            height: 100%;
            width: 48%;
            object-fit: cover;
            object-position: right center;
            z-index: 1;
        }

        .rating-body {
            padding: 32px 34px 30px;
            overflow-y: auto;
        }

        .rating-info-box {
            border: 1px solid #d9d9d9;
            border-radius: 12px;
            padding: 24px 34px;
            margin-bottom: 20px;
        }

        .rating-info-row {
            display: flex;
            align-items: flex-start;
            gap: 18px;
        }

        .rating-info-row + .rating-info-row {
            margin-top: 16px;
        }

        .rating-info-icon {
            width: 28px;
            height: 28px;
            color: #111111;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .rating-info-icon svg {
            width: 24px;
            height: 24px;
        }

        .rating-info-label {
            margin: 0;
            font-size: 10px;
            line-height: 1;
            font-weight: 800;
            color: #555555;
            letter-spacing: 0.2px;
        }

        .rating-info-value {
            margin: 4px 0 0;
            font-size: 15px;
            line-height: 1.1;
            font-weight: 800;
            color: #000000;
        }

        .rating-field {
            margin-bottom: 16px;
        }

        .rating-field label {
            display: block;
            margin-bottom: 7px;
            font-size: 11px;
            font-weight: 700;
            color: #333333;
        }

        .rating-field select,
        .rating-field textarea {
            width: 100%;
            border: 1px solid #d7d7d7;
            border-radius: 6px;
            background: #f9fafb;
            color: #222222;
            font-size: 14px;
            outline: none;
        }

        .rating-field select {
            height: 56px;
            padding: 0 14px;
        }

        .rating-field textarea {
            min-height: 92px;
            padding: 14px;
            resize: none;
        }

        .rating-field select:focus,
        .rating-field textarea:focus {
            border-color: #0ea5e9;
            box-shadow: 0 0 0 2px rgba(14, 165, 233, 0.18);
        }

        .rating-note {
            margin: 4px 0 26px;
            font-size: 11px;
            color: #555555;
        }

        .rating-actions {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .rating-submit,
        .rating-cancel {
            width: 100%;
            height: 42px;
            border-radius: 6px;
            font-size: 15px;
            font-weight: 700;
            transition: 0.2s;
        }

        .rating-submit {
            background: #0ea5e9;
            color: #ffffff;
            border: 0;
        }

        .rating-submit:hover {
            background: #0284c7;
        }

        .rating-cancel {
            background: #ffffff;
            color: #555555;
            border: 1px solid #d7d7d7;
        }

        .rating-cancel:hover {
            background: #f5f5f5;
        }

        .rating-error {
            margin-top: 6px;
            font-size: 12px;
            color: #dc2626;
        }

        @media (max-width: 640px) {
            .rating-overlay {
                align-items: flex-start;
                padding: 20px 12px;
            }

            .rating-modal {
                max-height: 94vh;
                border-radius: 22px;
            }

            .rating-header {
                min-height: 230px;
            }

            .rating-header-text {
                padding: 38px 24px;
                max-width: 285px;
            }

            .rating-header-text h3 {
                font-size: 28px;
                line-height: 1.02;
            }

            .rating-header-text p {
                font-size: 12px;
                line-height: 1.45;
                margin-top: 14px;
            }

            .rating-header-image {
                width: 54%;
                opacity: 0.95;
            }

            .rating-body {
                padding: 22px 20px 24px;
            }

            .rating-info-box {
                padding: 18px;
            }

            .rating-field select {
                height: 50px;
            }

            .rating-field textarea {
                min-height: 90px;
            }
        }
    </style>

    <script>
        function closeTestimonialPopup() {
            const popup = document.getElementById('testimonialPopup');

            if (popup) {
                popup.remove();
            }
        }
    </script>
@endif

@include('layouts.footer')

<style>
    /*
    |--------------------------------------------------------------------------
    | Responsive khusus mobile
    |--------------------------------------------------------------------------
    | Desktop/web tetap memakai layout asli.
    */

    @media (max-width: 640px) {
        #beranda {
            padding-top: 70px !important;
        }

        .dashboard-hero-section {
            padding-left: 16px !important;
            padding-right: 16px !important;
            margin-top: 20px !important;
        }

        .dashboard-hero-wrapper {
            border-radius: 18px !important;
        }

        .dashboard-hero-image {
            height: 520px !important;
        }

        .dashboard-hero-content {
            padding-top: 72px !important;
            padding-left: 18px !important;
            padding-right: 18px !important;
        }

        .dashboard-hero-title {
            gap: 10px !important;
        }

        .dashboard-hero-logo {
            height: 42px !important;
        }

        .dashboard-hero-heading {
            font-size: 34px !important;
            padding-top: 14px !important;
        }

        .dashboard-hero-subtitle {
            font-size: 22px !important;
            line-height: 1.3 !important;
        }

        .dashboard-search-box {
            max-width: 100% !important;
            border-radius: 18px !important;
            padding: 8px !important;
            align-items: stretch !important;
        }

        .dashboard-search-input {
            min-width: 0 !important;
            font-size: 13px !important;
            padding: 10px 8px !important;
        }

        .dashboard-search-button {
            padding: 10px 16px !important;
            font-size: 13px !important;
            border-radius: 14px !important;
            white-space: nowrap !important;
        }

        .dashboard-search-icon {
            padding-left: 8px !important;
            display: flex !important;
            align-items: center !important;
        }

        .dashboard-about-section,
        .dashboard-facility-section,
        .dashboard-room-section,
        .dashboard-testimonial-section {
            padding-left: 16px !important;
            padding-right: 16px !important;
        }

        .dashboard-about-section {
            margin-top: 56px !important;
        }

        .dashboard-about-grid {
            gap: 24px !important;
        }

        .dashboard-section-title {
            font-size: 24px !important;
            line-height: 1.3 !important;
        }

        .dashboard-section-text {
            font-size: 14px !important;
            line-height: 1.6 !important;
        }

        .dashboard-about-images {
            gap: 12px !important;
        }

        .dashboard-about-image {
            width: 50% !important;
            min-height: 120px !important;
            object-fit: cover !important;
        }

        .dashboard-about-right {
            margin-top: 12px !important;
        }

        .dashboard-facility-section {
            margin-top: 56px !important;
        }

        .dashboard-facility-slider {
            gap: 14px !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
        }

        .dashboard-facility-control {
            gap: 14px !important;
            margin-top: 24px !important;
        }

        .dashboard-slider-button {
            width: 42px !important;
            height: 42px !important;
            flex-shrink: 0 !important;
        }

        .dashboard-facility-text {
            font-size: 14px !important;
            text-align: center !important;
        }

        .dashboard-room-section {
            margin-top: 56px !important;
        }

        .dashboard-room-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
            gap: 14px !important;
        }

        .dashboard-empty-card {
            padding: 28px 18px !important;
        }

        .dashboard-testimonial-section {
            margin-top: 64px !important;
        }

        .dashboard-testimonial-wrapper {
            margin-top: 28px !important;
        }

        .dashboard-testi-slide {
            padding-left: 28px !important;
            padding-right: 28px !important;
        }

        .dashboard-testi-button {
            width: 34px !important;
            height: 34px !important;
            font-size: 14px !important;
        }

        .dashboard-testi-left {
            left: -4px !important;
        }

        .dashboard-testi-right {
            right: -4px !important;
        }
    }

    @media (max-width: 380px) {
        .dashboard-hero-image {
            height: 480px !important;
        }

        .dashboard-hero-heading {
            font-size: 30px !important;
        }

        .dashboard-hero-subtitle {
            font-size: 19px !important;
        }

        .dashboard-search-button {
            padding-left: 12px !important;
            padding-right: 12px !important;
        }

        .dashboard-room-grid {
            gap: 12px !important;
        }
    }
</style>

<script>
    function cariKamarManual() {
        const input = document.getElementById('searchKamarInput');
        const keyword = input ? input.value.trim() : '';

        const baseUrl = "{{ url('/') }}";

        if (keyword.length > 0) {
            window.location.assign(baseUrl + '?search=' + encodeURIComponent(keyword) + '#kamar');
        } else {
            window.location.assign(baseUrl + '#kamar');
        }

        return false;
    }

    document.addEventListener('DOMContentLoaded', function () {
        const input = document.getElementById('searchKamarInput');
        const button = document.getElementById('searchKamarButton');

        if (input) {
            input.addEventListener('keydown', function (event) {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    cariKamarManual();
                }
            });
        }

        if (button) {
            button.addEventListener('click', function (event) {
                event.preventDefault();
                cariKamarManual();
            });
        }

        @if(request('search'))
            const kamarSection = document.getElementById('kamar');

            if (kamarSection) {
                kamarSection.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        @endif
    });
</script>

<script>
    let testiIndex = 0;

    function updateTestiSlider() {
        const slider = document.getElementById('testiSlider');

        if (!slider) return;

        const slides = slider.children;
        const totalSlides = slides.length;

        if (totalSlides <= 0) return;

        if (testiIndex < 0) {
            testiIndex = totalSlides - 1;
        }

        if (testiIndex >= totalSlides) {
            testiIndex = 0;
        }

        slider.style.transform = `translateX(-${testiIndex * 100}%)`;
    }

    function nextTesti() {
        testiIndex++;
        updateTestiSlider();
    }

    function prevTesti() {
        testiIndex--;
        updateTestiSlider();
    }

    document.addEventListener('DOMContentLoaded', function () {
        updateTestiSlider();
    });
</script>
@endsection