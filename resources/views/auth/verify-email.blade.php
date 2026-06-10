@extends('layouts.app')

@section('content')
<style>
    /* Efek interaksi tombol yang disesuaikan dengan tema Rafa Kost */
    .btn-main {
        background-color: #2563eb; /* Biru cerah khas Rafa Kost */
        color: white;
        transition: background-color 0.2s ease, transform 0.1s ease;
    }
    .btn-main:hover {
        background-color: #1d4ed8; /* Biru agak gelap saat di-hover */
    }
    .btn-main:active {
        transform: scale(0.98); /* Sedikit mengecil saat diklik agar terasa organik */
    }
    .btn-text {
        background-color: transparent;
        color: #64748b;
        transition: color 0.2s ease;
    }
    .btn-text:hover {
        color: #0f172a;
    }
</style>

<div style="min-height:100vh; background-color:#f8fafc; display:flex; align-items:center; justify-content:center; padding:24px; font-family: ui-sans-serif, system-ui, sans-serif;">
    <div style="max-width:400px; width:100%; background:white; border:1px solid #e2e8f0; border-radius:20px; padding:40px 32px; box-shadow:0 4px 25px rgba(15, 23, 42, 0.04); text-align:center;">
        
        <h1 style="font-size:22px; font-weight:700; color:#0f172a; margin:0 0 12px 0; letter-spacing: -0.5px;">
            Cek email kamu
        </h1>

        <p style="font-size:15px; color:#475569; line-height:1.6; margin:0 0 28px 0;">
            Terima kasih sudah mendaftar di <b>Rafa Kost</b>. Silakan klik tautan verifikasi yang baru saja kami kirim ke email kamu.
        </p>

        @if (session('status') == 'verification-link-sent')
            <div style="color:#166534; background:#f0fdf4; border:1px solid #bbf7d0; padding:12px; border-radius:10px; margin-bottom:24px; font-size:14px; font-weight: 500;">
                Tautan verifikasi baru sudah dikirim.
            </div>
        @endif

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn-main" style="width:100%; border:0; padding:13px 16px; border-radius:10px; font-size:15px; font-weight:600; cursor:pointer; boxShadow: 0 2px 4px rgba(37, 99, 235, 0.1);">
                Kirim ulang email
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}" style="margin-top:14px;">
            @csrf
            <button type="submit" class="btn-text" style="width:100%; border:0; padding:8px 16px; font-size:14px; font-weight:500; cursor:pointer; text-decoration:underline; text-underline-offset:4px;">
                Keluar
            </button>
        </form>
    </div>
</div>
@endsection