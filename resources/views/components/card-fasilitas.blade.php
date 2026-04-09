@props(['image', 'title'])

<div class="min-w-[220px] flex-shrink-0 rounded-xl overflow-hidden relative group">

    <img src="{{ $image }}" class="w-full h-40 object-cover">

    <div class="absolute inset-0 bg-black/40 group-hover:bg-black/50 transition"></div>

    <div class="absolute bottom-3 left-3 text-white">
        <p class="font-semibold">{{ $title }}</p>
    </div>

</div>
