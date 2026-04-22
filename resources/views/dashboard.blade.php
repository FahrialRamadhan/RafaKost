@extends('layouts.app')

@section('body_class', 'dashboard-user')

@section('content')
    <x-slot name="title">Home - Rafa Kost</x-slot>
<div  id="beranda"class="pt-20">
    <section class="px-6 mt-10">
        <div class="relative w-full max-w-6xl mx-auto rounded-2xl overflow-hidden">
            <img src="{{ asset('images/benner.png') }}" class="w-full h-[720px] object-cover">
            <div class="absolute inset-0 bg-black/30"></div>
            <div class="absolute inset-0 flex flex-col items-center justify-start text-center text-white px-4 pt-28">
                <!-- Title -->
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/secondlogo.png') }}" class="h-11 md:h-14 w-auto">

                    <h1 class="text-4xl md:text-5xl font-medium leading-none justify-start pt-5">
                        Rafa Kost
                    </h1>
                </div>
                <p class="mt-2 text-xl md:text-4xl font-medium leading-snug">
                    Nyaman, Aman, Terjangkau
                </p>

                <div class="mt-5 flex items-center bg-white rounded-full shadow-lg w-full max-w-xl p-2">
            <!-- ICON -->
    <div class="pl-3 text-gray-400">
        <svg xmlns="http://www.w3.org/2000/svg"
             class="h-5 w-5"
             fill="none"
             viewBox="0 0 24 24"
             stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M21 21l-4.35-4.35m1.35-5.65a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
    </div>

  <input
        type="text"
        placeholder="Tulis tipe kamar atau nomor kamar"
        class="flex-1 px-3 py-3 text-gray-700 outline-none border-none focus:ring-0 bg-transparent"
    >

    <button
        class="bg-blue-500 text-white px-6 py-3 rounded-full hover:bg-blue-600 transition">
        Cari
    </button>

</div>
            </div>
        </div>
    </section>
    <section class="max-w-6xl mx-auto px-6 mt-20">

        <div class="grid md:grid-cols-2 gap-10 items-center">

            <!-- LEFT CONTENT -->
            <div>

                <div class="flex items-center gap-2 mb-2">
                    <img src="{{ asset('images/frameworkpartikel.png') }}" class="w-4 h-4">
                    <p class="text-sm text-gray-500 uppercase tracking-wide">
                        Tentang Kami
                    </p>
                </div>

                <h2 class="text-2xl md:text-3xl font-semibold text-gray-800 leading-snug">
                    Kadang, tempat terbaik itu nggak perlu dicari jauh-jauh.
                </h2>

                <p class="mt-2 text-gray-600">
                    Hunian nyaman bisa jadi lebih dekat dari yang kamu kira.
                </p>

                <p class="mt-2 text-blue-500 font-medium">
                    #RAFAKOST
                </p>

                <!-- Images -->
                <div class="flex gap-4 mt-5">

                    <img src="{{ asset('images/koridorkamarkiri.png') }}" class="w-1/3 h-4.01 object-cover rounded-lg">

                    <img src="{{ asset('images/koridorkamarkanan.png') }}" class="w-1/3 h-4.01 object-cover rounded-lg">

                </div>

            </div>

            <!-- RIGHT CONTENT -->
           <div class="relative w-full opacity-60 mt-20">

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

    <section class="max-w-6xl mx-auto px-6 mt-20">

        <!-- Header -->
        <div id="fasilitas" class="relative z-10 bg-gray-200 py-1 scroll-mt-24">

            <div class="flex items-center gap-2 text-gray-500 text-sm mb-2">
                <img src="{{ asset('images/frameworkpartikel.png') }}" class="w-4 h-4">
                <span>FASILITAS</span>
            </div>

            <h2 class="text-3xl md:text-3xl font-semibold text-gray-800">
                Beberapa Fasilitas Rafa Kost
            </h2>

            <p class="text-gray-600 mt-2 max-w-xl">
                Nikmati berbagai fasilitas yang dirancang untuk menunjang kenyamanan dan kebutuhan harian Anda.
            </p>

        </div>

        <!-- Card Grid -->
        <div class="relative">
    <!-- SLIDER -->
    <div id="slider"
     class="flex gap-5 overflow-x-auto scroll-smooth no-scrollbar px-2">

        <x-card-fasilitas image="{{ asset('images/listrik.jpg') }}" title="Bebas Listrik" />
        <x-card-fasilitas image="{{ asset('images/air.jpg') }}" title="Air" />
        <x-card-fasilitas image="{{ asset('images/dapur.png') }}" title="Dapur Bersama" />
        <x-card-fasilitas image="{{ asset('images/parkiran.png') }}" title="Parkiran" />
        <x-card-fasilitas image="{{ asset('images/lokasistrategis.jpg') }}" title="Lokasi Strategis" />
        <x-card-fasilitas image="{{ asset('images/wifi.jpg') }}" title="Wifi" />
        <x-card-fasilitas image="{{ asset('images/cctv.jpg') }}" title="CCTV" />


    </div>

