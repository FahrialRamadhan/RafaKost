
<x-guest-layout>
    @auth
    @include('layouts.navigation-user')
@else
    @include('layouts.navigation-guest')
@endauth
    <div class="bg-white-100 min-h-screen py-10 px-6 pt-40">

        <div class="max-w-6xl mx-auto">

            <!-- HEADER -->
            <div class="mb-6">
                <p class="text-xs tracking-widest text-gray-500 uppercase flex items-center gap-2">
                    
                    <!-- IMAGE ICON -->
                    <img src="{{ asset('images/frameworkpartikel.png') }}" 
                         alt="Lokasi Icon"
                         class="w-4 h-4 object-contain">

                    LOKASI
                </p>

                <h1 class="text-3xl font-bold text-gray-900 mt-2">
                    Lokasi Rafa Kost
                </h1>

                <p class="text-gray-600 mt-2">
                    Lokasi Rafa Kost berada di area strategis Purwokerto, dekat dengan berbagai fasilitas umum seperti kampus, tempat makan, dan pusat perbelanjaan. Akses jalan mudah dijangkau, serta lingkungan sekitar yang aman dan nyaman untuk menunjang aktivitas sehari-hari.
                </p>
            </div>

            <div class="relative rounded-2xl overflow-hidden shadow-xl border border-gray-200">

    <!-- highlight -->
    <div class="relative">

    <iframe 
        src="https://maps.google.com/maps?q=-7.40618758966827,109.23281022798868&z=18&output=embed"
        width="100%" 
        height="500"
        style="border:0;">
    </iframe>

    <!-- tombol -->
    <div class="absolute top-4 left-4 z-10">
        <a href="https://www.google.com/maps?q=-7.40618758966827,109.23281022798868"
           target="_blank"
           class="bg-white px-4 py-2 rounded-lg shadow text-sm hover:bg-gray-100">
            Buka di Maps
        </a>
    </div>

</div>

</div>

        </div>
    </div>
@include('layouts.footer')
</x-guest-layout>