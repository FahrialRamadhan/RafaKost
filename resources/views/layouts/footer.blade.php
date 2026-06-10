<footer class="max-w-6xl mx-auto px-4 mt-24 mb-16">
    <div class="relative rounded-2xl overflow-hidden flex flex-col justify-between min-h-[300px] shadow-sm">

        <img src="{{ asset('images/pemandangan.jpg') }}" 
             alt="Background Footer" 
             class="absolute inset-0 w-full h-full object-cover z-0">

        <div class="absolute inset-0 bg-black/30 z-0"></div>

        <div class="relative z-10 flex flex-col justify-between p-6 md:p-8 h-full">

            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 text-white">
                
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/secondlogo.png') }}" alt="Logo" class="h-10">
                    <h2 class="text-3xl font-semibold">Rafa Kost</h2>
                </div>

                <p class="text-lg max-w-md text-left md:text-right font-medium">
                    Rafa Kost menjadi rumah kedua setelah rumah kamu.
                </p>

            </div>

            <div class="bg-white rounded-xl p-6 md:px-10 md:py-8 mt-8 w-full shadow-lg">
                <div class="flex flex-col md:flex-row justify-between gap-10">

                    <div class="flex flex-col gap-4">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">MENU</h3>
                        <ul class="flex flex-col gap-3 text-sm text-gray-700 font-medium">
                            <li>
                                <a href="#beranda" class="hover:text-blue-500 transition-colors duration-200">Beranda</a>
                            </li>
                            <li>
                                <a href="#kamar" class="hover:text-blue-500 transition-colors duration-200">Kamar</a>
                            </li>
                            <li>
                                <a href="/maps" class="hover:text-blue-500 transition-colors duration-200">Maps</a>
                            </li>
                        </ul>
                    </div>

                    <div class="flex flex-col gap-4">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">INFORMATION</h3>
                        <ul class="flex flex-col gap-3 text-sm text-gray-700 font-medium">
                            <li>
                                <a href="#" class="hover:text-blue-500 transition-colors duration-200">Privasi</a>
                            </li>
                            <li>
                                <a href="#" class="hover:text-blue-500 transition-colors duration-200">Faq</a>
                            </li>
                            <li>
                                <a href="mailto:hello@rafakost.biz.id" class="hover:text-blue-500 transition-colors duration-200">Contacts</a>
                            </li>
                        </ul>
                    </div>

                    <div class="flex flex-col items-start md:items-end gap-3">
                        <a 
                            href="https://wa.me/6289538402398?text=Halo%20Rafa%20Kost%2C%20saya%20ingin%20bertanya%20tentang%20kamar."
                            target="_blank"
                            rel="noopener noreferrer"
                            class="bg-[#0ea5e9] hover:bg-sky-600 text-white px-6 py-2 rounded-md text-sm font-medium transition-colors duration-200 shadow-sm"
                        >
                            Contact Us
                        </a>

                        <div class="flex flex-col items-start md:items-end gap-1 text-sm text-gray-400 mt-2 md:text-right">
                            <a href="mailto:hello@rafakost.biz.id" class="hover:text-blue-500 transition-colors duration-200">
                                hello@rafakost.biz.id
                            </a>
                            <a 
                                href="https://wa.me/6289538402398?text=Halo%20Rafa%20Kost%2C%20saya%20ingin%20bertanya%20tentang%20kamar."
                                target="_blank"
                                rel="noopener noreferrer"
                                class="hover:text-blue-500 transition-colors duration-200"
                            >
                                +62895-3840-22398
                            </a>
                            <p class="mt-1">
                                Brigjeng encung, Purwokerto Utara
                            </p>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

<script src="https://cdn.tailwindcss.com"></script>
</footer>