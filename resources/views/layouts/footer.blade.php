<footer class="max-w-6xl mx-auto px-4 mt-24 mb-16">
    <div class="relative rounded-2xl overflow-hidden">

        <!-- BACKGROUND -->
        <img src="{{ asset('images/pemandangan.jpg') }}" 
             class="w-full h-[280px] object-cover">

        <!-- OVERLAY -->
        <div class="absolute inset-0 bg-black/30"></div>

        <!-- CONTENT -->
        <div class="absolute inset-0 flex flex-col justify-between p-8 text-white">

            <!-- TOP -->
            <div class="flex justify-between items-center">

                <!-- LOGO -->
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/secondlogo.png') }}" class="h-10">
                    <h2 class="text-3xl font-semibold">Rafa Kost</h2>
                </div>

                <!-- TAGLINE -->
                <p class="text-lg max-w-md text-right">
                    Rafa Kost menjadi rumah kedua setelah rumah kamu.
                </p>

            </div>

            <!-- CARD -->
            <div class="bg-white text-gray-700 rounded-xl p-6 mt-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                    <div>
                        <h3 class="text-sm font-semibold mb-3">MENU</h3>
                        <ul class="space-y-2 text-sm">
                            <li><a href="#">Beranda</a></li>
                            <li><a href="#">Kamar</a></li>
                            <li><a href="#">Maps</a></li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-sm font-semibold mb-3">INFORMATION</h3>
                        <ul class="space-y-2 text-sm">
                            <li><a href="#">Privasi</a></li>
                            <li><a href="#">FAQ</a></li>
                            <li><a href="#">Contacts</a></li>
                        </ul>
                    </div>

                    <div class="text-sm">
                        <button class="bg-blue-500 text-white px-4 py-2 rounded mb-3">
                            Contact Us
                        </button>

                        <p>rafakost@gmail.com</p>
                        <p class="mt-1">+62 895-3840-2398</p>
                        <p class="mt-1 text-gray-500">
                            Brijen encung, Purwokerto Utara
                        </p>
                    </div>

                </div>
            </div>

        </div>
    </div>
</footer>