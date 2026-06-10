@extends('layouts.app')

@section('main_class', 'pt-0')

@section('content')
    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <div class="page-wrapper">
        <div class="content-container">

            {{-- Back link --}}
            <a href="{{ route('kamars.index') }}" style="color: #000000; font-size: 13px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; margin-bottom: 24px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
                Kembali
            </a>

            {{-- Header Title --}}
            <div style="margin-bottom: 32px; display: flex; gap: 8px; align-items: center;">
                <img src="{{ asset('images/frameworkpartikel.png') }}" alt="icon" style="width: 20px; height: 20px; object-fit: contain;">
                <h1 style="font-size: 16px; font-weight: 700; color: #000000; margin: 0;">Tambah Kamar</h1>
            </div>

            {{-- Form Body --}}
            <form action="{{ route('kamars.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

				{{-- Row: Nama, Lantai & Kamar Mandi --}}
				<div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 24px; margin-bottom: 20px;">
				    <div>
				        <label class="form-label">Nama Kamar</label>
				        <input type="text" name="nama" class="form-input" placeholder="Cth.Kamar A1" value="{{ old('nama') }}">
				        @error('nama') <p style="color: #e53e3e; font-size: 11px; margin-top: 6px;">{{ $message }}</p> @enderror
				    </div>
				
				    <div>
				        <label class="form-label">Lantai</label>
				        <input type="text" name="lantai" class="form-input" placeholder="Cth.Lantai 1" value="{{ old('lantai') }}">
				        @error('lantai') <p style="color: #e53e3e; font-size: 11px; margin-top: 6px;">{{ $message }}</p> @enderror
				    </div>
				
				    <div>
				        <label class="form-label">Kamar Mandi</label>
				        <input type="text" name="kamar_mandi" class="form-input" placeholder="Cth. dalam / luar" value="{{ old('kamar_mandi') }}">
				        @error('kamar_mandi') <p style="color: #e53e3e; font-size: 11px; margin-top: 6px;">{{ $message }}</p> @enderror
				    </div>
				</div>

				{{-- Harga --}}
				<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:20px;">
				    <div>
				        <label class="form-label">Harga 1 Orang</label>
				        <div style="position: relative;">
				            <span style="position:absolute;left:16px;top:50%;transform:translateY(-50%);color:#000000;font-size:13px;font-weight:700;">Rp</span>
				            <input type="number"
				                   name="harga_1_orang"
				                   class="form-input"
				                   placeholder="Contoh: 700000"
				                   value="{{ old('harga_1_orang') }}"
				                   style="padding-left:44px;">
				        </div>
				        @error('harga_1_orang') <p style="color:#e53e3e;font-size:11px;margin-top:6px;">{{ $message }}</p> @enderror
				    </div>
				
				    <div>
				        <label class="form-label">Harga 2 Orang</label>
				        <div style="position: relative;">
				            <span style="position:absolute;left:16px;top:50%;transform:translateY(-50%);color:#000000;font-size:13px;font-weight:700;">Rp</span>
				            <input type="number"
				                   name="harga_2_orang"
				                   class="form-input"
				                   placeholder="Contoh: 1000000"
				                   value="{{ old('harga_2_orang') }}"
				                   style="padding-left:44px;">
				        </div>
				        @error('harga_2_orang') <p style="color:#e53e3e;font-size:11px;margin-top:6px;">{{ $message }}</p> @enderror
				    </div>
				</div>
				{{-- Deskripsi --}}
				<div style="margin-bottom: 20px;">
				    <label class="form-label">Deskripsi Kamar</label>
				    <textarea
				        name="description"
				        rows="5"
				        class="form-input"
				        placeholder="Contoh: Kamar nyaman, bersih, cocok untuk mahasiswa atau pekerja. Sudah termasuk kasur, meja, lemari, dan akses fasilitas umum.">{{ old('description') }}</textarea>
				
				    @error('description')
				        <p style="color:#e53e3e;font-size:11px;margin-top:6px;">{{ $message }}</p>
				    @enderror
				</div>

                {{-- Status --}}
                <div style="margin-bottom: 20px;">
                    <label class="form-label">Status Kamar</label>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
                        <label style="cursor: pointer; display: block;">
                            <input type="radio" name="status" value="tersedia" {{ old('status', 'tersedia') == 'tersedia' ? 'checked' : '' }} style="display: none;" class="status-radio">
                            <div class="status-card {{ old('status', 'tersedia') == 'tersedia' ? 'active' : '' }}">
                                <div class="radio-circle"></div>
                                <span style="color: #000000; font-size: 13px; font-weight: 700;">Tersedia</span>
                            </div>
                        </label>
                        <label style="cursor: pointer; display: block;">
                            <input type="radio" name="status" value="terisi" {{ old('status') == 'terisi' ? 'checked' : '' }} style="display: none;" class="status-radio">
                            <div class="status-card {{ old('status') == 'terisi' ? 'active' : '' }}">
                                <div class="radio-circle"></div>
                                <span style="color: #000000; font-size: 13px; font-weight: 700;">Terisi</span>
                            </div>
                        </label>
                    </div>
                    @error('status') <p style="color: #e53e3e; font-size: 11px; margin-top: 6px;">{{ $message }}</p> @enderror
                </div>

				{{-- Image Upload --}}
				<div style="margin-bottom: 20px;">
				    <label class="form-label">Foto Utama Kamar</label>
				    <label for="image-upload" style="display: block;">
				        <div class="upload-area">
				            <svg width="40" height="40" fill="none" stroke="#0EA5E9" stroke-width="1.5" viewBox="0 0 24 24" style="margin: 0 auto 12px;">
				                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
				                <circle cx="8.5" cy="8.5" r="2.5"></circle>
				                <polyline points="21 15 16 10 5 21"></polyline>
				            </svg>
				            <p id="upload-text" style="color: #000000; font-size: 13px; font-weight: 700; margin: 0 0 4px;">Klik Unggah Foto Utama</p>
				            <p style="color: #94A3B8; font-size: 11px; margin: 0;">PNG, JPG, JPEG, WEBP maks 2mb</p>
				        </div>
				    </label>
				    <input type="file" name="image" id="image-upload" accept="image/*" style="display: none;"
				           onchange="document.getElementById('upload-text').textContent = this.files[0] ? this.files[0].name : 'Klik Unggah Foto Utama'">
				    @error('image') <p style="color: #e53e3e; font-size: 11px; margin-top: 6px;">{{ $message }}</p> @enderror
				</div>
				
				{{-- Gallery Upload --}}
				<div style="margin-bottom: 32px;">
				    <label class="form-label">Foto Tambahan / Gallery</label>
				    <label for="images-upload" style="display: block;">
				        <div class="upload-area">
				            <svg width="40" height="40" fill="none" stroke="#0EA5E9" stroke-width="1.5" viewBox="0 0 24 24" style="margin: 0 auto 12px;">
				                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
				                <circle cx="8.5" cy="8.5" r="2.5"></circle>
				                <polyline points="21 15 16 10 5 21"></polyline>
				            </svg>
				            <p id="gallery-upload-text" style="color: #000000; font-size: 13px; font-weight: 700; margin: 0 0 4px;">Klik Unggah Banyak Foto</p>
				            <p style="color: #94A3B8; font-size: 11px; margin: 0;">Bisa pilih lebih dari 1 foto</p>
				        </div>
				    </label>
				    <input type="file" name="images[]" id="images-upload" accept="image/*" multiple style="display: none;"
				           onchange="document.getElementById('gallery-upload-text').textContent = this.files.length ? this.files.length + ' foto dipilih' : 'Klik Unggah Banyak Foto'">
				    @error('images.*') <p style="color: #e53e3e; font-size: 11px; margin-top: 6px;">{{ $message }}</p> @enderror
				</div>

                {{-- Actions --}}
                <div style="display: flex; gap: 12px; justify-content: flex-end;">
                    <a href="{{ route('kamars.index') }}" class="btn-cancel">Batal</a>
                    <button type="submit" class="btn-save">Simpan</button>
                </div>
            </form>
        </div> 
    </div>

