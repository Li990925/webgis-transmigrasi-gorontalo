@extends('admin.layout')

@section('title', 'Tambah Kawasan Transmigrasi')
@section('header-title', 'Tambah Data Kawasan Baru')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-extrabold text-gray-900">Formulir Pendaftaran Kawasan Baru</h1>
            <p class="text-xs text-gray-500 mt-0.5">Input data demografi, capaian sertifikasi hak milik, dan koordinat spasial PostGIS.</p>
        </div>
        <a href="{{ route('admin.kawasan.index') }}" class="px-3 py-2 rounded-xl bg-white hover:bg-gray-50 text-gray-700 text-xs font-semibold border border-gray-300 shadow-2xs transition-colors flex items-center gap-1.5 cursor-pointer">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali
        </a>
    </div>

    @if ($errors->any())
        <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs">
            <div class="font-bold mb-1.5">Terjadi kesalahan pengisian formulir:</div>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.kawasan.store') }}" method="POST" class="space-y-6">
        @csrf

        <!-- Section 1: Identitas & Lokasi Administratif -->
        <div class="p-6 rounded-xl bg-white border border-gray-200 shadow-sm space-y-4">
            <h2 class="text-sm font-bold text-gray-900 flex items-center gap-2 border-b border-gray-200 pb-3">
                <span class="w-6 h-6 rounded-lg bg-blue-100 text-blue-900 flex items-center justify-center text-xs font-extrabold">1</span>
                Identitas Kawasan & Administrasi Wilayah
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 text-xs">
                <div>
                    <label class="block font-bold text-gray-700 mb-1">Nama Kawasan <span class="text-rose-500">*</span></label>
                    <input type="text" name="nama" required value="{{ old('nama') }}" placeholder="Contoh: UPT Ayula Baru"
                        class="w-full py-2.5 px-3 rounded-xl bg-gray-50 border border-gray-300 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#1a73e8]">
                </div>

                <div>
                    <label class="block font-bold text-gray-700 mb-1">Tipe Kawasan <span class="text-rose-500">*</span></label>
                    <select name="tipe" required class="w-full py-2.5 px-3 rounded-xl bg-gray-50 border border-gray-300 text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#1a73e8]">
                        <option value="UPT" {{ old('tipe') === 'UPT' ? 'selected' : '' }}>UPT (Unit Pemukiman Transmigrasi)</option>
                        <option value="KTM" {{ old('tipe') === 'KTM' ? 'selected' : '' }}>KTM (Kota Terpadu Mandiri)</option>
                    </select>
                </div>

                <div>
                    <label class="block font-bold text-gray-700 mb-1">Kabupaten <span class="text-rose-500">*</span></label>
                    <select name="kabupaten" required class="w-full py-2.5 px-3 rounded-xl bg-gray-50 border border-gray-300 text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#1a73e8]">
                        @if ($user->isSuperAdmin())
                            @foreach(['Kabupaten Boalemo', 'Kabupaten Pohuwato', 'Kabupaten Gorontalo', 'Kabupaten Gorontalo Utara'] as $kab)
                                <option value="{{ $kab }}" {{ old('kabupaten') === $kab ? 'selected' : '' }}>{{ $kab }}</option>
                            @endforeach
                        @else
                            <option value="{{ $user->kabupaten }}" selected>{{ $user->kabupaten }}</option>
                        @endif
                    </select>
                </div>

                <div>
                    <label class="block font-bold text-gray-700 mb-1">Kecamatan <span class="text-rose-500">*</span></label>
                    <input type="text" name="kecamatan" required value="{{ old('kecamatan') }}" placeholder="Contoh: Tilamuta"
                        class="w-full py-2.5 px-3 rounded-xl bg-gray-50 border border-gray-300 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#1a73e8]">
                </div>

                <div>
                    <label class="block font-bold text-gray-700 mb-1">Desa <span class="text-rose-500">*</span></label>
                    <input type="text" name="desa" required value="{{ old('desa') }}" placeholder="Contoh: Ayula"
                        class="w-full py-2.5 px-3 rounded-xl bg-gray-50 border border-gray-300 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#1a73e8]">
                </div>

                <div>
                    <label class="block font-bold text-gray-700 mb-1">Tahun Penempatan <span class="text-rose-500">*</span></label>
                    <input type="number" name="tahun_penempatan" required value="{{ old('tahun_penempatan', date('Y')) }}" min="1980" max="2030"
                        class="w-full py-2.5 px-3 rounded-xl bg-gray-50 border border-gray-300 text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#1a73e8]">
                </div>
            </div>
        </div>

        <!-- Section 2: Demografi & Legalitas Hak Milik -->
        <div class="p-6 rounded-xl bg-white border border-gray-200 shadow-sm space-y-4">
            <h2 class="text-sm font-bold text-gray-900 flex items-center gap-2 border-b border-gray-200 pb-3">
                <span class="w-6 h-6 rounded-lg bg-yellow-100 text-yellow-800 flex items-center justify-center text-xs font-extrabold">2</span>
                Demografi Penduduk & Sertifikasi Tanah (SHM)
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 text-xs">
                <div>
                    <label class="block font-bold text-gray-700 mb-1">Jumlah KK <span class="text-rose-500">*</span></label>
                    <input type="number" name="jumlah_kk" id="input_kk" required value="{{ old('jumlah_kk', 0) }}" min="1"
                        class="w-full py-2.5 px-3 rounded-xl bg-gray-50 border border-gray-300 text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#1a73e8]">
                </div>

                <div>
                    <label class="block font-bold text-gray-700 mb-1">Jumlah Jiwa <span class="text-rose-500">*</span></label>
                    <input type="number" name="jumlah_jiwa" required value="{{ old('jumlah_jiwa', 0) }}" min="1"
                        class="w-full py-2.5 px-3 rounded-xl bg-gray-50 border border-gray-300 text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#1a73e8]">
                </div>

                <div>
                    <label class="block font-bold text-gray-700 mb-1">KK Sudah SHM <span class="text-rose-500">*</span></label>
                    <input type="number" name="kk_shm" id="input_shm" required value="{{ old('kk_shm', 0) }}" min="0"
                        class="w-full py-2.5 px-3 rounded-xl bg-gray-50 border border-gray-300 text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#1a73e8]">
                </div>

                <div>
                    <label class="block font-bold text-gray-700 mb-1">KK Belum SHM (Non-SHM) <span class="text-rose-500">*</span></label>
                    <input type="number" name="kk_non_shm" id="input_non_shm" required value="{{ old('kk_non_shm', 0) }}" min="0"
                        class="w-full py-2.5 px-3 rounded-xl bg-gray-50 border border-gray-300 text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#1a73e8]">
                </div>

                <div>
                    <label class="block font-bold text-gray-700 mb-1">Luas Kawasan (Hektar) <span class="text-rose-500">*</span></label>
                    <input type="number" step="0.01" name="luas_ha" required value="{{ old('luas_ha', 0) }}" min="0.1"
                        class="w-full py-2.5 px-3 rounded-xl bg-gray-50 border border-gray-300 text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#1a73e8]">
                </div>

                <div>
                    <label class="block font-bold text-gray-700 mb-1">Status Perkembangan <span class="text-rose-500">*</span></label>
                    <select name="status_perkembangan" required class="w-full py-2.5 px-3 rounded-xl bg-gray-50 border border-gray-300 text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#1a73e8]">
                        <option value="Pembinaan" {{ old('status_perkembangan') === 'Pembinaan' ? 'selected' : '' }}>Pembinaan</option>
                        <option value="Berkembang" {{ old('status_perkembangan') === 'Berkembang' ? 'selected' : '' }}>Berkembang</option>
                        <option value="Mandiri" {{ old('status_perkembangan') === 'Mandiri' ? 'selected' : '' }}>Mandiri</option>
                    </select>
                </div>

                <div>
                    <label class="block font-bold text-gray-700 mb-1">Skor Kemandirian (0 - 100)</label>
                    <input type="number" name="skor_kemandirian" value="{{ old('skor_kemandirian', 70) }}" min="0" max="100"
                        class="w-full py-2.5 px-3 rounded-xl bg-gray-50 border border-gray-300 text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#1a73e8]">
                </div>

                <div>
                    <label class="block font-bold text-gray-700 mb-1">Komoditas Unggulan</label>
                    <input type="text" name="komoditas_unggulan" value="{{ old('komoditas_unggulan') }}" placeholder="Pisang, Jagung, Kelapa"
                        class="w-full py-2.5 px-3 rounded-xl bg-gray-50 border border-gray-300 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#1a73e8]">
                </div>
            </div>
        </div>

        <!-- Section 3: Koordinat Spasial PostGIS -->
        <div class="p-6 rounded-xl bg-white border border-gray-200 shadow-sm space-y-4">
            <h2 class="text-sm font-bold text-gray-900 flex items-center gap-2 border-b border-gray-200 pb-3">
                <span class="w-6 h-6 rounded-lg bg-blue-100 text-blue-900 flex items-center justify-center text-xs font-extrabold">3</span>
                Koordinat Titik Spasial (PostGIS Point SRID 4326)
            </h2>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
                <div class="lg:col-span-4 space-y-3 text-xs">
                    <div>
                        <label class="block font-bold text-gray-700 mb-1">Latitude (Lintang) <span class="text-rose-500">*</span></label>
                        <input type="number" step="0.0000001" name="latitude" id="input_latitude" required value="{{ old('latitude', '0.550000') }}"
                            class="w-full py-2.5 px-3 rounded-xl bg-gray-50 border border-gray-300 text-gray-900 font-mono focus:outline-none focus:ring-2 focus:ring-[#1a73e8]">
                    </div>

                    <div>
                        <label class="block font-bold text-gray-700 mb-1">Longitude (Bujur) <span class="text-rose-500">*</span></label>
                        <input type="number" step="0.0000001" name="longitude" id="input_longitude" required value="{{ old('longitude', '122.350000') }}"
                            class="w-full py-2.5 px-3 rounded-xl bg-gray-50 border border-gray-300 text-gray-900 font-mono focus:outline-none focus:ring-2 focus:ring-[#1a73e8]">
                    </div>

                    <div class="p-3 rounded-xl bg-blue-50 border border-blue-200 text-[11px] text-blue-900">
                        <span class="font-bold">Tips:</span> Anda dapat mengklik langsung di atas peta di sebelah kanan untuk menentukan koordinat pusat titik secara presisi. Poligon batas dapat diedit lebih lanjut melalui menu <span class="font-bold underline">Editor Spasial</span> setelah disimpan.
                    </div>
                </div>

                <div class="lg:col-span-8">
                    <div id="picker-map" class="w-full h-64 rounded-xl border border-gray-300 overflow-hidden relative z-0"></div>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex items-center justify-end gap-3 pt-2">
            <a href="{{ route('admin.kawasan.index') }}" class="px-5 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold border border-gray-300 transition-colors">
                Batal
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-yellow-500 hover:bg-yellow-400 text-slate-950 text-xs font-bold shadow-sm transition-all flex items-center gap-2 cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span>Simpan Kawasan Baru</span>
            </button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const latInput = document.getElementById('input_latitude');
    const lngInput = document.getElementById('input_longitude');
    
    // Auto sync KK Non-SHM
    const kkInput = document.getElementById('input_kk');
    const shmInput = document.getElementById('input_shm');
    const nonShmInput = document.getElementById('input_non_shm');

    function syncShm() {
        const total = parseInt(kkInput.value) || 0;
        const shm = parseInt(shmInput.value) || 0;
        if (total > 0 && shm <= total) {
            nonShmInput.value = total - shm;
        }
    }
    kkInput?.addEventListener('input', syncShm);
    shmInput?.addEventListener('input', syncShm);

    // Mini Map Picker
    const initLat = parseFloat(latInput.value) || 0.65;
    const initLng = parseFloat(lngInput.value) || 122.50;

    const map = L.map('picker-map').setView([initLat, initLng], 9);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);

    let marker = L.marker([initLat, initLng], { draggable: true }).addTo(map);

    marker.on('dragend', function(e) {
        const pos = e.target.getLatLng();
        latInput.value = pos.lat.toFixed(6);
        lngInput.value = pos.lng.toFixed(6);
    });

    map.on('click', function(e) {
        marker.setLatLng(e.latlng);
        latInput.value = e.latlng.lat.toFixed(6);
        lngInput.value = e.latlng.lng.toFixed(6);
    });

    latInput.addEventListener('change', function() {
        const lat = parseFloat(latInput.value);
        const lng = parseFloat(lngInput.value);
        if (!isNaN(lat) && !isNaN(lng)) {
            marker.setLatLng([lat, lng]);
            map.panTo([lat, lng]);
        }
    });

    lngInput.addEventListener('change', function() {
        const lat = parseFloat(latInput.value);
        const lng = parseFloat(lngInput.value);
        if (!isNaN(lat) && !isNaN(lng)) {
            marker.setLatLng([lat, lng]);
            map.panTo([lat, lng]);
        }
    });
});
</script>
@endsection
