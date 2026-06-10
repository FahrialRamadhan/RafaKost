@extends('layouts.app')

@section('content')
<style>
    .identity-upload-icon {
        width: 34px;
        height: 34px;
        object-fit: contain;
        opacity: .78;
    }
</style>

<div class="min-h-screen py-10 px-4" style="background: #f4f6fa;">
    <div class="max-w-2xl mx-auto">

        {{-- Page Header --}}
        <div style="margin-bottom: 24px; text-align:center;">
            <h1 style="font-size: 1.4rem; font-weight: 700; color: #111827; margin: 0;">
                Verifikasi Identitas
            </h1>
            <p style="font-size: 0.82rem; color: #6b7280; margin-top: 5px;">
                Upload foto KTP dan selfie untuk mengaktifkan fitur booking kamar.
            </p>
        </div>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div style="margin-bottom: 16px; padding: 14px 18px; background: #ecfdf5; border: 1px solid #6ee7b7; border-radius: 12px; font-size: 0.85rem; font-weight: 600; color: #065f46;">
                {{ session('success') }}
            </div>
        @endif

        @if(session('warning'))
            <div style="margin-bottom: 16px; padding: 14px 18px; background: #fffbeb; border: 1px solid #fde68a; border-radius: 12px; font-size: 0.85rem; font-weight: 600; color: #92400e;">
                {{ session('warning') }}
            </div>
        @endif

        @if(session('error'))
            <div style="margin-bottom: 16px; padding: 14px 18px; background: #fef2f2; border: 1px solid #fca5a5; border-radius: 12px; font-size: 0.85rem; font-weight: 600; color: #991b1b;">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div style="margin-bottom: 16px; padding: 14px 18px; background: #fef2f2; border: 1px solid #fca5a5; border-radius: 12px; font-size: 0.85rem; font-weight: 600; color: #991b1b;">
                {{ $errors->first() }}
            </div>
        @endif

        {{-- Status Banner --}}
        <div style="margin-bottom: 20px;">
            @if($user->identity_status === 'approved')
                <div style="display: flex; align-items: flex-start; gap: 14px; padding: 16px 20px; background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 14px;">
                    <div style="width: 36px; height: 36px; border-radius: 50%; background: #16a34a; display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 1px;">
                        <svg width="18" height="18" fill="none" viewBox="0 0 24 24">
                            <path d="M5 13l4 4L19 7" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div>
                        <div style="font-size: 0.9rem; font-weight: 700; color: #166534;">Identitas Disetujui</div>
                        <p style="font-size: 0.82rem; color: #15803d; margin-top: 3px;">Akun kamu sudah bisa melakukan booking kamar.</p>
                    </div>
                </div>

            @elseif($user->identity_status === 'pending')
                <div style="display: flex; align-items: flex-start; gap: 14px; padding: 16px 20px; background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 14px;">
                    <div style="width: 36px; height: 36px; border-radius: 50%; background: #2563eb; display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 1px;">
                        <svg width="18" height="18" fill="none" viewBox="0 0 24 24">
                            <path d="M12 6v6l4 2" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <circle cx="12" cy="12" r="9" stroke="#fff" stroke-width="2"/>
                        </svg>
                    </div>
                    <div>
                        <div style="font-size: 0.9rem; font-weight: 700; color: #1e40af;">Sedang Diproses</div>
                        <p style="font-size: 0.82rem; color: #1d4ed8; margin-top: 3px;">Data identitas kamu sedang dikirim ke sistem verifikasi otomatis.</p>
                    </div>
                </div>

            @elseif($user->identity_status === 'manual_review')
                <div style="display: flex; align-items: flex-start; gap: 14px; padding: 16px 20px; background: #fffbeb; border: 1px solid #fde68a; border-radius: 14px;">
                    <div style="width: 36px; height: 36px; border-radius: 50%; background: #d97706; display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 1px;">
                        <svg width="18" height="18" fill="none" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="1.5" fill="#fff"/>
                            <path d="M12 7v4" stroke="#fff" stroke-width="2.5" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <div>
                        <div style="font-size: 0.9rem; font-weight: 700; color: #92400e;">Perlu Review Admin</div>
                        <p style="font-size: 0.82rem; color: #b45309; margin-top: 3px;">
                            {{ $user->identity_rejection_reason ?: 'Hasil verifikasi otomatis belum yakin, admin akan mengecek manual.' }}
                        </p>
                    </div>
                </div>

            @elseif($user->identity_status === 'rejected')
                <div style="display: flex; align-items: flex-start; gap: 14px; padding: 16px 20px; background: #fef2f2; border: 1px solid #fecaca; border-radius: 14px;">
                    <div style="width: 36px; height: 36px; border-radius: 50%; background: #dc2626; display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 1px;">
                        <svg width="18" height="18" fill="none" viewBox="0 0 24 24">
                            <path d="M6 18L18 6M6 6l12 12" stroke="#fff" stroke-width="2.5" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <div>
                        <div style="font-size: 0.9rem; font-weight: 700; color: #991b1b;">Verifikasi Ditolak</div>
                        <p style="font-size: 0.82rem; color: #b91c1c; margin-top: 3px;">
                            {{ $user->identity_rejection_reason ?: 'Silakan upload ulang foto KTP dan selfie yang lebih jelas.' }}
                        </p>
                    </div>
                </div>

            @else
                <div style="display: flex; align-items: flex-start; gap: 14px; padding: 16px 20px; background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 14px;">
                    <div style="width: 36px; height: 36px; border-radius: 50%; background: #2563eb; display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 1px;">
                        <svg width="18" height="18" fill="none" viewBox="0 0 24 24">
                            <path d="M13 16h-1v-4h-1m1-4h.01" stroke="#fff" stroke-width="2.5" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <div>
                        <div style="font-size: 0.9rem; font-weight: 700; color: #1e40af;">Belum Verifikasi</div>
                        <p style="font-size: 0.82rem; color: #1d4ed8; margin-top: 3px;">Kamu harus verifikasi identitas sebelum bisa booking kamar.</p>
                    </div>
                </div>
            @endif
        </div>

        {{-- Main Card --}}
        <div style="background: #fff; border: 1px solid #e5e7eb; border-radius: 16px; padding: 28px; box-shadow: 0 18px 45px rgba(15, 23, 42, .12);">

            @if($user->identity_status !== 'approved')

                {{-- Steps Info --}}
                <div style="display: flex; gap: 10px; margin-bottom: 24px;">
                    @foreach([
                        ['1', 'Upload KTP', 'Foto KTP yang jelas'],
                        ['2', 'Live Selfie', 'Wajah dekat kamera'],
                        ['3', 'Tunggu Review', 'Admin verifikasi']
                    ] as [$num, $title, $sub])
                        <div style="flex: 1; text-align: center; padding: 14px 10px; background: #f9fafb; border-radius: 10px; border: 1px solid #f3f4f6;">
                            <div style="width: 28px; height: 28px; border-radius: 50%; background: #e0f2fe; color: #0ea5e9; font-size: 0.8rem; font-weight: 700; display: flex; align-items: center; justify-content: center; margin: 0 auto 8px;">
                                {{ $num }}
                            </div>
                            <div style="font-size: 0.78rem; font-weight: 700; color: #111827;">{{ $title }}</div>
                            <div style="font-size: 0.7rem; color: #9ca3af; margin-top: 2px;">{{ $sub }}</div>
                        </div>
                    @endforeach
                </div>

                <form method="POST" action="{{ route('identity-verification.store') }}" enctype="multipart/form-data" onsubmit="return validateLiveSelfies()">
                    @csrf

                    <div style="display: grid; grid-template-columns: 1fr; gap: 16px; margin-bottom: 20px;">

                        {{-- KTP --}}
                        <div>
                            <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #374151; margin-bottom: 8px;">
                                Foto KTP <span style="color: #dc2626;">*</span>
                            </label>

                            <label style="display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 8px; padding: 28px 16px; background: #f9fafb; border: 2px dashed #111827; border-radius: 12px; cursor: pointer;"
                                   onmouseover="this.style.borderColor='#0ea5e9'"
                                   onmouseout="this.style.borderColor='#111827'">
                                <img src="{{ asset('images/ktplog.png') }}" alt="Upload KTP" class="identity-upload-icon">

                                <span style="font-size: 0.78rem; font-weight: 600; color: #6b7280;">
                                    Pilih foto KTP
                                </span>

                                <span style="font-size: 0.7rem; color: #9ca3af;">
                                    JPG, PNG, WEBP — maks 4MB
                                </span>

                                <input type="file" name="ktp_photo" accept="image/*" required style="display: none;" onchange="updateLabel(this, 'ktp-name')">
                            </label>

                            <div id="ktp-name" style="font-size: 0.72rem; color: #0ea5e9; margin-top: 6px; font-weight: 600;"></div>
                        </div>

                        {{-- Selfie + Selfie KTP --}}
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">

                            {{-- Live Selfie --}}
                            <div>
                                <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #374151; margin-bottom: 8px;">
                                    Live Selfie Wajah <span style="color: #dc2626;">*</span>
                                </label>

                                <label onclick="openCamera('selfie_base64', 'selfie-name', 'selfie-preview')"
                                       style="display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 8px; padding: 28px 12px; background: #f9fafb; border: 2px dashed #111827; border-radius: 12px; cursor: pointer;"
                                       onmouseover="this.style.borderColor='#0ea5e9'"
                                       onmouseout="this.style.borderColor='#111827'">
                                    <img src="{{ asset('images/ktplog.png') }}" alt="Selfie" class="identity-upload-icon">

                                    <span style="font-size: 0.78rem; font-weight: 600; color: #6b7280; text-align: center;">
                                        Ambil selfie wajah
                                    </span>

                                    <span style="font-size: 0.7rem; color: #9ca3af; text-align: center;">
                                        Wajah terlihat jelas
                                    </span>
                                </label>

                                <input type="hidden" name="selfie_base64" id="selfie_base64" required>
                                <div id="selfie-name" style="font-size: 0.72rem; color: #0ea5e9; margin-top: 6px; font-weight: 600;"></div>
                                <img id="selfie-preview" src="" alt="" style="display:none; width: 100%; margin-top: 10px; border-radius: 10px; border: 1px solid #e5e7eb; object-fit: cover;">
                            </div>

                            {{-- Live Selfie + KTP --}}
                            <div>
                                <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #374151; margin-bottom: 8px;">
                                    Selfie + KTP <span style="color: #dc2626;">*</span>
                                </label>

                                <label onclick="openCamera('selfie_ktp_base64', 'selfie-ktp-name', 'selfie-ktp-preview')"
                                       style="display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 8px; padding: 28px 12px; background: #f9fafb; border: 2px dashed #111827; border-radius: 12px; cursor: pointer;"
                                       onmouseover="this.style.borderColor='#0ea5e9'"
                                       onmouseout="this.style.borderColor='#111827'">
                                    <img src="{{ asset('images/ktplog.png') }}" alt="Selfie KTP" class="identity-upload-icon">

                                    <span style="font-size: 0.78rem; font-weight: 600; color: #6b7280; text-align: center;">
                                        Selfie pegang KTP
                                    </span>

                                    <span style="font-size: 0.7rem; color: #9ca3af; text-align: center;">
                                        Wajah & KTP jelas
                                    </span>
                                </label>

                                <input type="hidden" name="selfie_ktp_base64" id="selfie_ktp_base64" required>
                                <div id="selfie-ktp-name" style="font-size: 0.72rem; color: #0ea5e9; margin-top: 6px; font-weight: 600;"></div>
                                <img id="selfie-ktp-preview" src="" alt="" style="display:none; width: 100%; margin-top: 10px; border-radius: 10px; border: 1px solid #e5e7eb; object-fit: cover;">
                            </div>

                        </div>
                    </div>

                    {{-- Info Note --}}
                    <div style="display: flex; gap: 12px; align-items: flex-start; padding: 14px 16px; background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 10px; margin-bottom: 24px;">
                        <svg width="18" height="18" fill="none" viewBox="0 0 24 24" style="flex-shrink: 0; margin-top: 2px;">
                            <circle cx="12" cy="12" r="10" stroke="#111827" stroke-width="1.5"/>
                            <path d="M12 8v4M12 16h.01" stroke="#111827" stroke-width="1.8" stroke-linecap="round"/>
                        </svg>

                        <p style="font-size: 0.8rem; color: #111827; margin: 0; line-height: 1.6;">
                            Pastikan nama dan foto pada KTP terlihat jelas. Data ini hanya digunakan untuk validasi penyewa oleh admin Rafa Kost.
                        </p>
                    </div>

                    {{-- Actions --}}
                    <div style="display: flex; justify-content: flex-end; gap: 10px;">
                        <a href="{{ url('/') }}"
                           style="padding: 12px 22px; border-radius: 10px; background: #f3f4f6; color: #374151; font-size: 0.88rem; font-weight: 600; text-decoration: none;">
                            Kembali
                        </a>

                        <button type="submit"
                                style="padding: 12px 28px; border-radius: 10px; background: #0ea5e9; color: #fff; font-size: 0.88rem; font-weight: 700; border: none; cursor: pointer;">
                            Kirim
                        </button>
                    </div>
                </form>

            @else
                {{-- Approved state --}}
                <div style="text-align: center; padding: 20px 0;">
                    <div style="width: 64px; height: 64px; border-radius: 50%; background: #ecfdf5; border: 2px solid #6ee7b7; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                        <svg width="30" height="30" fill="none" viewBox="0 0 24 24">
                            <path d="M5 13l4 4L19 7" stroke="#16a34a" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <p style="font-size: 0.9rem; color: #6b7280; margin-bottom: 20px;">
                        Identitas kamu sudah terverifikasi. Sekarang kamu bisa mencari dan booking kamar.
                    </p>
                    <a href="{{ url('/') }}"
                       style="display: inline-block; padding: 12px 28px; border-radius: 10px; background: #0ea5e9; color: #fff; font-size: 0.88rem; font-weight: 700; text-decoration: none;">
                        Cari Kamar →
                    </a>
                </div>
            @endif
        </div>

    </div>
