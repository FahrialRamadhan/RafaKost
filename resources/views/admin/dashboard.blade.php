<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">
            Dashboard Admin
        </h2>
    </x-slot>

    <div class="py-8 bg-gray-100 min-h-screen">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow rounded-lg p-6 mb-6">
                <h1 class="text-2xl font-bold text-gray-800 mb-2">
                    Selamat datang, {{ auth()->user()->name }}
                </h1>
                <p class="text-gray-600">
                    Kelola data Rafa Kost dari halaman ini.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('kamars.index') }}"
                   class="bg-white border rounded-lg p-5 shadow-sm hover:shadow-md hover:bg-gray-50 transition">
                    <h3 class="text-lg font-semibold text-gray-800">
                        Data Kamar
                    </h3>
                    <p class="text-sm text-gray-600 mt-1">
                        Lihat dan kelola data kamar
                    </p>
                </a>

                <a href="{{ route('kamars.create') }}"
                   class="bg-white border rounded-lg p-5 shadow-sm hover:shadow-md hover:bg-gray-50 transition">
                    <h3 class="text-lg font-semibold text-gray-800">
                        Tambah Kamar
                    </h3>
                    <p class="text-sm text-gray-600 mt-1">
                        Tambahkan kamar baru
                    </p>
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
