@extends('layouts.app')
@section('content')
@include('layouts.navigation-user')

@php
    if (empty($kamar)) abort(404);
@endphp

<div class="detail-font bg-gray-200 min-h-screen pt-20">

    {{-- Gallery Modal --}}
    <div id="galleryModal"
         class="fixed inset-0 z-[99999] bg-black/80 hidden items-center justify-center p-6"
         style="display:none">
        <button type="button" onclick="closeGalleryModal()"
                class="absolute top-4 right-5 w-10 h-10 rounded-full bg-white/90 text-gray-900 text-2xl font-bold leading-none flex items-center justify-center hover:bg-white">
            ×
        </button>
        <button type="button" onclick="prevGalleryImage()"
                class="absolute left-5 top-1/2 -translate-y-1/2 w-11 h-11 rounded-full bg-white/90 text-gray-900 text-3xl font-bold leading-none flex items-center justify-center hover:bg-white z-10">
            ‹
        </button>
        <div class="relative flex items-center justify-center max-w-4xl w-full max-h-[86vh]">
            <img id="galleryModalImage" src="" alt="Foto kamar"
                 class="max-w-full max-h-[82vh] object-contain rounded-2xl shadow-2xl bg-gray-900">
            <div id="galleryModalCounter"
                 class="absolute -bottom-9 left-1/2 -translate-x-1/2 text-white text-xs font-bold bg-black/45 px-3 py-1.5 rounded-full">
            </div>
        </div>
        <button type="button" onclick="nextGalleryImage()"
                class="absolute right-5 top-1/2 -translate-y-1/2 w-11 h-11 rounded-full bg-white/90 text-gray-900 text-3xl font-bold leading-none flex items-center justify-center hover:bg-white z-10">
            ›
        </button>
    </div>

    <div class="max-w-[1160px] mx-auto px-5 pt-7 pb-20 grid grid-cols-1 lg:grid-cols-[1fr_340px] gap-6 items-start">

        {{-- ========== LEFT COL ========== --}}
        <div class="flex flex-col gap-5">

            {{-- Gallery --}}
            @php
                $galleryImages = [];
                if (!empty($kamar->images)) {
                    $decoded = json_decode($kamar->images, true);
                    $galleryImages = is_array($decoded) ? $decoded : [];
                }
                if (!empty($kamar->image) && !in_array($kamar->image, $galleryImages)) {
                    array_unshift($galleryImages, $kamar->image);
                }
                $mainImage = $galleryImages[0] ?? null;
                $thumbOne  = $galleryImages[1] ?? null;
                $thumbTwo  = $galleryImages[2] ?? null;
            @endphp

            <div class="rounded-2xl overflow-hidden grid grid-cols-2 gap-1 bg-gray-300"
                 style="grid-template-rows: 210px 150px;">
                {{-- Main --}}
                <div class="row-span-2 bg-gray-300 overflow-hidden cursor-pointer group relative"
                     onclick="openGalleryModal(0)">
                    @if($mainImage)
                        <img src="{{ Storage::url($mainImage) }}" alt="Foto utama"
                             class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-400">Tidak ada foto</div>
                    @endif
                </div>
                {{-- Thumb 1 --}}
                <div class="bg-gray-200 overflow-hidden cursor-pointer group" onclick="openGalleryModal(1)">
                    @if($thumbOne)
                        <img src="{{ Storage::url($thumbOne) }}" alt="Foto 2"
                             class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-400">Foto</div>
                    @endif
                </div>
                {{-- Thumb 2 --}}
                <div class="bg-gray-200 overflow-hidden cursor-pointer group relative" onclick="openGalleryModal(2)">
                    @if($thumbTwo)
                        <img src="{{ Storage::url($thumbTwo) }}" alt="Foto 3"
                             class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-400">Foto</div>
                    @endif
                    @if(count($galleryImages) > 3)
                        <div class="absolute inset-0 bg-gray-900/45 backdrop-blur-[2px] flex items-center justify-center text-white text-lg font-bold pointer-events-none">
                            +{{ count($galleryImages) - 3 }} foto
                        </div>
                    @endif
                </div>
            </div>

            {{-- Room Header Card --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-4">
                    <div>
                        <h1 class="text-2xl font-extrabold text-gray-900 leading-tight">{{ $kamar->nama }}</h1>
                        <p class="text-xs text-gray-400 mt-1 flex items-center gap-1">
                            {{ $kamar->address ?? 'Rafa Kost / Purwokerto' }}
                        </p>
                    </div>
                    <div class="sm:text-right">
                        <div class="text-xl font-extrabold text-gray-900">
                            Rp {{ number_format($kamar->harga_1_orang ?? $kamar->harga, 0, ',', '.') }}
                        </div>
                        <div class="text-[11px] text-gray-400 font-medium">/ Bulan untuk 1 orang</div>
                    </div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5">
                    @php
                        $specs = [
                            ['label' => 'KASUR',       'value' => $kamar->bed_type ?? 'Single Bed'],
                            ['label' => 'KAMAR MANDI', 'value' => strtoupper($kamar->bathroom_type ?? $kamar->kamar_mandi ?? 'luar')],
                            ['label' => 'PENYIMPANAN', 'value' => $kamar->size ?? 'Lemari'],
                            ['label' => 'LISTRIK',     'value' => $kamar->electricity ?? 'Bebas (Normal)'],
                        ];
                    @endphp
                    @foreach($specs as $spec)
                        <div class="flex flex-col items-center justify-center gap-2 py-4 px-3 bg-gray-50 border border-gray-200 rounded-lg text-center cursor-default transition-all duration-200 hover:bg-blue-50 hover:border-blue-300 group">
                            <span class="text-[11px] font-extrabold text-gray-900 uppercase tracking-wide transition-colors duration-200 group-hover:text-blue-600">{{ $spec['label'] }}</span>
                            <span class="text-[11px] font-medium text-gray-400 whitespace-nowrap transition-colors duration-200 group-hover:text-blue-400">{{ $spec['value'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Deskripsi --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                <h2 class="text-[17px] font-extrabold text-gray-900 mb-3">Deskripsi</h2>
                <p class="text-[13px] leading-7 text-gray-500">
                    {{ $kamar->description ?? 'Kamar ini cocok untuk kamu yang membutuhkan tempat tinggal yang nyaman dan praktis. Kondisi kamar bersih dan cukup untuk aktivitas sehari-hari.' }}
                </p>
            </div>

            {{-- Fasilitas --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                <h2 class="text-[17px] font-extrabold text-gray-900 mb-3">Fasilitas Umum</h2>
                <div class="flex flex-wrap gap-2">
                    @php
                        $defaultFacilities = ['Wifi Gratis','Air Bersih','Listrik','Dapur Bersama','Parkir','Kulkas Bersama'];
                        $facilities = $kamar->facilities ?? $defaultFacilities;
                    @endphp
                    @foreach($facilities as $facility)
                        <div class="flex items-center gap-1.5 px-3.5 py-2 bg-blue-50 border border-blue-200 rounded-md text-xs font-semibold text-blue-600 cursor-default transition hover:bg-blue-600 hover:text-white hover:border-blue-600">
                            {{ is_array($facility) ? ($facility['label'] ?? '-') : $facility }}
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Peraturan --}}
            @if(!empty($kamar->rules))
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                    <h2 class="text-[17px] font-extrabold text-gray-900 mb-3">Peraturan Kost</h2>
                    <ul class="flex flex-col gap-2.5">
                        @foreach($kamar->rules as $rule)
                            <li class="flex items-center gap-2.5 text-[13px] text-gray-500">
                                <span class="w-1.5 h-1.5 rounded-full bg-blue-600 flex-shrink-0"></span>
                                {{ $rule }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

        </div>

        {{-- ========== RIGHT COL (Booking Card) ========== --}}
        <div class="lg:sticky lg:top-6 z-[1]">
            <div class="bg-white rounded-2xl shadow-md border border-gray-200 overflow-hidden">

                {{-- Header --}}
                <div class="px-5 pt-5 pb-4 border-b border-gray-200">
                    <div class="text-xl font-extrabold text-gray-900">Rafa Kost</div>
                    <div class="flex items-center justify-between mt-2.5">
                        <span class="text-[11px] font-bold text-gray-500 uppercase tracking-wide">Booking Kamar</span>
                        <span class="text-[10px] font-bold px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800 uppercase tracking-wide">Tersedia</span>
                    </div>
                </div>

                {{-- Body --}}
                <div class="px-5 pt-4 pb-5 flex flex-col gap-3.5">

                    {{-- Tanggal --}}
                    <div class="flex flex-col gap-1">
                        <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wide">Tanggal Masuk</label>
                        <input type="date" name="tanggal_masuk"
                               value="{{ old('tanggal_masuk', date('Y-m-d')) }}"
                               min="{{ date('Y-m-d') }}"
                               class="w-full px-3 py-2.5 border-[1.5px] border-gray-200 rounded-lg text-[13px] font-[inherit] text-gray-900 bg-gray-50 outline-none transition focus:border-blue-500 focus:ring-[3px] focus:ring-blue-100 focus:bg-white">
                    </div>

                    {{-- Durasi --}}
                    <div class="flex flex-col gap-1">
                        <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wide">Durasi Sewa</label>
                        <select name="durasi" id="durasiSelect"
                                class="w-full px-3 py-2.5 border-[1.5px] border-gray-200 rounded-lg text-[13px] font-[inherit] text-gray-900 bg-gray-50 outline-none transition focus:border-blue-500 focus:ring-[3px] focus:ring-blue-100 focus:bg-white appearance-none"
                                style="background-image:url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%239CA3AF' stroke-width='2.5'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E\");background-repeat:no-repeat;background-position:right 13px center;padding-right:34px;">
                            <option value="1">1 Bulan</option>
                            <option value="3">3 Bulan</option>
                            <option value="6">6 Bulan</option>
                            <option value="12">12 Bulan</option>
                        </select>
                    </div>

                    {{-- Orang --}}
                    <div class="flex flex-col gap-1">
                        <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wide">Orang</label>
                        <select name="orang" id="orangSelect"
                                class="w-full px-3 py-2.5 border-[1.5px] border-gray-200 rounded-lg text-[13px] font-[inherit] text-gray-900 bg-gray-50 outline-none transition focus:border-blue-500 focus:ring-[3px] focus:ring-blue-100 focus:bg-white appearance-none"
                                style="background-image:url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%239CA3AF' stroke-width='2.5'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E\");background-repeat:no-repeat;background-position:right 13px center;padding-right:34px;">
                            <option value="1">1 Orang</option>
                            <option value="2">2 Orang</option>
                        </select>
                    </div>

                    {{-- Divider Data Penyewa --}}
                    <div class="flex items-center gap-2 text-[11px] font-bold text-gray-400 uppercase tracking-wide my-0.5">
                        <span class="flex-1 h-px bg-gray-200"></span>
                        Data Penyewa
                        <span class="flex-1 h-px bg-gray-200"></span>
                    </div>

                    {{-- Nama --}}
                    <div class="flex flex-col gap-1">
                        <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wide">Nama Lengkap</label>
                        <input type="text" name="customer_name" id="customerName"
                               value="{{ old('customer_name', auth()->user()->name ?? '') }}"
                               placeholder="Masukkan nama lengkap"
                               oninput="this.value = this.value.replace(/[^a-zA-ZÀ-ÿ\s]/g, '')"
                               class="w-full px-3 py-2.5 border-[1.5px] border-gray-200 rounded-lg text-[13px] font-[inherit] text-gray-900 bg-gray-50 outline-none transition focus:border-blue-500 focus:ring-[3px] focus:ring-blue-100 focus:bg-white">
                    </div>

                    {{-- No WhatsApp --}}
                    <div class="flex flex-col gap-1">
                        <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wide">No WhatsApp</label>
                        <input type="text" name="customer_phone" id="customerPhone"
                               value="{{ old('customer_phone', auth()->user()->phone ?? '') }}"
                               placeholder="Contoh: 081234567890"
                               oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                               class="w-full px-3 py-2.5 border-[1.5px] border-gray-200 rounded-lg text-[13px] font-[inherit] text-gray-900 bg-gray-50 outline-none transition focus:border-blue-500 focus:ring-[3px] focus:ring-blue-100 focus:bg-white">
                    </div>

                    {{-- Email --}}
                    <div class="flex flex-col gap-1">
                        <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wide">Email</label>
                        <input type="email" name="customer_email" id="customerEmail"
                               value="{{ old('customer_email', auth()->user()->email ?? '') }}"
                               placeholder="nama@email.com"
                               class="w-full px-3 py-2.5 border-[1.5px] border-gray-200 rounded-lg text-[13px] font-[inherit] text-gray-900 bg-gray-50 outline-none transition focus:border-blue-500 focus:ring-[3px] focus:ring-blue-100 focus:bg-white">
                    </div>

                    {{-- Alamat Lengkap --}}
<div class="flex flex-col gap-1">
    <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wide">Alamat Lengkap</label>
    <textarea name="customer_address" id="customerAddress" rows="3"
              placeholder="Masukkan alamat lengkap"
              maxlength="100"
              oninput="updateCounter(this, 'counterAddress', 100)"
              class="w-full px-3 py-2.5 border-[1.5px] border-gray-200 rounded-lg text-[13px] font-[inherit] text-gray-900 bg-gray-50 outline-none transition resize-y min-h-[70px] focus:border-blue-500 focus:ring-[3px] focus:ring-blue-100 focus:bg-white"></textarea>
    <span id="counterAddress" class="text-[11px] text-gray-400 text-right">0 / 100</span>
</div>

{{-- Catatan --}}
<div class="flex flex-col gap-1">
    <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wide">Catatan Tambahan</label>
    <textarea name="customer_note" id="customerNote" rows="2"
              placeholder="Opsional"
              maxlength="50"
              oninput="updateCounter(this, 'counterNote', 50)"
              class="w-full px-3 py-2.5 border-[1.5px] border-gray-200 rounded-lg text-[13px] font-[inherit] text-gray-900 bg-gray-50 outline-none transition resize-y min-h-[58px] focus:border-blue-500 focus:ring-[3px] focus:ring-blue-100 focus:bg-white"></textarea>
    <span id="counterNote" class="text-[11px] text-gray-400 text-right">0 / 50</span>
</div>

                    {{-- Summary --}}
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-3.5 flex flex-col gap-2">
                        <div class="flex items-center gap-1.5 text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-0.5">
                            Rangkuman
                        </div>
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-gray-500">Harga Sewa</span>
                            <span class="font-bold text-gray-900" id="hargaSewa">Rp {{ number_format($kamar->harga_1_orang ?? $kamar->harga, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-gray-500">Durasi Sewa</span>
                            <span class="font-bold text-gray-900" id="durasiLabel">1 Bulan</span>
                        </div>
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-gray-500">Orang</span>
                            <span class="font-bold text-gray-900" id="orangLabel">1 Orang</span>
                        </div>
                        <div class="h-px bg-gray-200 my-0.5"></div>
                        <div class="flex items-center justify-between">
                            <span class="text-[13px] font-bold text-gray-900">Total Harga</span>
                            <span class="text-sm font-extrabold text-gray-900" id="totalHarga">Rp {{ number_format($kamar->harga_1_orang ?? $kamar->harga, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    {{-- Tombol Booking --}}
                    <button type="button" onclick="submitBooking()"
                            class="w-full py-3.5 bg-blue-600 hover:bg-blue-700 active:translate-y-0 hover:-translate-y-px text-white text-sm font-bold rounded-lg transition-all shadow-sm hover:shadow-blue-300/50 hover:shadow-lg">
                        Sewa Sekarang
                    </button>

                    <p class="text-center text-[10px] text-gray-400 leading-relaxed">
                        Pembayaran akan dikonfirmasi oleh pengelola kost.<br>
                        Tidak ada biaya tambahan tersembunyi.
                    </p>

                </div>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

	function updateCounter(el, counterId, max) {
        const len = el.value.length;
        const counter = document.getElementById(counterId);
        counter.textContent = len + ' / ' + max;
        counter.classList.toggle('text-red-500', len >= max);
        counter.classList.toggle('text-gray-400', len < max);
    }
	
    const galleryImages = @json(
        collect($galleryImages)->map(fn ($img) => Storage::url($img))->values()
    );

    let currentGalleryIndex = 0;

    function openGalleryModal(index = 0) {
        if (!galleryImages.length) return;
        currentGalleryIndex = Math.max(0, Math.min(index, galleryImages.length - 1));
        updateGalleryModal();
        const modal = document.getElementById('galleryModal');
        if (modal) { modal.style.display = 'flex'; document.body.style.overflow = 'hidden'; }
    }

    function closeGalleryModal() {
        const modal = document.getElementById('galleryModal');
        if (modal) { modal.style.display = 'none'; document.body.style.overflow = ''; }
    }

    function updateGalleryModal() {
        const img     = document.getElementById('galleryModalImage');
        const counter = document.getElementById('galleryModalCounter');
        if (!img || !counter || !galleryImages.length) return;
        img.src = galleryImages[currentGalleryIndex];
        counter.textContent = `${currentGalleryIndex + 1} / ${galleryImages.length}`;
    }

    function nextGalleryImage() {
        if (!galleryImages.length) return;
        currentGalleryIndex = (currentGalleryIndex + 1) % galleryImages.length;
        updateGalleryModal();
    }

    function prevGalleryImage() {
        if (!galleryImages.length) return;
        currentGalleryIndex = (currentGalleryIndex - 1 + galleryImages.length) % galleryImages.length;
        updateGalleryModal();
    }

    document.addEventListener('keydown', function (e) {
        const modal = document.getElementById('galleryModal');
        if (!modal || modal.style.display === 'none') return;
        if (e.key === 'Escape')       closeGalleryModal();
        if (e.key === 'ArrowRight')   nextGalleryImage();
        if (e.key === 'ArrowLeft')    prevGalleryImage();
    });

    document.addEventListener('click', function (e) {
        const modal = document.getElementById('galleryModal');
        if (modal && e.target === modal) closeGalleryModal();
    });

    (function () {
        const harga1 = {{ (int) ($kamar->harga_1_orang ?? $kamar->harga) }};
        const harga2 = {{ (int) ($kamar->harga_2_orang ?? $kamar->harga) }};

        const fmt = n => 'Rp ' + n.toLocaleString('id-ID');

        const durasiSelect = document.getElementById('durasiSelect');
        const orangSelect  = document.getElementById('orangSelect');

        function updateSummary() {
            const bulan = parseInt(durasiSelect.value || 1);
            const orang = parseInt(orangSelect.value || 1);
            const hargaPerBulan = orang >= 2 ? harga2 : harga1;
            const total = hargaPerBulan * bulan;
            document.getElementById('hargaSewa').textContent  = fmt(hargaPerBulan);
            document.getElementById('durasiLabel').textContent = bulan + ' Bulan';
            document.getElementById('orangLabel').textContent  = orang + ' Orang';
            document.getElementById('totalHarga').textContent  = fmt(total);
        }

        durasiSelect?.addEventListener('change', updateSummary);
        orangSelect?.addEventListener('change', updateSummary);
        updateSummary();

        window.submitBooking = function () {
            const tanggal        = document.querySelector('[name="tanggal_masuk"]')?.value;
            const durasi         = durasiSelect?.value;
            const orang          = orangSelect?.value;
            const customerName   = document.getElementById('customerName')?.value?.trim();
            const customerPhone  = document.getElementById('customerPhone')?.value?.trim();
            const customerEmail  = document.getElementById('customerEmail')?.value?.trim();
            const customerAddress = document.getElementById('customerAddress')?.value?.trim();
            const customerNote   = document.getElementById('customerNote')?.value?.trim();

            if (!tanggal) {
                Swal.fire({ icon:'warning', title:'Tanggal belum dipilih', text:'Harap pilih tanggal masuk terlebih dahulu.', confirmButtonColor:'#0ea5e9' });
                return;
            }
            if (!customerName) {
                Swal.fire({ icon:'warning', title:'Nama belum diisi', text:'Harap isi nama lengkap terlebih dahulu.', confirmButtonColor:'#0ea5e9' });
                return;
            }
            if (!customerPhone) {
                Swal.fire({ icon:'warning', title:'Nomor WhatsApp belum diisi', text:'Harap isi nomor WhatsApp terlebih dahulu.', confirmButtonColor:'#0ea5e9' });
                return;
            }

            const params = new URLSearchParams({
                tanggal_masuk: tanggal, durasi, orang,
                customer_name: customerName,
                customer_phone: customerPhone,
                customer_email: customerEmail || '',
                customer_address: customerAddress || '',
                customer_note: customerNote || ''
            });

            Swal.fire({
                title: 'Apakah sudah yakin?',
                text: 'Apakah kamu yakin ingin dengan pesanannya?',
                imageUrl: "{{ asset('images/kasur.png') }}",
                imageWidth: 74, imageHeight: 74, imageAlt: 'Kasur',
                showCancelButton: true,
                confirmButtonText: 'Lanjut',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                buttonsStyling: false,
                customClass: {
                    popup: 'booking-confirm-popup',
                    title: 'booking-confirm-title',
                    htmlContainer: 'booking-confirm-text',
                    confirmButton: 'booking-confirm-btn',
                    cancelButton: 'booking-cancel-btn',
                    image: 'booking-swal-image'
                }
            }).then(result => {
                if (result.isConfirmed) {
                    window.location.href = `/booking/{{ $kamar->id ?? 1 }}?` + params.toString();
                }
            });
        };
    })();
</script>

{{-- Fontnya disini bos --}}
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
    .detail-font, .detail-font * { font-family: 'Plus Jakarta Sans', sans-serif !important; }
</style>

{{-- SweetAlert2 custom styles --}}
<style>
    .booking-confirm-popup  { border-radius:18px!important; padding:34px 36px 28px!important; width:590px!important; }
    .booking-confirm-title  { font-size:28px!important; font-weight:800!important; color:#111827!important; }
    .booking-confirm-text   { font-size:15px!important; color:#8b8b8b!important; line-height:1.6!important; }
    .booking-confirm-btn    { background:#0ea5e9!important; color:#fff!important; border:0!important; border-radius:6px!important; padding:10px 28px!important; font-size:13px!important; font-weight:600!important; margin-left:8px!important; }
    .booking-cancel-btn     { background:#f3f4f6!important; color:#4b5563!important; border:0!important; border-radius:6px!important; padding:10px 28px!important; font-size:13px!important; font-weight:500!important; margin-right:8px!important; }
    .booking-swal-image     { margin-top:8px!important; margin-bottom:8px!important; object-fit:contain!important; }
    @media(max-width:640px) {
        .booking-confirm-popup { width:92%!important; padding:28px 22px 24px!important; }
        .booking-confirm-title { font-size:23px!important; }
        .booking-confirm-text  { font-size:13px!important; }
    }
</style>

@include('layouts.footer')

@endsection