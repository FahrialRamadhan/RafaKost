
<x-guest-layout>
    <x-slot name="title">Home - Rafa Kost</x-slot>
    @include('layouts.navigation-guest')

    <section class="px-6 mt-10">
        <div class="relative w-full max-w-6xl mx-auto rounded-2xl overflow-hidden">
            <img src="{{ asset('images/benner.png') }}" class="w-full h-[550px] object-cover">
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

                <!-- Search -->
                <div class="mt-6 flex items-center bg-white rounded-full overflow-hidden shadow-lg w-full max-w-xl">
                    <input type="text" placeholder="Tulis tipe kamar atau nomor kamar"
                        class="flex-1 px-5 py-3 text-gray-700 outline-none">
                    <button class="bg-blue-500 text-white px-6 py-3 hover:bg-blue-600">
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
        <div class="mb-10">

            <div class="flex items-center gap-2 text-gray-500 text-sm mb-2">
                <img src="{{ asset('images/frameworkpartikel.png') }}" class="w-4 h-4">
                <span>FASILITAS</span>
            </div>

            <h2 class="text-3xl md:text-4xl font-semibold text-gray-800">
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
        <x-card-fasilitas image="{{ asset('images/parkir.jpg') }}" title="Parkiran" />
        <x-card-fasilitas image="{{ asset('images/listrik.jpg') }}" title="Bebas Listrik" />
        <x-card-fasilitas image="{{ asset('images/air.jpg') }}" title="Air" />
        <x-card-fasilitas image="{{ asset('images/parkir.jpg') }}" title="Parkiran" />
        <x-card-fasilitas image="{{ asset('images/parkir.jpg') }}" title="Parkiran" />


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
        <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
            <img src="{{ asset('images/frameworkpartikel.png') }}" class="w-4 h-4">
            <span>DAFTAR KAMAR</span>
        </div>

        <!-- Title -->
        <h2 class="text-2xl md:text-3xl font-semibold text-gray-800">
            Ada {{ $kamars->count() }} Kamar di Rafa Kost
        </h2>

        <!-- Subtitle -->
        <p class="text-gray-600 mt-2 max-w-xl text-sm md:text-base">
            Rafa Kost menyediakan total 10 kamar dengan pembagian 7 kamar mandi dalam dan 3 kamar mandi luar, memberikan kenyamanan serta privasi bagi setiap penghuni.
        </p>

    </div>

    <!-- GRID CARD -->
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6 mt-6">

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
        class="absolute left-[-10px] top-1/2 -translate-y-1/2 z-10 w-10 h-10 bg-black text-white rounded-full">
        ←
    </button>

    <!-- WRAPPER -->
    <div class="overflow-hidden">

        <!-- SLIDER -->
        <div id="testiSlider" class="flex gap-6 transition-all duration-500">

            <!-- ITEM -->
            <div class="min-w-[75%] flex justify-center items-center">
                <x-testimonial-card 
                    text="Kosan nyaman, view bagus depan lapangan enak buat piknik"
                    name="Khasanah Uswatun"
                    role="Mahasiswa"
                />
            </div>

            <div class="min-w-[75%] flex justify-center items-center">
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
        class="absolute right-[-10px] top-1/2 -translate-y-1/2 z-10 w-10 h-10 bg-black text-white rounded-full">
        →
    </button>

</div>

</section>
</x-guest-layout>
