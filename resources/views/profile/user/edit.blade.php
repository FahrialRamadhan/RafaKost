@extends('layouts.app')

@section('content')
<script src="https://cdn.tailwindcss.com"></script>
<div class="bg-[#F8F9FA] min-h-screen py-10 px-10 sm:px-6">
    <div class="max-w-[1050px] mx-auto">

        {{-- 🔹 TOMBOL KEMBALI --}}
        <div class="mb-6">
            <a href="javascript:history.back()" class="inline-flex items-center text-gray-800 hover:text-black font-semibold text-sm transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path>
                </svg>
                Kembali
            </a>
        </div>

        {{-- 🔹 MAIN LAYOUT --}}
        <div class="flex flex-col md:flex-row gap-8 items-start">

            {{-- 🔹 SIDEBAR KIRI (Bentuk Card) --}}
            <div class="w-full md:w-[280px] shrink-0 bg-white rounded-[20px] p-6 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] h-fit border border-gray-50">
                
                {{-- Profile Avatar & Name --}}
                <div class="flex flex-col items-center">
                    
                    {{-- Bungkus Avatar: overflow-hidden mencegah gambar bocor --}}
                    <div class="w-[84px] h-[84px] rounded-full border-[1.5px] border-gray-800 bg-[#9CD4FF] overflow-hidden flex items-center justify-center">
                        @if($user->photo)
                            <img src="{{ asset('storage/' . $user->photo) }}"
                                class="w-full h-full object-cover">
                        @else
                            <svg class="w-10 h-10 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        @endif
                    </div>

                    <h2 class="mt-4 font-bold text-gray-900 text-[15px]">
                        {{ $user->name }}
                    </h2>
                </div>

                {{-- Menu Sidebar --}}
                <div class="mt-8 space-y-1">
                    {{-- Active Menu: Profilku --}}
                    <a href="{{ route('profile.edit') }}"
                       class="flex items-center px-4 py-2.5 rounded-xl bg-[#CBE4FE] text-gray-900 font-semibold text-sm">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Profilku
                    </a>

                    {{-- Inactive Menu: Kost Sekarang --}}
                    <a href="{{ route('my-rentals.index') }}#active"
                       class="flex items-center px-4 py-2.5 rounded-xl text-[#A3A3A3] hover:bg-gray-50 hover:text-gray-900 font-medium text-sm">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        Kost Sekarang
                    </a>

                    {{-- Logout --}}
                    <form method="POST" action="{{ route('logout') }}" class="pt-2">
                        @csrf
                        <button class="w-full flex items-center px-4 py-2.5 text-[#FF4D4D] hover:bg-red-50 rounded-xl font-medium text-sm">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            Log Out
                        </button>
                    </form>
                </div>
            </div>

            {{-- 🔹 KONTEN KANAN (Formulir) --}}
            <div class="flex-1 w-full min-w-0">

             <div class="flex-1 w-full flex flex-col">
    
    {{-- 1. Memanggil File Form Profil --}}
    @include('profile.partials.update-profile-information-form')

    {{-- 2. Memanggil File Form Password --}}
    @include('profile.partials.update-password-form')

</div>

            </div>

        </div>
    </div>
</div>

