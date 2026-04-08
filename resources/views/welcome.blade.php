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
                <div class="flex gap-4 mt-6">

                    <img src="{{ asset('images/koridorkamarkiri.png') }}" class="w-1/2 h-40 object-cover rounded-lg">

                    <img src="{{ asset('images/koridorkamarkanan.png') }}" class="w-1/2 h-40 object-cover rounded-lg">

                </div>

            </div>

            <!-- RIGHT CONTENT -->
            <div class="relative w-full opacity-60">

                <!-- ICON (pojok kanan section, bukan text) -->
                <img src="{{ asset('images/bintangpartikel.png') }}"
                    class="absolute top-[-20px] right-0 w-6 h-6 opacity-70">

                <!-- TEXT -->
                <div class="text-gray-600 leading-relaxed text-sm md:text-base max-w-xl justify-start p-50">
                    <p>
                        <span class="text-blue-500 font-medium">Rafa Kost</span> hadir dengan fasilitas lengkap,
                        lingkungan aman, dan lokasi strategis untuk hunian nyaman tanpa ribet—
                        cocok untuk mahasiswa maupun pekerja.
                    </p>
                </div>

            </div>

        </div>

    </section>
</x-guest-layout>
