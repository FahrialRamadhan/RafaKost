@extends('layouts.app')

@section('main_class', 'pt-0')

@section('content')
    <div class="min-h-screen" style="background: #f0f4f8;">

        {{-- Top accent bar --}}
        <div style="height: 3px; background: linear-gradient(90deg, #1a7fe8, #2563eb, #1a7fe8);"></div>

        <div class="max-w-2xl mx-auto py-10 px-6">

            {{-- Back link --}}
            <a href="{{ route('kamars.index') }}"
               style="color: #1a7fe8; font-size: 14px; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; margin-bottom: 32px; letter-spacing: 0.03em;"
               onmouseover="this.style.opacity='0.75'" onmouseout="this.style.opacity='1'">
                ← Kembali ke Data Kamar
            </a>

            {{-- Card --}}
            <div style="background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 8px 32px rgba(0,0,0,0.10);">

                {{-- Card Header --}}
                <div style="background: linear-gradient(135deg, #1a7fe8 0%, #1652a8 100%); padding: 28px 32px;">
                    <div style="display: flex; align-items: center; gap: 14px;">
                        <div style="width: 44px; height: 44px; background: rgba(255,255,255,0.2); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                            <svg width="22" height="22" fill="none" stroke="white" stroke-width="1.8" viewBox="0 0 24 24">
                                <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                            </svg>
                        </div>
                        <div>
                            <h1 style="color: #ffffff; font-size: 22px; font-weight: 700; margin: 0; letter-spacing: -0.02em;">Edit Kamar</h1>
                            <p style="color: rgba(255,255,255,0.7); font-size: 13px; margin: 3px 0 0;">
                                Mengubah data — <span style="font-weight: 600; color: white;">{{ $kamar->nama }}</span>
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Form Body --}}
                <form action="{{ route('kamars.update', $kamar->id) }}" method="POST" enctype="multipart/form-data" style="padding: 32px;">
                    @csrf
                    @method('PUT')

                    {{-- Row: Nama & Lantai --}}
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">

                        <div>
                            <label style="display: block; color: #4a5568; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 8px;">
                                Nama Kamar
                            </label>
                            <input type="text" name="nama"
                                   value="{{ old('nama', $kamar->nama) }}"
                                   style="width: 100%; background: #f8fafc; border: 1.5px solid #cbd5e0; border-radius: 10px; padding: 12px 14px; color: #1a202c; font-size: 15px; outline: none; box-sizing: border-box; transition: border-color 0.2s;"
                                   onfocus="this.style.borderColor='#1a7fe8'" onblur="this.style.borderColor='#cbd5e0'">
                            @error('nama')
                                <p style="color: #e53e3e; font-size: 12px; margin-top: 5px;">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label style="display: block; color: #4a5568; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 8px;">
                                Lantai
                            </label>
                            <input type="text" name="lantai"
                                   value="{{ old('lantai', $kamar->lantai) }}"
                                   style="width: 100%; background: #f8fafc; border: 1.5px solid #cbd5e0; border-radius: 10px; padding: 12px 14px; color: #1a202c; font-size: 15px; outline: none; box-sizing: border-box; transition: border-color 0.2s;"
                                   onfocus="this.style.borderColor='#1a7fe8'" onblur="this.style.borderColor='#cbd5e0'">
                            @error('lantai')
                                <p style="color: #e53e3e; font-size: 12px; margin-top: 5px;">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Harga --}}
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; color: #4a5568; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 8px;">
                            Harga
                        </label>
                        <div style="position: relative;">
                            <span style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #1a7fe8; font-size: 14px; font-weight: 600;">Rp</span>
                            <input type="text" name="harga"
                                   value="{{ old('harga', $kamar->harga) }}"
                                   style="width: 100%; background: #f8fafc; border: 1.5px solid #cbd5e0; border-radius: 10px; padding: 12px 14px 12px 40px; color: #1a202c; font-size: 15px; outline: none; box-sizing: border-box; transition: border-color 0.2s;"
                                   onfocus="this.style.borderColor='#1a7fe8'" onblur="this.style.borderColor='#cbd5e0'">
                        </div>
                        @error('harga')
                            <p style="color: #e53e3e; font-size: 12px; margin-top: 5px;">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Status --}}
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; color: #4a5568; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 8px;">
                            Status Kamar
                        </label>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                            <label style="cursor: pointer;">
                                <input type="radio" name="status" value="tersedia"
                                       {{ old('status', $kamar->status) == 'tersedia' ? 'checked' : '' }}
                                       style="display: none;" class="status-radio">
                                <div class="status-card" data-value="tersedia"
                                     style="border: 1.5px solid {{ old('status', $kamar->status) == 'tersedia' ? '#1a7fe8' : '#cbd5e0' }}; background: {{ old('status', $kamar->status) == 'tersedia' ? 'rgba(26,127,232,0.07)' : '#f8fafc' }}; border-radius: 10px; padding: 14px; text-align: center; transition: all 0.2s;">
                                    <div style="width: 10px; height: 10px; background: #38a169; border-radius: 50%; margin: 0 auto 8px;"></div>
                                    <span style="color: #2d3748; font-size: 14px; font-weight: 600;">Tersedia</span>
                                </div>
                            </label>
                            <label style="cursor: pointer;">
                                <input type="radio" name="status" value="terisi"
                                       {{ old('status', $kamar->status) == 'terisi' ? 'checked' : '' }}
                                       style="display: none;" class="status-radio">
                                <div class="status-card" data-value="terisi"
                                     style="border: 1.5px solid {{ old('status', $kamar->status) == 'terisi' ? '#1a7fe8' : '#cbd5e0' }}; background: {{ old('status', $kamar->status) == 'terisi' ? 'rgba(26,127,232,0.07)' : '#f8fafc' }}; border-radius: 10px; padding: 14px; text-align: center; transition: all 0.2s;">
                                    <div style="width: 10px; height: 10px; background: #e53e3e; border-radius: 50%; margin: 0 auto 8px;"></div>
                                    <span style="color: #2d3748; font-size: 14px; font-weight: 600;">Terisi</span>
                                </div>
                            </label>
                        </div>
                        @error('status')
                            <p style="color: #e53e3e; font-size: 12px; margin-top: 5px;">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Image Upload --}}
                    <div style="margin-bottom: 28px;">
                        <label style="display: block; color: #4a5568; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 8px;">
                            Foto Kamar
                        </label>

                        {{-- Current Image Preview --}}
                        @if($kamar->image)
                            <div style="display: flex; align-items: center; gap: 12px; background: #f8fafc; border: 1.5px solid #cbd5e0; border-radius: 10px; padding: 12px 14px; margin-bottom: 10px;">
                                <img src="{{ asset('storage/' . $kamar->image) }}"
                                     alt="Foto {{ $kamar->nama }}"
                                     style="width: 52px; height: 52px; object-fit: cover; border-radius: 8px; border: 1px solid #cbd5e0;">
                                <div>
                                    <p style="color: #718096; font-size: 12px; margin: 0 0 2px;">Foto saat ini</p>
                                    <p style="color: #2d3748; font-size: 13px; font-weight: 500; margin: 0;">{{ basename($kamar->image) }}</p>
                                </div>
                            </div>
                        @endif

                        <label for="image-upload" style="cursor: pointer;">
                            <div id="upload-area"
                                 style="background: #f8fafc; border: 1.5px dashed #cbd5e0; border-radius: 10px; padding: 24px; text-align: center; transition: all 0.2s;"
                                 onmouseover="this.style.borderColor='#1a7fe8'" onmouseout="this.style.borderColor='#cbd5e0'">
                                <svg width="28" height="28" fill="none" stroke="#1a7fe8" stroke-width="1.5" viewBox="0 0 24 24" style="margin: 0 auto 8px;">
                                    <path d="M4 16l4-4 4 4 4-6 4 6M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"/>
                                </svg>
                                <p id="upload-text" style="color: #718096; font-size: 14px; margin: 0;">
                                    {{ $kamar->image ? 'Klik untuk ganti foto' : 'Klik untuk unggah foto' }}
                                </p>
                                <p style="color: #a0aec0; font-size: 12px; margin: 4px 0 0;">PNG, JPG, JPEG maks 2MB</p>
                            </div>
                        </label>
                        <input type="file" name="image" id="image-upload" accept="image/*" style="display: none;"
                               onchange="document.getElementById('upload-text').textContent = this.files[0] ? this.files[0].name : '{{ $kamar->image ? 'Klik untuk ganti foto' : 'Klik untuk unggah foto' }}'">
                        @error('image')
                            <p style="color: #e53e3e; font-size: 12px; margin-top: 5px;">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Divider --}}
                    <div style="height: 1px; background: #e2e8f0; margin-bottom: 24px;"></div>

                    {{-- Actions --}}
                    <div style="display: flex; gap: 12px; justify-content: flex-end;">
                        <a href="{{ route('kamars.index') }}"
                           style="padding: 12px 24px; border-radius: 10px; border: 1.5px solid #cbd5e0; color: #718096; font-size: 15px; font-weight: 600; text-decoration: none; transition: all 0.2s;"
                           onmouseover="this.style.borderColor='#718096';this.style.color='#2d3748'"
                           onmouseout="this.style.borderColor='#cbd5e0';this.style.color='#718096'">
                            Batal
                        </a>
                        <button type="submit"
                                style="padding: 12px 28px; border-radius: 10px; background: linear-gradient(135deg, #1a7fe8, #1652a8); border: none; color: #ffffff; font-size: 15px; font-weight: 700; cursor: pointer; letter-spacing: 0.01em; transition: opacity 0.2s;"
                                onmouseover="this.style.opacity='0.88'" onmouseout="this.style.opacity='1'">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.status-radio').forEach(radio => {
            radio.addEventListener('change', () => {
                document.querySelectorAll('.status-card').forEach(card => {
                    card.style.borderColor = '#cbd5e0';
                    card.style.background = '#f8fafc';
                });
                const selected = radio.nextElementSibling;
                selected.style.borderColor = '#1a7fe8';
                selected.style.background = 'rgba(26,127,232,0.07)';
            });
        });
    </script>
@endsection