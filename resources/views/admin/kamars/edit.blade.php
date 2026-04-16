<x-app-layout>
    <div class="max-w-4xl mx-auto py-6 px-4">
        <h1 class="text-2xl font-bold mb-4">Edit Kamar</h1>

        <form action="{{ route('kamars.update', $kamar->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label>Nama Kamar</label>
                <input type="text" name="nama" value="{{ $kamar->nama }}" class="w-full border rounded p-2">
            </div>

            <div class="mb-4">
                <label>Lantai</label>
                <input type="text" name="lantai" value="{{ $kamar->lantai }}" class="w-full border rounded p-2">
            </div>

            <div class="mb-4">
                <label>Harga</label>
                <input type="text" name="harga" value="{{ $kamar->harga }}" class="w-full border rounded p-2">
            </div>

            <div class="mb-4">
                <label>Status</label>
                <select name="status" class="w-full border rounded p-2">
                    <option value="tersedia" {{ $kamar->status == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                    <option value="terisi" {{ $kamar->status == 'terisi' ? 'selected' : '' }}>Terisi</option>
                </select>
            </div>

            <div class="mb-4">
                <label>Image</label>
                <input type="file" name="image" class="w-full border rounded p-2">
            </div>

            <button class="bg-yellow-500 text-white px-4 py-2 rounded">
                Update
            </button>
        </form>
    </div>
</x-app-layout>