</div>

<div class="flex items-center justify-center gap-6 mt-8">

    <!-- LEFT BUTTON -->
    <button onclick="scrollLeftFunc()"
        class="w-12 h-12 flex items-center justify-center rounded-full bg-white shadow hover:bg-gray-100 transition">
        &#8592;
    </button>

    <!-- TEXT -->
    <span class="text-gray-700 font-medium">
        Lihat semua fasilitas
    </span>

    <!-- RIGHT BUTTON -->
    <button onclick="scrollRightFunc()"
        class="w-12 h-12 flex items-center justify-center rounded-full bg-white shadow hover:bg-gray-100 transition">
        &#8594;
    </button>

</div>

    <section class="max-w-6xl mx-auto px-4 mt-20">

    <!-- HEADER -->
    <div class="mb-8">

        <!-- Label -->
        <div id="kamar" class="flex items-center gap-2 text-sm text-gray-500 mb-2 py-1 scroll-mt-24">
            <img src="{{ asset('images/frameworkpartikel.png') }}" class="w-4 h-4">
            <span>DAFTAR KAMAR</span>
        </div>

        <!-- Title -->
        <h2 class="text-2xl md:text-3xl font-semibold text-gray-800">
            Ada {{ $kamars->count() }} Kamar Kosong di Rafa Kost
        </h2>

        <!-- Subtitle -->
        <p class="text-gray-600 mt-2 max-w-xl text-sm md:text-base">
            Rafa Kost menyediakan total 10 kamar dengan pembagian 7 kamar mandi dalam dan 3 kamar mandi luar, memberikan kenyamanan serta privasi bagi setiap penghuni.
        </p>

    </div>

    <!-- GRID CARD -->
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6 mt-6 group">

        @foreach ($kamars as $kamar)
            <x-card-kamar :kamar="$kamar" />
        @endforeach

    </div>

</section>

<section class="max-w-6xl mx-auto px-4 mt-24 text-center">

    <h2 class="text-2xl md:text-3xl font-semibold">
        Apa Kata <span class="text-blue-500">#Penghuni</span>
    </h2>

    <p class="text-gray-500 mt-2 text-sm md:text-base">
        Setiap penghuni punya cerita pengalaman mereka menemukan kost terbaik
    </p>

    <!-- SLIDER -->
    <div class="relative mt-10">

        <!-- LEFT -->
        <button onclick="prevTesti()"
            class="absolute left-0 top-1/2 -translate-y-1/2 w-10 h-10 bg-black text-white rounded-full z-10">
            ←
        </button>

        <!-- SLIDE -->
    <div class="overflow-hidden">
        <div id="testiSlider" class="flex gap-6 transition-all duration-500">

            <div class="min-w-full flex justify-center">
                <x-testimonial-card
                    text="Kosan nyaman, view bagus depan lapangan enak buat piknik"
                    name="Khasanah Uswatun"
                    role="Mahasiswa"
                />
            </div>

            <div class="min-w-full flex justify-center">
                <x-testimonial-card
                    text="Tempat strategis, dekat kampus dan fasilitas lengkap"
                    name="Andi Saputra"
                    role="Mahasiswa"
                />
            </div>

            <div class="min-w-full flex justify-center">
                <x-testimonial-card
                    text="Tempat strategis, dekat kampus dan fasilitas lengkap"
                    name="Andi Saputra"
                    role="Mahasiswa"
                />
            </div>

            <div class="min-w-full flex justify-center">
                <x-testimonial-card
                    text="Tempat strategis, dekat kampus dan fasilitas lengkap"
                    name="Andi Saputra"
                    role="Mahasiswa"
                />
            </div>

        </div>
    </div>

        <!-- RIGHT -->
        <button onclick="nextTesti()"
            class="absolute right-0 top-1/2 -translate-y-1/2 w-10 h-10 bg-black text-white rounded-full z-10">
            →
        </button>

    </div>

</section>
@include('layouts.footer')
@endsection
