<x-app-layout>
    <div class="max-w-7xl mx-auto py-6 px-4">
        <h1 class="text-2xl font-bold mb-4">Data Kamar</h1>

        @if(session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        <a href="{{ route('kamars.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded">
            Tambah Kamar
        </a>

        <table class="w-full mt-4 border">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border p-2">No</th>
                    <th class="border p-2">Nama</th>
                    <th class="border p-2">Lantai</th>
                    <th class="border p-2">Harga</th>
                    <th class="border p-2">Status</th>
                    <th class="border p-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($kamars as $kamar)
                    <tr>
                        <td class="border p-2">{{ $loop->iteration }}</td>
                        <td class="border p-2">{{ $kamar->nama }}</td>
                        <td class="border p-2">{{ $kamar->lantai }}</td>
                        <td class="border p-2">{{ $kamar->harga }}</td>
                        <td class="border p-2">{{ $kamar->status }}</td>
                        <td class="border p-2">
                            <a href="{{ route('kamars.edit', $kamar->id) }}" class="text-yellow-600">Edit</a>

                            <form action="{{ route('kamars.destroy', $kamar->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button onclick="return confirm('Yakin hapus?')" class="text-red-600 ml-2">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