<style>
        html, body {
            background-color: #ffffff !important;
            font-family: 'DM Sans', sans-serif;
            color: #1E293B;
        }
        .page-wrapper { background-color: #ffffff; min-height: 100vh; padding: 40px 20px; }
        .content-container { max-width: 1140px; margin: 0 auto; width: 100%; }
        
        .form-label { display: block; font-size: 13px; font-weight: 700; color: #000000; margin-bottom: 8px; }
        .form-input {
            width: 100%; border: 1px solid #E2E8F0; border-radius: 8px; padding: 12px 16px;
            font-size: 13px; color: #1E293B; outline: none; transition: border-color 0.2s; background-color: #ffffff;
        }
        .form-input::placeholder { color: #94A3B8; }
        .form-input:focus { border-color: #0EA5E9; }

        /* Status Radio Card (Persis mockup: titik di atas, teks di bawah) */
        .status-card {
            border: 1px solid #E2E8F0; border-radius: 8px; padding: 24px; display: flex; flex-direction: column;
            align-items: center; justify-content: center; gap: 12px; cursor: pointer; transition: all 0.2s ease;
        }
        .status-card.active { border-color: #0EA5E9; }
        .radio-circle {
            width: 18px; height: 18px; border-radius: 50%; border: 1.5px solid #0EA5E9;
            position: relative; transition: all 0.2s ease; display: flex; align-items: center; justify-content: center;
        }
        .radio-circle::after {
            content: ''; width: 10px; height: 10px; background-color: transparent; border-radius: 50%; transition: 0.2s;
        }
        .status-card.active .radio-circle::after { background-color: #0EA5E9; }

        /* Upload Area */
        .upload-area {
            border: 1px dashed #CBD5E1; border-radius: 8px; padding: 40px 20px; text-align: center;
            cursor: pointer; transition: border-color 0.2s; background-color: #ffffff;
        }
        .upload-area:hover { border-color: #0EA5E9; }

        /* Buttons */
        .btn-cancel {
            background-color: #F8FAFC; color: #475569; border: 1px solid #E2E8F0; padding: 10px 32px;
            border-radius: 6px; font-size: 13px; font-weight: 600; text-decoration: none; transition: 0.2s;
        }
        .btn-cancel:hover { background-color: #F1F5F9; }
        .btn-save {
            background-color: #0EA5E9; color: #ffffff; border: none; padding: 10px 32px;
            border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer; transition: 0.2s;
        }
        .btn-save:hover { opacity: 0.85; }
    </style>

    <script>
        document.querySelectorAll('.status-radio').forEach(radio => {
            radio.addEventListener('change', () => {
                document.querySelectorAll('.status-card').forEach(card => card.classList.remove('active'));
                if(radio.checked) radio.nextElementSibling.classList.add('active');
            });
        });
    </script>
@endsection