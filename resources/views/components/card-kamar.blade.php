@props(['kamar'])

<a href="{{ route('kamar.show', $kamar->id) }}" class="block transition duration-300">

    <div class="relative w-full h-[280px] rounded-2xl overflow-hidden shadow
                transition duration-300 ease-in-out
                hover:-translate-y-2 hover:shadow-xl hover:scale-[1.03]">

        <!-- IMAGE -->
        <img
            src="{{ $kamar->image ? Storage::url($kamar->image) : asset('images/default-room.png') }}"
            alt="{{ $kamar->nama }}"
            class="w-full h-full object-cover"
        />

        <!-- TOP BADGE -->
        <div class="absolute top-2 left-2 right-2 flex justify-between items-center text-xs">

            <!-- TIPE -->
            <span class="flex items-center gap-1 bg-white/90 px-2 py-1 rounded-md text-gray-700 text-[11px]">
                <img src="{{ asset('images/logokamar.png') }}" class="w-3 h-3 object-contain">
                {{ $kamar->tipe ?? 'Km. mandi luar' }}
            </span>

            <!-- STATUS -->
            <span class="flex items-center gap-1 px-2 py-1 rounded-md text-black text-[11px]
                {{ $kamar->status == 'terisi' ? 'bg-red-300' : 'bg-blue-400' }}">
                <img src="{{ asset('images/logokamar.png') }}" class="w-3 h-3 object-contain">
                {{ ucfirst($kamar->status) }}
            </span>

        </div>

        <!-- WATERMARK -->
        <div class="absolute inset-0 flex items-center justify-center">
            <p class="text-white/70 font-semibold text-sm">
                RafaKost Property
            </p>
        </div>

        <!-- BOTTOM INFO -->
        <div class="absolute bottom-3 left-3 right-3 bg-white rounded-xl p-3 shadow">
            <p class="font-semibold text-sm">{{ $kamar->nama }}</p>

            <p class="text-xs text-gray-500 flex items-center gap-1">
                📍 {{ $kamar->lantai }}
            </p>

            <div class="mt-2 bg-gray-100 text-xs px-2 py-1 rounded-md text-gray-700">
                {{ $kamar->harga }}
            </div>
        </div>

    </div>

</a>
