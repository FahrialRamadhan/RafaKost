@extends('layouts.app')
@section('content')
<div class="mt-20">
    <div class="min-h-screen py-10 px-6">
        <div class="max-w-6xl mx-auto flex gap-8">

            {{-- 🔹 SIDEBAR --}}
            <div class="w-1/4">
                <div class="bg-white rounded-xl p-6 shadow-sm">

                    {{-- Profile --}}
                    <div class="text-center">
                        @if($user->photo)
                            <img src="{{ asset('storage/' . $user->photo) }}"
                                class="w-20 h-20 mx-auto rounded-full object-cover">
                        @else
                            <div class="w-20 h-20 mx-auto bg-blue-200 rounded-full flex items-center justify-center">
                                👤
                            </div>
                        @endif

                        <p class="mt-3 font-semibold text-gray-800">
                            {{ $user->name }}
                        </p>
                    </div>

                    {{-- Menu --}}
                    <div class="mt-6 space-y-2">
                        <a href="{{ route('profile.edit') }}"
                           class="block px-4 py-2 rounded-lg bg-blue-100 text-blue-700 font-medium">
                            Profilku
                        </a>

                        <a href="#"
                           class="block px-4 py-2 rounded-lg text-gray-600 hover:bg-gray-100">
                            Kost Sekarang
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="w-full text-left px-4 py-2 text-red-500 hover:bg-red-50 rounded-lg">
                                Log out
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- 🔹 CONTENT --}}
            <div class="w-3/4">

                <div class="bg-white rounded-xl p-8 shadow-sm">

                    {{-- Title --}}
                    <h1 class="text-2xl font-bold text-gray-800 mb-1">Profilku</h1>
                    <p class="text-gray-500 text-sm mb-6">
                        Update foto profil dan detail di sini
                    </p>

                    {{-- FORM PROFILE --}}
                    @include('profile.partials.update-profile-information-form')

                    <hr class="my-8">

                    {{-- PASSWORD --}}
                    @include('profile.partials.update-password-form')

                </div>
            </div>

        </div>
    </div>
</div>
@endsection