{{-- SWEETALERT --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    .confirm-popup {
        border-radius: 18px !important;
        padding: 34px 36px 28px !important;
        width: 590px !important;
    }

    .confirm-popup-title {
        font-size: 28px !important;
        font-weight: 800 !important;
        color: #111827 !important;
    }

    .confirm-popup-text {
        font-size: 15px !important;
        color: #8b8b8b !important;
        line-height: 1.6 !important;
    }

    .confirm-popup .swal2-icon {
        border-color: #111827 !important;
        color: #111827 !important;
    }

    .confirm-btn-red {
        background: #ff0000 !important;
        color: #ffffff !important;
        border: 0 !important;
        border-radius: 6px !important;
        padding: 10px 28px !important;
        font-size: 13px !important;
        font-weight: 600 !important;
        margin-left: 8px !important;
    }

    .confirm-btn-red:hover {
        background: #dc2626 !important;
    }

    .cancel-btn-soft {
        background: #f3f4f6 !important;
        color: #4b5563 !important;
        border: 0 !important;
        border-radius: 6px !important;
        padding: 10px 28px !important;
        font-size: 13px !important;
        font-weight: 500 !important;
        margin-right: 8px !important;
    }

    .cancel-btn-soft:hover {
        background: #e5e7eb !important;
    }
	<style>
    .confirm-popup {
        border-radius: 18px !important;
        padding: 34px 36px 28px !important;
        width: 590px !important;
    }

    .confirm-popup-title {
        font-size: 28px !important;
        font-weight: 800 !important;
        color: #111827 !important;
    }

    .confirm-popup-text {
        font-size: 15px !important;
        color: #8b8b8b !important;
        line-height: 1.6 !important;
    }

    .confirm-popup .swal2-icon {
        border-color: #111827 !important;
        color: #111827 !important;
    }

    .confirm-btn-red {
        background: #ff0000 !important;
        color: #ffffff !important;
        border: 0 !important;
        border-radius: 6px !important;
        padding: 10px 28px !important;
        font-size: 13px !important;
        font-weight: 600 !important;
        margin-left: 8px !important;
    }

    .confirm-btn-red:hover {
        background: #dc2626 !important;
    }

    .cancel-btn-soft {
        background: #f3f4f6 !important;
        color: #4b5563 !important;
        border: 0 !important;
        border-radius: 6px !important;
        padding: 10px 28px !important;
        font-size: 13px !important;
        font-weight: 500 !important;
        margin-right: 8px !important;
    }

    .cancel-btn-soft:hover {
        background: #e5e7eb !important;
    }

    /* Notifikasi toggle */
    .notif-card {
        background: #f9fafb;
        border: 1px solid #d1d5db;
        border-radius: 14px;
        padding: 16px;
    }

    .notif-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .notif-option {
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        gap: 16px !important;
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        padding: 14px 16px;
        cursor: pointer;
        transition: 0.2s ease;
    }

    .notif-option:hover {
        border-color: #93c5fd;
        background: #f8fbff;
    }

    .notif-left {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 0;
    }

    .notif-icon {
        width: 38px;
        height: 38px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .notif-icon-email {
        background: #eff6ff;
        color: #2563eb;
    }

    .notif-icon-wa {
        background: #ecfdf5;
        color: #16a34a;
    }

    .notif-title {
        font-size: 14px;
        font-weight: 700;
        color: #111827;
        line-height: 1.2;
    }

    .notif-desc {
        font-size: 12px;
        color: #6b7280;
        margin-top: 3px;
        line-height: 1.35;
    }

    .notif-toggle-input {
        display: none !important;
    }

    .notif-switch {
        position: relative;
        width: 46px;
        height: 26px;
        background: #d1d5db;
        border-radius: 999px;
        flex-shrink: 0;
        transition: 0.2s ease;
    }

    .notif-switch::after {
        content: "";
        position: absolute;
        top: 3px;
        left: 3px;
        width: 20px;
        height: 20px;
        background: #ffffff;
        border-radius: 999px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.18);
        transition: 0.2s ease;
    }

    .notif-toggle-input:checked + .notif-switch {
        background: #2563eb;
    }

    .notif-toggle-input:checked + .notif-switch::after {
        transform: translateX(20px);
    }

    @media (max-width: 640px) {
        .notif-card {
            grid-column: span 3 / span 3;
        }

        .notif-option {
            padding: 13px 14px;
        }

        .notif-desc {
            font-size: 11px;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Responsive khusus HP saja
    |--------------------------------------------------------------------------
    | Web/Desktop tetap pakai layout asli:
    | .flex gap-8
    | .w-1/4
    | .w-3/4
    */
    @media (max-width: 640px) {
        .profile-page-wrapper {
            padding: 24px 16px !important;
        }

        .profile-layout {
            flex-direction: column !important;
            gap: 24px !important;
        }

        .profile-sidebar,
        .profile-content {
            width: 100% !important;
        }

        .profile-card {
            padding: 24px 18px !important;
        }

        .confirm-popup {
            width: 92% !important;
            padding: 28px 22px 24px !important;
        }

        .confirm-popup-title {
            font-size: 23px !important;
        }

        .confirm-popup-text {
            font-size: 13px !important;
        }
    }
</style>




<script>
    document.addEventListener('DOMContentLoaded', function () {
        const profileForm = document.getElementById('profileUpdateForm');
        const btnProfileSave = document.getElementById('btnProfileSave');
        const passwordForm = document.getElementById('passwordUpdateForm');

		/*
		|--------------------------------------------------------------------------
		| Popup Simpan Profil
		|--------------------------------------------------------------------------
		*/
		if (btnProfileSave && profileForm) {
		    btnProfileSave.addEventListener('click', function (e) {
		        e.preventDefault();
		
		        Swal.fire({
		            title: 'Simpan Perubahan?',
		            text: 'Yakin ingin menyimpan perubahan pada profil? Perubahan ini akan langsung diterapkan.',
		            icon: 'warning',
		            showCancelButton: true,
		            confirmButtonText: 'Simpan',
		            cancelButtonText: 'Batal',
		            reverseButtons: true,
		            buttonsStyling: false,
		            customClass: {
		                popup: 'confirm-popup',
		                title: 'confirm-popup-title',
		                htmlContainer: 'confirm-popup-text',
		                confirmButton: 'confirm-btn-red',
		                cancelButton: 'cancel-btn-soft'
		            }
		        }).then((result) => {
		            if (result.isConfirmed) {
		                profileForm.submit();
		            }
		        });
		    });
		}

        /*
        |--------------------------------------------------------------------------
        | Popup Ubah Password
        |--------------------------------------------------------------------------
        */
        if (passwordForm) {
            passwordForm.addEventListener('submit', function (e) {
                if (passwordForm.dataset.confirmed === 'true') {
                    return;
                }

                e.preventDefault();

                Swal.fire({
                    title: 'Ubah Password!',
                    text: 'Apakah kamu yakin ingin mengganti password? Gunakan password yang kuat dan mudah diingat.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ubah',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    buttonsStyling: false,
                    customClass: {
                        popup: 'confirm-popup',
                        title: 'confirm-popup-title',
                        htmlContainer: 'confirm-popup-text',
                        confirmButton: 'confirm-btn-red',
                        cancelButton: 'cancel-btn-soft'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        passwordForm.dataset.confirmed = 'true';
                        passwordForm.submit();
                    }
                });
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Popup Success / Error
        |--------------------------------------------------------------------------
        */
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: @json(session('success')),
                confirmButtonText: 'Oke',
                confirmButtonColor: '#2563eb'
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: @json(session('error')),
                confirmButtonText: 'Oke',
                confirmButtonColor: '#ef4444'
            });
        @endif

        @if(session('status') === 'profile-updated')
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Profil berhasil diperbarui.',
                confirmButtonText: 'Oke',
                confirmButtonColor: '#2563eb'
            });
        @endif

        @if(session('status') === 'password-updated')
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Password berhasil diperbarui.',
                confirmButtonText: 'Oke',
                confirmButtonColor: '#2563eb'
            });
        @endif

        @if($errors->updatePassword->any())
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: 'Password gagal diperbarui. Periksa kembali password lama, password baru, dan konfirmasi password.',
                confirmButtonText: 'Oke',
                confirmButtonColor: '#ef4444'
            });
        @endif
    });
</script>
@endsection