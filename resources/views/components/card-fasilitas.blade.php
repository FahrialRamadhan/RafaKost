@props(['image', 'title'])

<div class="min-w-[220px] flex-shrink-0 rounded-xl overflow-hidden relative group">

    <img src="{{ $image }}" class="w-full h-40 object-cover">

    <div class="absolute inset-0 bg-black/40 group-hover:bg-black/50 transition"></div>

    <div class="absolute inset-0 flex items-center justify-start pl-4 text-white bg-white/140">
        <p class="font-semibold text-lg">{{ $title }}</p>
    </div>

</div>
