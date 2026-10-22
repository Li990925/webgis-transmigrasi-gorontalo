@extends('admin.layout')

@section('title', 'Editor Spasial - ' . $kawasanTransmigrasi->nama)
@section('header-title', 'Editor Spasial Kanvas Geoman & PostGIS')

@section('content')
<div class="space-y-4">
    <!-- Header with Action Buttons -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 rounded-xl bg-white border border-gray-200 shadow-sm">
        <div>
            <div class="flex items-center gap-2">
                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-100 text-blue-900 border border-blue-200">
                    PostGIS SRID 4326 (Polygon & Point)
                </span>
                <span class="text-xs text-gray-500 font-medium">{{ $kawasanTransmigrasi->kabupaten }} &bull; Kec. {{ $kawasanTransmigrasi->kecamatan }}</span>
            </div>
            <h1 class="text-xl font-extrabold text-gray-900 mt-1">{{ $kawasanTransmigrasi->nama }}</h1>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.kawasan.index') }}" class="px-3 py-2 rounded-xl bg-white hover:bg-gray-100 text-gray-700 text-xs font-semibold border border-gray-300 shadow-sm transition-colors flex items-center gap-1.5">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>

            @if($canEdit && !$user->isEksekutif())
                <button id="btn-save-geometry" class="px-4 py-2 rounded-xl bg-yellow-500 hover:bg-yellow-400 text-slate-950 text-xs font-bold shadow-sm hover:shadow transition-all flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-slate-950" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span>Simpan Perubahan ke PostGIS</span>
                </button>
            @else
                <span class="text-xs text-amber-800 bg-amber-50 px-3 py-2 rounded-xl border border-amber-200 font-semibold">
                    Mode Read-Only (Hanya Lihat)
                </span>
            @endif
        </div>
    </div>

    <!-- Live Metrics Info Bar -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
        <div class="p-3.5 rounded-xl bg-white border border-gray-200 shadow-sm">
            <div class="text-[10px] uppercase font-bold text-gray-500">Jumlah Verteks Poligon</div>
            <div id="stat-vertex-count" class="text-lg font-mono font-extrabold text-gray-900 mt-0.5">
                {{ count($kawasanTransmigrasi->polygon_geojson ?? []) }} Titik
            </div>
        </div>

        <div class="p-3.5 rounded-xl bg-white border border-gray-200 shadow-sm">
            <div class="text-[10px] uppercase font-bold text-gray-500">Luas Tercatat (Tabular)</div>
            <div class="text-lg font-mono font-extrabold text-gray-900 mt-0.5">
                {{ number_format($kawasanTransmigrasi->luas_ha, 0) }} Ha
            </div>
        </div>

        <div class="p-3.5 rounded-xl bg-white border border-gray-200 shadow-sm">
            <div class="text-[10px] uppercase font-bold text-gray-500">Luas Geodesik PostGIS</div>
            <div id="stat-postgis-area" class="text-lg font-mono font-extrabold text-blue-900 mt-0.5">
                {{ $nativeAreaHa ? number_format($nativeAreaHa, 2) . ' Ha' : 'Dihitung saat simpan' }}
            </div>
        </div>

        <div class="p-3.5 rounded-xl bg-white border border-gray-200 shadow-sm">
            <div class="text-[10px] uppercase font-bold text-gray-500">Koordinat Titik Pusat</div>
            <div id="stat-center-coords" class="text-xs font-mono font-semibold text-blue-700 mt-1 truncate">
                {{ $kawasanTransmigrasi->latitude }}, {{ $kawasanTransmigrasi->longitude }}
            </div>
        </div>
    </div>

    <!-- Spatial Canvas Container -->
    <div class="relative bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm" style="height: calc(100vh - 290px); min-height: 480px;">
        <!-- Canvas Map -->
        <div id="spatial-canvas" class="w-full h-full z-0"></div>

        <!-- Floating Basemap Selector Controls -->
        <div class="absolute top-4 right-4 z-10 flex flex-col gap-1.5 bg-white/95 backdrop-blur-md p-2 rounded-xl border border-gray-200 shadow-md text-xs">
            <span class="text-[10px] font-bold text-gray-500 uppercase tracking-wider px-1">Pilihan Citra Peta:</span>
            <div class="grid grid-cols-2 gap-1">
                <button type="button" class="basemap-btn px-2.5 py-1 rounded-lg text-[11px] font-semibold bg-[#081e36] text-white" data-layer="osm">OSM</button>
                <button type="button" class="basemap-btn px-2.5 py-1 rounded-lg text-[11px] font-semibold bg-gray-100 text-gray-700 hover:bg-gray-200" data-layer="satellite">Citra Satelit</button>
                <button type="button" class="basemap-btn px-2.5 py-1 rounded-lg text-[11px] font-semibold bg-gray-100 text-gray-700 hover:bg-gray-200" data-layer="topo">Topografi</button>
                <button type="button" class="basemap-btn px-2.5 py-1 rounded-lg text-[11px] font-semibold bg-gray-100 text-gray-700 hover:bg-gray-200" data-layer="dark">Gelap (Dark)</button>
            </div>
        </div>

        <!-- Floating Hint Box -->
        <div class="absolute bottom-4 left-4 z-10 bg-white/95 backdrop-blur-md px-3.5 py-2.5 rounded-xl border border-gray-200 shadow-md text-xs max-w-sm hidden sm:block">
            <div class="font-bold text-blue-900 mb-0.5 flex items-center gap-1.5">
                <svg class="w-4 h-4 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Panduan Digitasi Batas Spasial:
            </div>
            <p class="text-[11px] text-gray-600 leading-relaxed">
                Gunakan alat di bilah sisi kiri peta (Geoman Toolbar) untuk mengedit verteks poligon, menggambar batas baru, atau menggeser titik tengah kawasan. Klik tombol <span class="text-slate-950 bg-yellow-400 px-1 py-0.5 rounded font-bold">"Simpan Perubahan ke PostGIS"</span> untuk menerapkan koordinat ke database.
            </p>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script src="/js/leaflet-geoman.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const rawCoords = {!! json_encode($kawasanTransmigrasi->polygon_geojson ?? []) !!};
    const centerLat = {{ $kawasanTransmigrasi->latitude }};
    const centerLng = {{ $kawasanTransmigrasi->longitude }};
    const canEdit = {{ $canEdit ? 'true' : 'false' }};
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // 1. Basemap Layers
    const baseLayers = {
        osm: L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19, attribution: '&copy; OpenStreetMap' }),
        satellite: L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', { maxZoom: 18, attribution: 'Esri World Imagery' }),
        topo: L.tileLayer('https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png', { maxZoom: 17, attribution: 'OpenTopoMap' }),
        dark: L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', { maxZoom: 19, attribution: '&copy; CARTO' })
    };

    const map = L.map('spatial-canvas', {
        center: [centerLat, centerLng],
        zoom: 13,
        layers: [baseLayers.osm]
    });

    // Basemap switcher events
    document.querySelectorAll('.basemap-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const layerKey = this.dataset.layer;
            Object.values(baseLayers).forEach(l => map.removeLayer(l));
            baseLayers[layerKey].addTo(map);

            document.querySelectorAll('.basemap-btn').forEach(b => {
                b.classList.remove('bg-[#081e36]', 'text-white');
                b.classList.add('bg-gray-100', 'text-gray-700');
            });
            this.classList.remove('bg-gray-100', 'text-gray-700');
            this.classList.add('bg-[#081e36]', 'text-white');
        });
    });

    // 2. Active Layer Elements
    let centerMarker = L.marker([centerLat, centerLng], {
        draggable: canEdit,
        title: 'Pusat Kawasan: {{ $kawasanTransmigrasi->nama }}'
    }).addTo(map);

    centerMarker.bindPopup(`<b>{{ $kawasanTransmigrasi->nama }}</b><br>Titik Koordinat Pusat PostGIS`).openPopup();

    centerMarker.on('dragend', function(e) {
        const pos = e.target.getLatLng();
        document.getElementById('stat-center-coords').textContent = `${pos.lat.toFixed(6)}, ${pos.lng.toFixed(6)}`;
    });

    let currentPolygon = null;

    if (rawCoords && rawCoords.length >= 3) {
        currentPolygon = L.polygon(rawCoords, {
            color: '#4c1d95',
            fillColor: '#4c1d95',
            fillOpacity: 0.25,
            weight: 3
        }).addTo(map);

        map.fitBounds(currentPolygon.getBounds(), { padding: [40, 40] });
    }

    // 3. Geoman Controls Initialization
    if (canEdit && typeof map.pm !== 'undefined') {
        map.pm.addControls({
            position: 'topleft',
            drawMarker: true,
            drawPolygon: true,
            drawPolyline: false,
            drawCircle: false,
            drawCircleMarker: false,
            drawRectangle: true,
            editMode: true,
            dragMode: true,
            cutPolygon: true,
            removalMode: true
        });

        // Event when new polygon is drawn
        map.on('pm:create', function(e) {
            if (e.shape === 'Polygon' || e.shape === 'Rectangle') {
                if (currentPolygon) {
                    map.removeLayer(currentPolygon);
                }
                currentPolygon = e.layer;
                updateMetrics();
                
                currentPolygon.on('pm:edit', updateMetrics);
                currentPolygon.on('pm:dragend', updateMetrics);
            } else if (e.shape === 'Marker') {
                if (centerMarker) {
                    map.removeLayer(centerMarker);
                }
                centerMarker = e.layer;
                const pos = centerMarker.getLatLng();
                document.getElementById('stat-center-coords').textContent = `${pos.lat.toFixed(6)}, ${pos.lng.toFixed(6)}`;
            }
        });

        if (currentPolygon) {
            currentPolygon.on('pm:edit', updateMetrics);
            currentPolygon.on('pm:dragend', updateMetrics);
        }
    }

    function updateMetrics() {
        if (!currentPolygon) return;
        const latlngs = currentPolygon.getLatLngs()[0] || currentPolygon.getLatLngs();
        document.getElementById('stat-vertex-count').textContent = `${latlngs.length} Titik`;
    }

    // 4. Save Geometry directly to PostGIS
    const saveBtn = document.getElementById('btn-save-geometry');
    if (saveBtn) {
        saveBtn.addEventListener('click', async function() {
            if (!currentPolygon) {
                alert('Silakan gambar atau tentukan poligon batas kawasan terlebih dahulu.');
                return;
            }

            const rawLatLngs = currentPolygon.getLatLngs()[0] || currentPolygon.getLatLngs();
            const polygon_geojson = rawLatLngs.map(pt => [
                parseFloat(pt.lat.toFixed(6)),
                parseFloat(pt.lng.toFixed(6))
            ]);

            // Ensure polygon is closed (first point equals last point)
            if (polygon_geojson.length > 0) {
                const first = polygon_geojson[0];
                const last = polygon_geojson[polygon_geojson.length - 1];
                if (first[0] !== last[0] || first[1] !== last[1]) {
                    polygon_geojson.push([first[0], first[1]]);
                }
            }

            const centerPos = centerMarker.getLatLng();
            const payload = {
                latitude: parseFloat(centerPos.lat.toFixed(6)),
                longitude: parseFloat(centerPos.lng.toFixed(6)),
                polygon_geojson: polygon_geojson
            };

            saveBtn.disabled = true;
            saveBtn.innerHTML = `
                <svg class="animate-spin w-4 h-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                <span>Menyimpan ke PostGIS...</span>
            `;

            try {
                const res = await fetch('/api/kawasan/{{ $kawasanTransmigrasi->id }}/geometry', {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify(payload)
                });

                const data = await res.json();

                if (res.ok && data.success) {
                    if (data.data?.postgis_area_ha) {
                        document.getElementById('stat-postgis-area').textContent = `${data.data.postgis_area_ha} Ha`;
                    }
                    alert(data.message || 'Geometri spasial berhasil disimpan ke database PostGIS!');
                } else {
                    alert('Gagal menyimpan: ' + (data.message || 'Terjadi kesalahan sistem.'));
                }
            } catch (err) {
                console.error(err);
                alert('Gagal menghubungi server: ' + err.message);
            } finally {
                saveBtn.disabled = false;
                saveBtn.innerHTML = `
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span>Simpan Perubahan ke PostGIS</span>
                `;
            }
        });
    }
});
</script>
@endsection
