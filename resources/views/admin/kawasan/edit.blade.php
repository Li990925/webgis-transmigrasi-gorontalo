@extends('admin.layout')

@section('title', 'Edit Kawasan ' . $kawasanTransmigrasi->nama)
@section('header-title', 'Edit Data Kawasan')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-extrabold text-gray-900">Edit Data Kawasan: {{ $kawasanTransmigrasi->nama }}</h1>
            <p class="text-xs text-gray-500 mt-0.5">Perbarui data demografi, capaian sertifikasi hak milik, dan koordinat spasial.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.kawasan.spasial', $kawasanTransmigrasi->id) }}" class="px-3.5 py-2 rounded-xl bg-[#1a73e8] hover:bg-blue-600 text-white text-xs font-semibold shadow-sm transition-colors flex items-center gap-1.5">
                <svg class="w-4 h-4 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                Editor Spasial Poligon
            </a>
            <a href="{{ route('admin.kawasan.index') }}" class="px-3 py-2 rounded-xl bg-white hover:bg-gray-100 text-gray-700 text-xs font-semibold border border-gray-300 shadow-sm transition-colors flex items-center gap-1.5">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>
        </div>
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

    <form action="{{ route('admin.kawasan.update', $kawasanTransmigrasi->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Section 1: Identitas & Lokasi Administratif -->
        <div class="p-6 rounded-xl bg-white border border-gray-200 shadow-sm space-y-4">
            <h2 class="text-sm font-bold text-gray-800 flex items-center gap-2 border-b border-gray-100 pb-3">
                <span class="w-6 h-6 rounded-lg bg-blue-100 text-blue-900 flex items-center justify-center text-xs font-extrabold">1</span>
                Identitas Kawasan & Administrasi Wilayah
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 text-xs">
                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Nama Kawasan <span class="text-rose-500">*</span></label>
                    <input type="text" name="nama" required value="{{ old('nama', $kawasanTransmigrasi->nama) }}"
                        class="w-full py-2.5 px-3 rounded-xl bg-gray-50 border border-gray-300 text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#1a73e8] focus:bg-white transition-all">
                </div>

                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Tipe Kawasan <span class="text-rose-500">*</span></label>
                    <select name="tipe" required class="w-full py-2.5 px-3 rounded-xl bg-gray-50 border border-gray-300 text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#1a73e8] focus:bg-white transition-all">
                        <option value="UPT" {{ old('tipe', $kawasanTransmigrasi->tipe) === 'UPT' ? 'selected' : '' }}>UPT (Unit Pemukiman Transmigrasi)</option>
                        <option value="KTM" {{ old('tipe', $kawasanTransmigrasi->tipe) === 'KTM' ? 'selected' : '' }}>KTM (Kota Terpadu Mandiri)</option>
                    </select>
                </div>

                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Kabupaten <span class="text-rose-500">*</span></label>
                    <select name="kabupaten" required class="w-full py-2.5 px-3 rounded-xl bg-gray-50 border border-gray-300 text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#1a73e8] focus:bg-white transition-all">
                        @if ($user->isSuperAdmin())
                            @foreach(['Kabupaten Boalemo', 'Kabupaten Pohuwato', 'Kabupaten Gorontalo', 'Kabupaten Gorontalo Utara'] as $kab)
                                <option value="{{ $kab }}" {{ old('kabupaten', $kawasanTransmigrasi->kabupaten) === $kab ? 'selected' : '' }}>{{ $kab }}</option>
                            @endforeach
                        @else
                            <option value="{{ $kawasanTransmigrasi->kabupaten }}" selected>{{ $kawasanTransmigrasi->kabupaten }}</option>
                        @endif
                    </select>
                </div>

                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Kecamatan <span class="text-rose-500">*</span></label>
                    <input type="text" name="kecamatan" required value="{{ old('kecamatan', $kawasanTransmigrasi->kecamatan) }}"
                        class="w-full py-2.5 px-3 rounded-xl bg-gray-50 border border-gray-300 text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#1a73e8] focus:bg-white transition-all">
                </div>

                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Desa <span class="text-rose-500">*</span></label>
                    <input type="text" name="desa" required value="{{ old('desa', $kawasanTransmigrasi->desa) }}"
                        class="w-full py-2.5 px-3 rounded-xl bg-gray-50 border border-gray-300 text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#1a73e8] focus:bg-white transition-all">
                </div>

                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Tahun Penempatan <span class="text-rose-500">*</span></label>
                    <input type="number" name="tahun_penempatan" required value="{{ old('tahun_penempatan', $kawasanTransmigrasi->tahun_penempatan) }}" min="1980" max="2030"
                        class="w-full py-2.5 px-3 rounded-xl bg-gray-50 border border-gray-300 text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#1a73e8] focus:bg-white transition-all">
                </div>
            </div>
        </div>

        <!-- Section 2: Demografi & Legalitas Hak Milik -->
        <div class="p-6 rounded-xl bg-white border border-gray-200 shadow-sm space-y-4">
            <h2 class="text-sm font-bold text-gray-800 flex items-center gap-2 border-b border-gray-100 pb-3">
                <span class="w-6 h-6 rounded-lg bg-blue-100 text-blue-900 flex items-center justify-center text-xs font-extrabold">2</span>
                Demografi Penduduk & Sertifikasi Tanah (SHM)
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 text-xs">
                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Jumlah KK <span class="text-rose-500">*</span></label>
                    <input type="number" name="jumlah_kk" id="input_kk" required value="{{ old('jumlah_kk', $kawasanTransmigrasi->jumlah_kk) }}" min="1"
                        class="w-full py-2.5 px-3 rounded-xl bg-gray-50 border border-gray-300 text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#1a73e8] focus:bg-white transition-all">
                </div>

                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Jumlah Jiwa <span class="text-rose-500">*</span></label>
                    <input type="number" name="jumlah_jiwa" required value="{{ old('jumlah_jiwa', $kawasanTransmigrasi->jumlah_jiwa) }}" min="1"
                        class="w-full py-2.5 px-3 rounded-xl bg-gray-50 border border-gray-300 text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#1a73e8] focus:bg-white transition-all">
                </div>

                <div>
                    <label class="block font-semibold text-gray-700 mb-1">KK Sudah SHM <span class="text-rose-500">*</span></label>
                    <input type="number" name="kk_shm" id="input_shm" required value="{{ old('kk_shm', $kawasanTransmigrasi->kk_shm) }}" min="0"
                        class="w-full py-2.5 px-3 rounded-xl bg-gray-50 border border-gray-300 text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#1a73e8] focus:bg-white transition-all">
                </div>

                <div>
                    <label class="block font-semibold text-gray-700 mb-1">KK Belum SHM (Non-SHM) <span class="text-rose-500">*</span></label>
                    <input type="number" name="kk_non_shm" id="input_non_shm" required value="{{ old('kk_non_shm', $kawasanTransmigrasi->kk_non_shm) }}" min="0"
                        class="w-full py-2.5 px-3 rounded-xl bg-gray-50 border border-gray-300 text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#1a73e8] focus:bg-white transition-all">
                </div>

                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Luas Kawasan (Hektar) <span class="text-rose-500">*</span></label>
                    <input type="number" step="0.01" name="luas_ha" required value="{{ old('luas_ha', $kawasanTransmigrasi->luas_ha) }}" min="0.1"
                        class="w-full py-2.5 px-3 rounded-xl bg-gray-50 border border-gray-300 text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#1a73e8] focus:bg-white transition-all">
                </div>

                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Status Perkembangan <span class="text-rose-500">*</span></label>
                    <select name="status_perkembangan" required class="w-full py-2.5 px-3 rounded-xl bg-gray-50 border border-gray-300 text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#1a73e8] focus:bg-white transition-all">
                        <option value="Pembinaan" {{ old('status_perkembangan', $kawasanTransmigrasi->status_perkembangan) === 'Pembinaan' ? 'selected' : '' }}>Pembinaan</option>
                        <option value="Berkembang" {{ old('status_perkembangan', $kawasanTransmigrasi->status_perkembangan) === 'Berkembang' ? 'selected' : '' }}>Berkembang</option>
                        <option value="Mandiri" {{ old('status_perkembangan', $kawasanTransmigrasi->status_perkembangan) === 'Mandiri' ? 'selected' : '' }}>Mandiri</option>
                    </select>
                </div>

                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Skor Kemandirian (0 - 100)</label>
                    <input type="number" name="skor_kemandirian" value="{{ old('skor_kemandirian', $kawasanTransmigrasi->skor_kemandirian) }}" min="0" max="100"
                        class="w-full py-2.5 px-3 rounded-xl bg-gray-50 border border-gray-300 text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#1a73e8] focus:bg-white transition-all">
                </div>

                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Komoditas Unggulan</label>
                    <input type="text" name="komoditas_unggulan" value="{{ old('komoditas_unggulan', is_array($kawasanTransmigrasi->komoditas_unggulan) ? implode(', ', $kawasanTransmigrasi->komoditas_unggulan) : $kawasanTransmigrasi->komoditas_unggulan) }}"
                        class="w-full py-2.5 px-3 rounded-xl bg-gray-50 border border-gray-300 text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#1a73e8] focus:bg-white transition-all">
                </div>
            </div>
        </div>

        <!-- Section 3: Koordinat Spasial PostGIS -->
        <div class="p-6 rounded-xl bg-white border border-gray-200 shadow-sm space-y-4">
            <h2 class="text-sm font-bold text-gray-800 flex items-center gap-2 border-b border-gray-100 pb-3">
                <span class="w-6 h-6 rounded-lg bg-blue-100 text-blue-900 flex items-center justify-center text-xs font-extrabold">3</span>
                Koordinat Titik Spasial (PostGIS Point SRID 4326)
            </h2>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
                <div class="lg:col-span-4 space-y-3 text-xs">
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">Latitude (Lintang) <span class="text-rose-500">*</span></label>
                        <input type="number" step="0.0000001" name="latitude" id="input_latitude" required value="{{ old('latitude', $kawasanTransmigrasi->latitude) }}"
                            class="w-full py-2.5 px-3 rounded-xl bg-gray-50 border border-gray-300 text-gray-900 font-mono focus:outline-none focus:ring-2 focus:ring-[#1a73e8] focus:bg-white transition-all">
                    </div>

                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">Longitude (Bujur) <span class="text-rose-500">*</span></label>
                        <input type="number" step="0.0000001" name="longitude" id="input_longitude" required value="{{ old('longitude', $kawasanTransmigrasi->longitude) }}"
                            class="w-full py-2.5 px-3 rounded-xl bg-gray-50 border border-gray-300 text-gray-900 font-mono focus:outline-none focus:ring-2 focus:ring-[#1a73e8] focus:bg-white transition-all">
                    </div>

                    <div class="p-3 rounded-xl bg-blue-50/60 border border-blue-100 text-[11px] text-gray-600">
                        <span class="text-blue-900 font-bold">Catatan Geometri:</span> Koordinat poligon batas kawasan dapat disesuaikan dan diedit verteksnya menggunakan kanvas digitasi di <a href="{{ route('admin.kawasan.spasial', $kawasanTransmigrasi->id) }}" class="text-[#1a73e8] font-bold hover:underline">Editor Spasial Poligon &rarr;</a>.
                    </div>
                </div>

                <div class="lg:col-span-8">
                    <div id="picker-map" class="w-full h-64 rounded-xl border border-gray-300 overflow-hidden relative z-0 shadow-inner"></div>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex items-center justify-end gap-3 pt-2">
            <a href="{{ route('admin.kawasan.index') }}" class="px-5 py-2.5 rounded-xl bg-white hover:bg-gray-100 text-gray-700 text-xs font-bold border border-gray-300 shadow-sm transition-colors">
                Batal
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-yellow-500 hover:bg-yellow-400 text-slate-950 text-xs font-bold shadow-sm hover:shadow transition-all flex items-center gap-2">
                <svg class="w-4 h-4 text-slate-950" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span>Simpan Perubahan Data</span>
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

    const map = L.map('picker-map').setView([initLat, initLng], 12);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);

    let marker = L.marker([initLat, initLng], { draggable: true }).addTo(map);

    // Show polygon outline if available
    @if (!empty($kawasanTransmigrasi->polygon_geojson))
        const polygonCoords = {!! json_encode($kawasanTransmigrasi->polygon_geojson) !!};
        const polyLayer = L.polygon(polygonCoords, {
            color: '#10b981',
            fillColor: '#10b981',
            fillOpacity: 0.25,
            weight: 2
        }).addTo(map);
        map.fitBounds(polyLayer.getBounds(), { padding: [20, 20] });
    @endif

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
});
</script>
@endsection
