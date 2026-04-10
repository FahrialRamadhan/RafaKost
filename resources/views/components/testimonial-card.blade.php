@props(['text', 'name', 'role'])

<div class="bg-gray-100 rounded-2xl p-10 w-full max-w-4xl md:max-w-1xl text-left mx-auto">

    <!-- QUOTE -->
    <p class="text-3xl mb-4">“</p>

    <!-- TEXT -->
    <p class="text-gray-700 text-lg leading-relaxed">
        {{ $text }}
    </p>

    <!-- USER -->
    <div class="mt-4 text-sm text-gray-500">
        <p>- {{ $name }}</p>
        <p>{{ $role }}</p>
    </div>

</div>