</div>

{{-- Camera Modal --}}
<div id="camera-modal" style="display:none; position: fixed; inset: 0; background: rgba(17, 24, 39, 0.78); z-index: 9999; align-items: center; justify-content: center; padding: 20px;">
    <div style="background: #fff; width: 100%; max-width: 420px; border-radius: 16px; padding: 18px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
            <h3 style="font-size: 1rem; font-weight: 700; color: #111827; margin: 0;">Ambil Foto</h3>
            <button type="button" onclick="closeCamera()" style="border: none; background: #f3f4f6; color: #374151; border-radius: 8px; padding: 8px 10px; cursor: pointer;">
                Tutup
            </button>
        </div>

        <video id="camera-video" autoplay playsinline style="width: 100%; border-radius: 12px; background: #000;"></video>
        <canvas id="camera-canvas" style="display:none;"></canvas>

        <button type="button" onclick="takePhoto()" style="width: 100%; margin-top: 14px; padding: 12px 20px; border-radius: 10px; background: #0ea5e9; color: #fff; font-size: 0.88rem; font-weight: 700; border: none; cursor: pointer;">
            Ambil Foto
        </button>
    </div>
</div>

<script>
let cameraStream = null;
let currentInputId = null;
let currentLabelId = null;
let currentPreviewId = null;

