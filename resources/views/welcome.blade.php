@include('layouts.navigation-guest')
@vite(['resources/css/app.css', 'resources/js/app.js'])

<section class="px-6 mt-10">
    <div class="relative w-full max-w-6xl mx-auto rounded-2xl overflow-hidden">

        <img src="{{ asset('images/benner.png') }}" class="w-full h-[660px] object-cover">

        <div class="absolute inset-0 bg-black/30"></div>

        <div class="absolute inset-0 flex flex-col items-center justify-start text-center text-white px-4 pt-28">

            <div class="flex items-center gap-3">

                <!-- Logo -->
                <img src="{{ asset('images/secondlogo.png') }}" class="h-11 md:h-14 w-auto">

                <!-- Text -->
                <h1 class="text-4xl md:text-5xl font-medium leading-none justify-start pt-4">
                    Rafa Kost
                </h1>

            </div>

            <p class="mt-2 text-xl md:text-4xl font-medium">
                Nyaman, Aman, Terjangkau
            </p>

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
