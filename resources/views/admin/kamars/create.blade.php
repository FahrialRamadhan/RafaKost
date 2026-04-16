<x-app-layout>
    <div class="max-w-4xl mx-auto py-6 px-4">
        <h1 class="text-2xl font-bold mb-4">Tambah Kamar</h1>

        <form action="{{ route('kamars.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-4">
                <label>Nama Kamar</label>
                <input type="text" name="nama" class="w-full border rounded p-2">
            </div>

            <div class="mb-4">
                <label>Lantai</label>
                <input type="text" name="lantai" class="w-full border rounded p-2">
            </div>

            <div class="mb-4">
                <label>Harga</label>
                <input type="text" name="harga" class="w-full border rounded p-2">
            </div>

            <div class="mb-4">
                <label>Status</label>
                <select name="status" class="w-full border rounded p-2">
                    <option value="tersedia">Tersedia</option>
                    <option value="terisi">Terisi</option>
                </select>
            </div>

            <div class="mb-4">
                <label>Image</label>
                <input type="file" name="image" class="w-full border rounded p-2">
            </div>

            <button class="bg-blue-500 text-white px-4 py-2 rounded">
                Simpan
            </button>
        </form>
    </div>
</x-app-layout>