function updateLabel(input, targetId) {
    const el = document.getElementById(targetId);
    if (input.files && input.files[0]) {
        el.textContent = '✓ ' + input.files[0].name;
    }
}

async function openCamera(inputId, labelId, previewId) {
    currentInputId = inputId;
    currentLabelId = labelId;
    currentPreviewId = previewId;

    const modal = document.getElementById('camera-modal');
    const video = document.getElementById('camera-video');

    if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
        alert('Browser tidak mendukung kamera langsung. Coba gunakan Chrome di HP.');
        return;
    }

    try {
        cameraStream = await navigator.mediaDevices.getUserMedia({
            video: { facingMode: 'user' },
            audio: false
        });

        video.srcObject = cameraStream;
        modal.style.display = 'flex';
    } catch (error) {
        alert('Kamera tidak bisa dibuka. Pastikan izin kamera di browser sudah diaktifkan.');
    }
}

function takePhoto() {
    const video = document.getElementById('camera-video');
    const canvas = document.getElementById('camera-canvas');

    if (!video.srcObject) {
        alert('Kamera belum aktif.');
        return;
    }

    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;

    const context = canvas.getContext('2d');
    context.drawImage(video, 0, 0, canvas.width, canvas.height);

    const imageData = canvas.toDataURL('image/jpeg', 0.9);

    document.getElementById(currentInputId).value = imageData;
    document.getElementById(currentLabelId).textContent = '✓ Foto berhasil diambil';

    const preview = document.getElementById(currentPreviewId);
    preview.src = imageData;
    preview.style.display = 'block';

    closeCamera();
}

function closeCamera() {
    const modal = document.getElementById('camera-modal');
    const video = document.getElementById('camera-video');

    if (cameraStream) {
        cameraStream.getTracks().forEach(track => track.stop());
        cameraStream = null;
    }

    video.srcObject = null;
    modal.style.display = 'none';
}

function validateLiveSelfies() {
    const selfie = document.getElementById('selfie_base64').value;
    const selfieKtp = document.getElementById('selfie_ktp_base64').value;

    if (!selfie) {
        alert('Silakan ambil live selfie wajah terlebih dahulu.');
        return false;
    }

    if (!selfieKtp) {
        alert('Silakan ambil live selfie sambil pegang KTP terlebih dahulu.');
        return false;
    }

    return true;
}
</script>
@endsection