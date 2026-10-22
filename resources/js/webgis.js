import L from 'leaflet';
import '@geoman-io/leaflet-geoman-free';
import Chart from 'chart.js/auto';

// Fix leaflet icon path issue in bundlers
delete L.Icon.Default.prototype._getIconUrl;
L.Icon.Default.mergeOptions({
    iconRetinaUrl: '/build/assets/marker-icon-2x.png',
    iconUrl: '/build/assets/marker-icon.png',
    shadowUrl: '/build/assets/marker-shadow.png',
});

let map = null;
let polygonLayerGroup = null;
let markerLayerGroup = null;
let activeKawasanId = null;
let transmigrasiFeatures = [];
let charts = {};
let isPickingCoordinates = false;
let tempPickMarker = null;

// Geometry Editing state
let editingKawasanId = null;
let editingPolygonLayer = null;

// Auth & RBAC state
let authState = {
    authenticated: false,
    user: null,
    role: 'tamu',
    permissions: {
        can_create: false,
        can_edit: false,
        can_delete: false,
        can_import: false,
        can_export: true,
        is_super_admin: false,
        is_operator: false,
        is_eksekutif: false,
        kabupaten: null
    }
};

// Base Maps Configuration
const baseLayers = {
    osm: L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap contributors'
    }),
    satellite: L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        maxZoom: 18,
        attribution: '&copy; Esri & Maxar Technologies'
    }),
    topo: L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Topo_Map/MapServer/tile/{z}/{y}/{x}', {
        maxZoom: 18,
        attribution: '&copy; Esri World Topo Map'
    }),
    dark: L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
        maxZoom: 19,
        attribution: '&copy; CartoDB Dark Matter'
    }),
    positron: L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
        maxZoom: 19,
        attribution: '&copy; CartoDB Positron'
    })
};

export async function initWebGIS() {
    const mapElement = document.getElementById('webgis-map');
    if (!mapElement) return;

    // Center of Gorontalo Province covering the 4 target districts
    const gorontaloCenter = [0.7200, 122.3500];
    const initialZoom = 9;

    map = L.map('webgis-map', {
        center: gorontaloCenter,
        zoom: initialZoom,
        zoomControl: false,
        layers: [baseLayers.osm]
    });

    // Custom Zoom Control at top-right
    L.control.zoom({ position: 'topright' }).addTo(map);

    // Geoman controls are disabled on public map (accessible in Admin Panel)
    if (map.pm) {
        map.pm.setLang('id');

        // Handle newly drawn polygon on canvas
        map.on('pm:create', (e) => {
            if (e.shape === 'Polygon' || e.shape === 'Rectangle') {
                const latLngs = e.layer.getLatLngs()[0].map(pt => [pt.lat, pt.lng]);
                // Auto fill modal form polygon if modal open
                const formPolygonInput = document.getElementById('form-polygon-json');
                if (formPolygonInput) {
                    formPolygonInput.value = JSON.stringify(latLngs);
                }
                const notif = document.getElementById('canvas-polygon-drawn-badge');
                if (notif) notif.classList.remove('hidden');
            }
        });
    }

    // Coordinate Tracker at bottom-left
    const coordControl = L.control({ position: 'bottomleft' });
    coordControl.onAdd = function () {
        const div = L.DomUtil.create('div', 'bg-[#081e36]/90 backdrop-blur-md text-yellow-400 font-mono text-xs px-3 py-1.5 rounded-lg border border-blue-900/60 shadow-lg');
        div.id = 'coord-display';
        div.innerHTML = 'Lat: 0.7200° N | Long: 122.3500° E | Zoom: 9';
        return div;
    };
    coordControl.addTo(map);

    map.on('mousemove', (e) => {
        const coordEl = document.getElementById('coord-display');
        if (coordEl) {
            coordEl.innerHTML = `Lat: ${e.latlng.lat.toFixed(4)}° N | Long: ${e.latlng.lng.toFixed(4)}° E | Zoom: ${map.getZoom()}`;
        }
    });

    // Handle Coordinate Picking on Click
    map.on('click', (e) => {
        if (isPickingCoordinates) {
            const lat = e.latlng.lat.toFixed(6);
            const lng = e.latlng.lng.toFixed(6);

            const inputLat = document.getElementById('form-latitude');
            const inputLng = document.getElementById('form-longitude');

            if (inputLat && inputLng) {
                inputLat.value = lat;
                inputLng.value = lng;
            }

            if (tempPickMarker) {
                map.removeLayer(tempPickMarker);
            }

            tempPickMarker = L.marker([lat, lng], {
                icon: L.divIcon({
                    className: 'custom-pick-marker',
                    html: '<div class="w-6 h-6 rounded-full bg-rose-500 ring-4 ring-rose-300 animate-bounce"></div>',
                    iconSize: [24, 24],
                    iconAnchor: [12, 24]
                })
            }).addTo(map);

            isPickingCoordinates = false;
            document.body.style.cursor = 'default';
            const alertEl = document.getElementById('pick-coord-alert');
            if (alertEl) alertEl.classList.add('hidden');

            const modal = document.getElementById('form-modal');
            if (modal) modal.classList.remove('hidden');
        }
    });

    // Layer Groups
    polygonLayerGroup = L.layerGroup().addTo(map);
    markerLayerGroup = L.layerGroup().addTo(map);

    // Initial auth check
    await checkAuthStatus();

    // Fetch GeoJSON from Laravel API endpoint
    await loadGeoJsonData();

    // Setup Event Listeners
    setupControls();
    setupFilters();
    setupTabSwitching();
    setupFormCalculations();

    // Export functions to Window for global inline button access
    window.selectKawasan = selectKawasan;
    window.focusKawasanOnMap = focusKawasanOnMap;
    window.resetMapView = resetMapView;
    window.exportGeoJSON = exportGeoJSON;
    window.switchBaseMap = switchBaseMap;
    window.switchAppView = switchAppView;
    window.openCreateModal = openCreateModal;
    window.openEditModal = openEditModal;
    window.closeFormModal = closeFormModal;
    window.deleteKawasan = deleteKawasan;
    window.pickCoordinateFromMap = pickCoordinateFromMap;

    // RBAC & Persona Switcher
    window.openPersonaModal = openPersonaModal;
    window.closePersonaModal = closePersonaModal;
    window.switchPersona = switchPersona;
    window.handleLogout = handleLogout;
    window.submitLoginForm = submitLoginForm;

    // Geoman Canvas Geometry Editor
    window.startGeometryEditor = startGeometryEditor;
    window.saveGeometryToPostGIS = saveGeometryToPostGIS;
    window.cancelGeometryEditor = cancelGeometryEditor;

    // Mass Import Modal
    window.openImportModal = openImportModal;
    window.closeImportModal = closeImportModal;
    window.submitImportForm = submitImportForm;

    // Audit logs pagination & filter
    window.loadAuditLogs = loadAuditLogs;

    // Floating legend toggle
    window.toggleLegendContent = toggleLegendContent;
}

// ==================== RBAC AUTHENTICATION ====================

export async function checkAuthStatus() {
    try {
        const res = await fetch('/api/auth/status');
        const data = await res.json();
        authState = data;
        updateAuthUI();
    } catch (e) {
        console.error('Failed to check auth status:', e);
    }
}

function updateAuthUI() {
    const badgeRole = document.getElementById('header-role-badge');
    const badgeUser = document.getElementById('header-user-name');
    const btnAdd = document.getElementById('btn-add-kawasan');
    const importOption = document.getElementById('btn-open-import');

    if (!badgeRole) return;

    if (authState.authenticated && authState.user) {
        badgeUser.textContent = authState.user.name;
        
        if (authState.role === 'super_admin') {
            badgeRole.className = 'px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-500/20 text-blue-300 border border-blue-500/30';
            badgeRole.textContent = 'Super Admin (Provinsi)';
        } else if (authState.role === 'operator_kabupaten') {
            badgeRole.className = 'px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/20 text-amber-300 border border-amber-500/30';
            badgeRole.textContent = `Operator ${authState.user.kabupaten || ''}`;
        } else if (authState.role === 'eksekutif') {
            badgeRole.className = 'px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30';
            badgeRole.textContent = 'Eksekutif (Read-Only)';
        }
    } else {
        badgeUser.textContent = 'Mode Publik / Tamu';
        badgeRole.className = 'px-2 py-0.5 rounded-full text-[10px] font-semibold bg-slate-700 text-slate-300 border border-slate-600';
        badgeRole.textContent = 'Tamu (Klik untuk Masuk)';
    }

    // Toggle add button
    if (btnAdd) {
        if (authState.permissions.can_create) {
            btnAdd.classList.remove('opacity-50', 'pointer-events-none');
            btnAdd.title = 'Tambah kawasan transmigrasi baru';
        } else {
            btnAdd.classList.add('opacity-50');
            btnAdd.title = 'Masuk sebagai Super Admin atau Operator Kabupaten untuk menambah data';
        }
    }

    // Refresh kawasan sidebar list to reflect edit/delete permissions
    if (transmigrasiFeatures.length > 0) {
        renderKawasanSidebarList(getFilteredFeatures());
    }
}

export function openPersonaModal() {
    const m = document.getElementById('persona-modal');
    if (m) m.classList.remove('hidden');
}

export function closePersonaModal() {
    const m = document.getElementById('persona-modal');
    if (m) m.classList.add('hidden');
}

export async function switchPersona(roleKey) {
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        const res = await fetch('/api/auth/switch-role', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ role: roleKey })
        });
        const json = await res.json();
        if (json.success) {
            closePersonaModal();
            showToast('success', json.message);
            await checkAuthStatus();
            // Refresh audit logs if on that view
            loadAuditLogs();
        } else {
            alert(json.message);
        }
    } catch (e) {
        alert('Gagal berganti persona: ' + e.message);
    }
}

export async function submitLoginForm(e) {
    e.preventDefault();
    const form = e.target;
    const email = form.email.value;
    const password = form.password.value;
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

    try {
        const res = await fetch('/api/auth/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ email, password })
        });
        const json = await res.json();
        if (json.success) {
            closePersonaModal();
            showToast('success', json.message);
            await checkAuthStatus();
            loadAuditLogs();
        } else {
            alert(json.message || 'Login gagal.');
        }
    } catch (err) {
        alert('Terjadi kesalahan login: ' + err.message);
    }
}

export async function handleLogout() {
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        await fetch('/api/auth/logout', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        });
        closePersonaModal();
        showToast('info', 'Anda telah kembali ke mode Tamu / Publik.');
        await checkAuthStatus();
    } catch (e) {
        console.error(e);
    }
}

// ==================== GEOMAN POSTGIS CANVAS EDITOR ====================

export function startGeometryEditor(kawasanId) {
    const feature = transmigrasiFeatures.find(f => f.properties.id === kawasanId);
    if (!feature) return;

    const props = feature.properties;

    // Check RBAC permission for this regency
    if (!authState.permissions.is_super_admin) {
        if (!authState.permissions.is_operator || authState.permissions.kabupaten !== props.kabupaten) {
            alert(`Akses ditolak: Anda hanya berwenang mengubah geometri wilayah ${authState.permissions.kabupaten || 'tidak ada'}. Silakan beralih persona.`);
            return;
        }
    }

    editingKawasanId = kawasanId;
    closeDetailDrawer();
    switchAppView('peta');

    // Pan to area
    map.flyTo([props.latitude, props.longitude], 13, { duration: 1.0 });

    const poly = props._polygon;
    if (poly) {
        editingPolygonLayer = poly;
        poly.pm.enable({
            allowSelfIntersection: false
        });
    }

    // Show floating editor toolbar on canvas
    const editorBar = document.getElementById('geometry-editor-toolbar');
    if (editorBar) {
        document.getElementById('geometry-editing-target-name').textContent = `${props.nama} (${props.kabupaten})`;
        editorBar.classList.remove('hidden');
    }
}

export async function saveGeometryToPostGIS() {
    if (!editingKawasanId || !editingPolygonLayer) return;

    try {
        const rawCoords = editingPolygonLayer.getLatLngs()[0];
        const polygonGeojson = rawCoords.map(pt => [Number(pt.lat.toFixed(6)), Number(pt.lng.toFixed(6))]);

        // Calculate center centroid as well
        const bounds = editingPolygonLayer.getBounds();
        const center = bounds.getCenter();

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        const res = await fetch(`/api/kawasan/${editingKawasanId}/geometry`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                latitude: Number(center.lat.toFixed(6)),
                longitude: Number(center.lng.toFixed(6)),
                polygon_geojson: polygonGeojson
            })
        });

        const json = await res.json();
        if (json.success) {
            editingPolygonLayer.pm.disable();
            editingKawasanId = null;
            editingPolygonLayer = null;

            const editorBar = document.getElementById('geometry-editor-toolbar');
            if (editorBar) editorBar.classList.add('hidden');

            const postgisHa = json.data.postgis_area_ha ? ` (Luas PostGIS: ${json.data.postgis_area_ha} Ha)` : '';
            showToast('success', `Geometri berhasil disimpan ke PostgreSQL PostGIS!${postgisHa}`);

            // Reload GeoJSON so all visuals and calculations sync
            await loadGeoJsonData();
            loadAuditLogs();
        } else {
            alert(json.message || 'Gagal menyimpan geometri.');
        }
    } catch (e) {
        alert('Kesalahan saat menyimpan geometri: ' + e.message);
    }
}

export function cancelGeometryEditor() {
    if (editingPolygonLayer) {
        editingPolygonLayer.pm.disable();
    }
    editingKawasanId = null;
    editingPolygonLayer = null;

    const editorBar = document.getElementById('geometry-editor-toolbar');
    if (editorBar) editorBar.classList.add('hidden');

    // Reload layers to discard unsaved vertex drags
    renderKawasanLayers(getFilteredFeatures());
}

// ==================== DATA FETCHING & RENDERING ====================

async function loadGeoJsonData() {
    try {
        const response = await fetch('/api/transmigrasi/geojson');
        const data = await response.json();
        transmigrasiFeatures = data.features || [];
        renderKawasanLayers(transmigrasiFeatures);
        renderKawasanSidebarList(transmigrasiFeatures);
        initAnalyticsDashboard();
    } catch (err) {
        console.error('Error fetching GeoJSON:', err);
    }
}

function getShmColor(percentage) {
    if (percentage >= 75) return '#10b981'; // Emerald (Tinggi)
    if (percentage >= 50) return '#f59e0b'; // Amber (Sedang)
    return '#ef4444'; // Red (Rendah / Butuh Percepatan ATR/BPN)
}

function createCustomPin(props, isActive = false) {
    const roundedPct = Math.round(Number(props.persentase_shm) || 0);
    const bgClass = isActive 
        ? 'bg-amber-500 scale-125 ring-4 ring-amber-300 shadow-amber-500/50' 
        : (roundedPct >= 75 ? 'bg-emerald-600 ring-2 ring-emerald-300' : (roundedPct >= 50 ? 'bg-amber-600 ring-2 ring-amber-300' : 'bg-rose-600 ring-2 ring-rose-300'));

    const html = `
        <div class="relative group cursor-pointer transition-all duration-300">
            <div class="w-8 h-8 rounded-full ${bgClass} text-white flex items-center justify-center shadow-md transition-transform hover:scale-120">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <span class="absolute -bottom-1.5 -right-2 text-[9px] font-extrabold px-1.5 py-0.2 rounded-full bg-slate-950 text-white border border-slate-700 shadow-lg font-mono">
                ${roundedPct}%
            </span>
            ${isActive ? '<span class="absolute -top-1 -right-1 flex h-3 w-3"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span><span class="relative inline-flex rounded-full h-3 w-3 bg-amber-500"></span></span>' : ''}
            <div class="absolute -bottom-9 left-1/2 -translate-x-1/2 whitespace-nowrap bg-slate-950/95 text-white text-[11px] font-semibold px-2.5 py-1 rounded-lg border border-slate-700 shadow-xl pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity z-50">
                ${props.nama} <span class="text-emerald-400 font-mono">(${roundedPct}% SHM)</span>
            </div>
        </div>
    `;

    return L.divIcon({
        className: 'custom-leaflet-marker',
        html: html,
        iconSize: [32, 32],
        iconAnchor: [16, 32],
        popupAnchor: [0, -32]
    });
}

function renderKawasanLayers(features) {
    polygonLayerGroup.clearLayers();
    markerLayerGroup.clearLayers();

    features.forEach(feature => {
        const props = feature.properties;
        const coords = feature.geometry.coordinates[0]; // [[lng, lat], ...]
        const latLngs = coords ? coords.map(pt => [pt[1], pt[0]]) : [];
        const center = [props.latitude, props.longitude];
        const shmColor = getShmColor(props.persentase_shm);

        // Render Polygon with Choropleth gradient coloring based on % SHM
        if (latLngs.length > 0) {
            const polygon = L.polygon(latLngs, {
                color: shmColor,
                weight: 2.5,
                opacity: 0.9,
                fillColor: shmColor,
                fillOpacity: 0.28,
                dashArray: props.persentase_shm < 50 ? '4, 4' : null
            });

            polygon.on('mouseover', function () {
                this.setStyle({ fillOpacity: 0.55, weight: 3.5 });
            });

            polygon.on('mouseout', function () {
                if (activeKawasanId !== props.id) {
                    this.setStyle({ fillOpacity: 0.28, weight: 2.5 });
                }
            });

            polygon.on('click', () => {
                selectKawasan(props.id);
            });

            polygonLayerGroup.addLayer(polygon);
            props._polygon = polygon;
        }

        // Render Marker
        const marker = L.marker(center, {
            icon: createCustomPin(props, activeKawasanId === props.id)
        });

        marker.on('click', () => {
            selectKawasan(props.id);
        });

        // Rich Popup Card
        const popupContent = `
            <div class="p-1 min-w-[270px] text-slate-800">
                <div class="flex items-center justify-between border-b pb-2 mb-2">
                    <span class="text-xs font-semibold px-2 py-0.5 rounded ${props.tipe === 'KTM' ? 'bg-emerald-100 text-emerald-800' : 'bg-sky-100 text-sky-800'}">
                        ${props.tipe} • ${props.kabupaten}
                    </span>
                    <span class="text-xs text-slate-500 font-mono">Th. ${props.tahun_penempatan}</span>
                </div>
                <h4 class="font-bold text-sm text-slate-900 leading-tight mb-1">${props.nama}</h4>
                <p class="text-xs text-slate-500 mb-2 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                    Kec. ${props.kecamatan}, Desa ${props.desa}
                </p>

                <!-- Status Hak Milik (SHM) Highlight -->
                <div class="bg-slate-50 p-2.5 rounded-lg border border-slate-100 mb-2">
                    <div class="flex items-center justify-between text-xs mb-1">
                        <span class="font-semibold text-slate-700">Status Sertifikasi Hak Milik:</span>
                        <span class="font-bold" style="color: ${shmColor}">${props.persentase_shm}%</span>
                    </div>
                    <div class="w-full bg-slate-200 h-2 rounded-full overflow-hidden mb-2">
                        <div class="h-2 rounded-full" style="width: ${props.persentase_shm}%; background-color: ${shmColor}"></div>
                    </div>
                    <div class="grid grid-cols-2 gap-1 text-[11px]">
                        <div class="text-emerald-700">✓ Sudah SHM: <strong>${props.kk_shm} KK</strong> (${Number(props.luas_shm_ha).toLocaleString()} Ha)</div>
                        <div class="text-amber-700">⏳ Belum SHM: <strong>${props.kk_non_shm} KK</strong> (${Number(props.luas_non_shm_ha).toLocaleString()} Ha)</div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-1.5 text-xs bg-slate-50 p-2 rounded mb-2">
                    <div><span class="text-slate-400 block text-[10px]">Total Penduduk</span><strong>${props.jumlah_kk} KK</strong> (${props.jumlah_jiwa} Jiwa)</div>
                    <div><span class="text-slate-400 block text-[10px]">Total Luas Lahan</span><strong>${Number(props.luas_ha).toLocaleString()} Ha</strong></div>
                </div>

                <div class="flex items-center gap-1.5 mt-2">
                    <button onclick="window.selectKawasan(${props.id})" class="flex-1 bg-yellow-500 hover:bg-yellow-400 text-slate-950 font-bold text-xs py-1.5 px-3 rounded-lg text-center transition flex items-center justify-center gap-1 cursor-pointer shadow-sm">
                        <span>Detail Lengkap</span>
                    </button>
                    ${authState && authState.authenticated && authState.permissions && authState.permissions.can_edit ? `
                    <button onclick="window.startGeometryEditor(${props.id})" class="px-2.5 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200 font-medium text-xs rounded-lg transition cursor-pointer" title="Edit Geometri Canvas (PostGIS)">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z"/></svg>
                    </button>
                    <button onclick="window.openEditModal(${props.id})" class="px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium text-xs rounded-lg transition cursor-pointer" title="Edit Data Tabular">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </button>
                    ` : ''}
                </div>
            </div>
        `;

        marker.bindPopup(popupContent, { maxWidth: 320 });
        markerLayerGroup.addLayer(marker);
        props._marker = marker;
    });
}

export function selectKawasan(id) {
    const feature = transmigrasiFeatures.find(f => f.properties.id === id);
    if (!feature) return;

    activeKawasanId = id;
    const props = feature.properties;

    // Pan and zoom smoothly
    map.flyTo([props.latitude, props.longitude], 12, { duration: 1.2 });

    // Open Popup
    if (props._marker) {
        props._marker.openPopup();
    }

    // Refresh markers to update active pulsing state
    renderKawasanLayers(getFilteredFeatures());

    // Highlight card in sidebar list
    document.querySelectorAll('.kawasan-sidebar-card').forEach(card => {
        if (card.dataset.id == id) {
            card.classList.add('ring-2', 'ring-blue-600', 'bg-blue-50/50');
            card.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        } else {
            card.classList.remove('ring-2', 'ring-blue-600', 'bg-blue-50/50', 'ring-purple-600', 'bg-purple-50/50', 'ring-emerald-500', 'bg-emerald-50/50');
        }
    });

    // Populate and open Detail Drawer
    populateDetailDrawer(props);
}

export function focusKawasanOnMap(id) {
    switchAppView('peta');
    setTimeout(() => {
        selectKawasan(id);
    }, 150);
}

function populateDetailDrawer(props) {
    const drawer = document.getElementById('detail-drawer');
    if (!drawer) return;

    document.getElementById('drawer-title').textContent = props.nama;
    document.getElementById('drawer-type').textContent = `${props.tipe} • ${props.kabupaten}`;
    document.getElementById('drawer-kabupaten').textContent = `${props.kabupaten}, Kecamatan ${props.kecamatan}`;
    document.getElementById('drawer-desa').textContent = props.desa;
    document.getElementById('drawer-tahun').textContent = `Tahun ${props.tahun_penempatan}`;
    
    // Population & SHM metrics
    document.getElementById('drawer-kk').textContent = `${Number(props.jumlah_kk).toLocaleString()} KK`;
    document.getElementById('drawer-jiwa').textContent = `${Number(props.jumlah_jiwa).toLocaleString()} Jiwa`;
    document.getElementById('drawer-luas').textContent = `${Number(props.luas_ha).toLocaleString()} Ha`;
    document.getElementById('drawer-status').textContent = props.status_perkembangan || 'Berkembang';
    document.getElementById('drawer-skor').textContent = `${props.skor_kemandirian || 75}/100`;

    // SHM Detail Cards
    const shmColor = getShmColor(props.persentase_shm);
    document.getElementById('drawer-shm-kk').textContent = `${Number(props.kk_shm).toLocaleString()} KK (${props.persentase_shm}%)`;
    document.getElementById('drawer-non-shm-kk').textContent = `${Number(props.kk_non_shm).toLocaleString()} KK (${(100 - props.persentase_shm).toFixed(2)}%)`;
    document.getElementById('drawer-shm-luas').textContent = `${Number(props.luas_shm_ha).toLocaleString()} Ha`;
    document.getElementById('drawer-non-shm-luas').textContent = `${Number(props.luas_non_shm_ha).toLocaleString()} Ha`;
    const shmBar = document.getElementById('drawer-shm-bar');
    shmBar.style.width = `${props.persentase_shm}%`;
    shmBar.style.backgroundColor = shmColor;

    document.getElementById('drawer-deskripsi').textContent = props.deskripsi || 'Tidak ada deskripsi profil.';
    document.getElementById('drawer-asal').textContent = props.asal_transmigran || '-';
    document.getElementById('drawer-coords').textContent = `${Number(props.latitude).toFixed(4)}° N, ${Number(props.longitude).toFixed(4)}° E`;

    // Render Edit Buttons in Drawer
    const editDrawerBtn = document.getElementById('drawer-edit-btn');
    if (editDrawerBtn) {
        editDrawerBtn.onclick = () => openEditModal(props.id);
    }

    const editGeomDrawerBtn = document.getElementById('drawer-geom-btn');
    if (editGeomDrawerBtn) {
        editGeomDrawerBtn.onclick = () => startGeometryEditor(props.id);
    }

    // Render Komoditas Badges
    const komoditasContainer = document.getElementById('drawer-komoditas');
    if (komoditasContainer && props.komoditas_unggulan) {
        const items = Array.isArray(props.komoditas_unggulan) ? props.komoditas_unggulan : [props.komoditas_unggulan];
        komoditasContainer.innerHTML = items.map(k => `
            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 text-emerald-800 border border-emerald-200/60 rounded-full text-xs font-medium">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                ${k}
            </span>
        `).join('');
    }

    // Render Supporting Facilities (Sarana Prasarana)
    const fasilitasContainer = document.getElementById('drawer-fasilitas');
    if (fasilitasContainer && props.sarana_prasarana) {
        const labels = {
            kesehatan: { label: 'Kesehatan', icon: 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z' },
            pendidikan: { label: 'Pendidikan', icon: 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253' },
            air_bersih: { label: 'Air Bersih', icon: 'M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z' },
            listrik: { label: 'Elektrifikasi & Listrik', icon: 'M13 10V3L4 14h7v7l9-11h-7z' },
            akses_jalan: { label: 'Aksesibilitas Jalan', icon: 'M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7' },
            ekonomi: { label: 'Sarana Ekonomi & Tani', icon: 'M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z' }
        };

        fasilitasContainer.innerHTML = Object.entries(props.sarana_prasarana).map(([key, val]) => {
            const meta = labels[key] || { label: key, icon: 'M5 13l4 4L19 7' };
            return `
                <div class="p-3 bg-slate-50 border border-slate-100 rounded-xl text-xs">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="p-1 bg-white text-emerald-600 rounded-md shadow-2xs border border-slate-200">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${meta.icon}"/></svg>
                        </span>
                        <span class="font-bold text-slate-800">${meta.label}</span>
                    </div>
                    <p class="text-slate-600 pl-6 leading-relaxed">${val}</p>
                </div>
            `;
        }).join('');
    }

    // Show Drawer
    drawer.classList.remove('translate-x-full');
}

export function closeDetailDrawer() {
    const drawer = document.getElementById('detail-drawer');
    if (drawer) {
        drawer.classList.add('translate-x-full');
    }
}
window.closeDetailDrawer = closeDetailDrawer;

export function resetMapView() {
    if (map) {
        map.flyTo([0.7200, 122.3500], 9, { duration: 1.0 });
    }
}

export function switchBaseMap(type) {
    Object.values(baseLayers).forEach(layer => map.removeLayer(layer));
    if (baseLayers[type]) {
        baseLayers[type].addTo(map);
    }

    document.querySelectorAll('.basemap-btn').forEach(btn => {
        if (btn.dataset.type === type) {
            btn.classList.add('bg-[#1a73e8]', 'text-white', 'shadow-xs');
            btn.classList.remove('bg-white', 'text-gray-700', 'text-slate-700', 'bg-emerald-600', 'bg-purple-900');
        } else {
            btn.classList.remove('bg-[#1a73e8]', 'bg-purple-900', 'text-white', 'bg-emerald-600');
            btn.classList.add('bg-white', 'text-slate-700');
        }
    });
}

export function switchAppView(viewName, updateHistory = true) {
    const views = ['peta', 'tabular', 'statistik', 'pendukung'];
    const active = views.includes(viewName) ? viewName : 'peta';

    // Remove preloader attribute so clean classes take over
    document.documentElement.removeAttribute('data-active-view');

    views.forEach(v => {
        const el = document.getElementById(`view-${v}`);
        const navBtn = document.getElementById(`nav-btn-${v}`);
        if (el) {
            if (v === active) el.classList.remove('hidden');
            else el.classList.add('hidden');
        }
        if (navBtn) {
            if (v === active) {
                navBtn.classList.add('bg-[#1a73e8]', 'text-white', 'font-bold', 'shadow-md', 'sidebar-active-glow');
                navBtn.classList.remove('text-slate-300', 'hover:bg-white/10', 'hover:text-white', 'text-purple-200', 'hover:bg-purple-800', 'bg-yellow-500', 'text-purple-950', 'bg-emerald-600', 'shadow-xs');
            } else {
                navBtn.classList.remove('bg-[#1a73e8]', 'text-white', 'font-bold', 'shadow-md', 'sidebar-active-glow', 'bg-yellow-500', 'text-purple-950', 'bg-emerald-600', 'shadow-xs');
                navBtn.classList.add('text-slate-300', 'hover:bg-white/10', 'hover:text-white');
            }
        }
    });

    // 1. Simpan ke localStorage agar saat F5 refresh tetap berada di tab/menu aktif
    try {
        localStorage.setItem('webgis_active_view', active);
    } catch (err) {
        // Abaikan error pada quota storage
    }

    // 2. Perbarui URL hash (#tabular, #statistik, #pendukung, #peta)
    if (updateHistory) {
        const targetHash = '#' + active;
        if (window.location.hash !== targetHash) {
            history.replaceState(null, '', targetHash);
        }
    }

    if (active === 'peta' && map) {
        setTimeout(() => map.invalidateSize(), 200);
    }

    if (active === 'statistik' && charts) {
        setTimeout(() => {
            if (charts.pie) charts.pie.resize();
            if (charts.bar) charts.bar.resize();
            if (charts.line) charts.line.resize();
        }, 150);
    }
}

function renderKawasanSidebarList(features) {
    const container = document.getElementById('kawasan-list-container');
    const badgeCount = document.getElementById('kawasan-count-badge');
    if (!container) return;

    if (badgeCount) {
        badgeCount.textContent = `${features.length} Kawasan`;
    }

    if (features.length === 0) {
        container.innerHTML = `
            <div class="p-6 text-center text-gray-400">
                <svg class="w-8 h-8 mx-auto mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="text-xs font-medium">Tidak ada kawasan yang sesuai dengan filter.</p>
            </div>
        `;
        return;
    }

    container.innerHTML = features.map(f => {
        const p = f.properties;
        const shmColor = getShmColor(p.persentase_shm);

        return `
            <div data-id="${p.id}" onclick="window.selectKawasan(${p.id})" class="kawasan-sidebar-card p-3 rounded-xl border border-gray-200 hover:border-blue-500 bg-white hover:bg-blue-50/40 shadow-xs transition-all cursor-pointer group overflow-hidden">
                <div class="flex items-center justify-between gap-1 mb-1 min-w-0">
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded truncate ${p.tipe === 'KTM' ? 'bg-blue-100 text-blue-900' : 'bg-gray-100 text-gray-700'}">
                        ${p.tipe} • ${p.kabupaten.replace('Kabupaten ', '')}
                    </span>
                    <span class="text-[10px] font-bold font-mono px-2 py-0.5 rounded-full shrink-0" style="background-color: ${shmColor}20; color: ${shmColor}">
                        ${p.persentase_shm}% SHM
                    </span>
                </div>
                <h4 class="font-bold text-xs text-gray-900 group-hover:text-blue-700 transition leading-snug truncate">
                    ${p.nama}
                </h4>
                <p class="text-[11px] text-gray-500 mb-2 truncate">
                    Kec. ${p.kecamatan}, Desa ${p.desa}
                </p>
                <div class="w-full bg-gray-100 h-1.5 rounded-full overflow-hidden mb-2">
                    <div class="h-1.5 rounded-full" style="width: ${p.persentase_shm}%; background-color: ${shmColor}"></div>
                </div>
                <div class="flex items-center justify-between text-[10px] text-gray-600 font-medium min-w-0">
                    <span class="truncate">${Number(p.jumlah_kk).toLocaleString()} KK (${p.kk_shm} SHM)</span>
                    <span class="text-gray-400 font-mono shrink-0 ml-1">${Number(p.luas_ha).toLocaleString()} Ha</span>
                </div>
            </div>
        `;
    }).join('');
}

function setupFilters() {
    const searchInput = document.getElementById('search-input');
    const filterKabupaten = document.getElementById('filter-kabupaten');
    const filterShm = document.getElementById('filter-shm');
    const togglePolygons = document.getElementById('toggle-polygons');
    const toggleMarkers = document.getElementById('toggle-markers');

    function applyFilter() {
        const filtered = getFilteredFeatures();
        renderKawasanLayers(filtered);
        renderKawasanSidebarList(filtered);
    }

    if (searchInput) searchInput.addEventListener('input', applyFilter);
    if (filterKabupaten) filterKabupaten.addEventListener('change', applyFilter);
    if (filterShm) filterShm.addEventListener('change', applyFilter);

    if (togglePolygons) {
        togglePolygons.addEventListener('change', (e) => {
            if (e.target.checked) map.addLayer(polygonLayerGroup);
            else map.removeLayer(polygonLayerGroup);
        });
    }

    if (toggleMarkers) {
        toggleMarkers.addEventListener('change', (e) => {
            if (e.target.checked) map.addLayer(markerLayerGroup);
            else map.removeLayer(markerLayerGroup);
        });
    }
}

function getFilteredFeatures() {
    const search = (document.getElementById('search-input')?.value || '').toLowerCase();
    const kab = document.getElementById('filter-kabupaten')?.value || 'all';
    const shm = document.getElementById('filter-shm')?.value || 'all';

    return transmigrasiFeatures.filter(f => {
        const p = f.properties;
        const matchSearch = !search || 
            p.nama.toLowerCase().includes(search) || 
            p.kecamatan.toLowerCase().includes(search) || 
            p.desa.toLowerCase().includes(search) ||
            (p.komoditas_unggulan && JSON.stringify(p.komoditas_unggulan).toLowerCase().includes(search));

        const matchKab = (kab === 'all') || (p.kabupaten === kab);

        let matchShm = true;
        if (shm === 'tinggi') matchShm = p.persentase_shm >= 75;
        else if (shm === 'sedang') matchShm = p.persentase_shm >= 50 && p.persentase_shm < 75;
        else if (shm === 'rendah') matchShm = p.persentase_shm < 50;

        return matchSearch && matchKab && matchShm;
    });
}

function setupControls() {
    const btnFullscreen = document.getElementById('btn-fullscreen');
    if (btnFullscreen) {
        btnFullscreen.addEventListener('click', () => {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen();
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                }
            }
        });
    }

    const mainSidebar = document.getElementById('main-sidebar');
    const btnToggleSidebar = document.getElementById('btn-toggle-sidebar');
    const btnExpandSidebar = document.getElementById('btn-expand-sidebar');
    const sidebarResizer = document.getElementById('sidebar-resizer');

    function collapseSidebar() {
        if (!mainSidebar) return;
        mainSidebar.classList.add('-translate-x-full');
        mainSidebar.classList.add('lg:-translate-x-full');
        mainSidebar.classList.add('is-collapsed');
        mainSidebar.classList.remove('lg:translate-x-0');
        if (btnExpandSidebar) btnExpandSidebar.classList.remove('hidden');
        if (sidebarResizer) sidebarResizer.style.display = 'none';
        setTimeout(() => {
            if (map) map.invalidateSize();
        }, 320);
    }

    function expandSidebar() {
        if (!mainSidebar) return;
        mainSidebar.classList.remove('-translate-x-full');
        mainSidebar.classList.remove('lg:-translate-x-full');
        mainSidebar.classList.remove('is-collapsed');
        mainSidebar.classList.add('lg:translate-x-0');
        if (btnExpandSidebar) btnExpandSidebar.classList.add('hidden');
        if (sidebarResizer) sidebarResizer.style.display = '';
        setTimeout(() => {
            if (map) map.invalidateSize();
        }, 320);
    }

    if (btnToggleSidebar) btnToggleSidebar.addEventListener('click', collapseSidebar);
    if (btnExpandSidebar) btnExpandSidebar.addEventListener('click', expandSidebar);

    // Keyboard shortcut: Ctrl+B or Cmd+B to toggle sidebar
    window.addEventListener('keydown', (e) => {
        if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'b') {
            e.preventDefault();
            if (mainSidebar.classList.contains('is-collapsed') || mainSidebar.classList.contains('-translate-x-full')) {
                expandSidebar();
            } else {
                collapseSidebar();
            }
        }
    });

    initSidebarResizer();
    initDrawerResizer();
}

function initSidebarResizer() {
    const resizer = document.getElementById('sidebar-resizer');
    const sidebar = document.getElementById('main-sidebar');
    if (!resizer || !sidebar) return;

    const DEFAULT_WIDTH = 384;
    const MIN_WIDTH = 300;

    let isDragging = false;
    let startX = 0;
    let startWidth = DEFAULT_WIDTH;
    let currentWidth = DEFAULT_WIDTH;

    // Restore saved width from localStorage
    try {
        const saved = localStorage.getItem('webgis_sidebar_width');
        if (saved && window.innerWidth >= 1024) {
            let parsed = parseInt(saved, 10);
            const maxWidth = Math.floor(window.innerWidth * 0.65);
            if (!isNaN(parsed)) {
                if (parsed < MIN_WIDTH) parsed = MIN_WIDTH;
                if (parsed > maxWidth) parsed = maxWidth;
                currentWidth = parsed;
                document.documentElement.style.setProperty('--sidebar-width', `${currentWidth}px`);
            }
        }
    } catch (e) {}

    function startDrag(clientX) {
        if (window.innerWidth < 1024) return;
        isDragging = true;
        startX = clientX;
        startWidth = sidebar.getBoundingClientRect().width;
        document.body.classList.add('is-resizing-sidebar');
        resizer.classList.add('bg-[#007acc]');
    }

    function moveDrag(clientX) {
        if (!isDragging) return;

        const deltaX = clientX - startX;
        let newWidth = Math.round(startWidth + deltaX);

        const maxWidth = Math.floor(window.innerWidth * 0.65);
        if (newWidth < MIN_WIDTH) newWidth = MIN_WIDTH;
        if (newWidth > maxWidth) newWidth = maxWidth;

        currentWidth = newWidth;
        document.documentElement.style.setProperty('--sidebar-width', `${newWidth}px`);

        if (map) {
            map.invalidateSize({ pan: false });
        }
    }

    function endDrag() {
        if (!isDragging) return;
        isDragging = false;
        document.body.classList.remove('is-resizing-sidebar');
        resizer.classList.remove('bg-[#007acc]');

        try {
            localStorage.setItem('webgis_sidebar_width', currentWidth.toString());
        } catch (e) {}

        if (map) {
            setTimeout(() => { map.invalidateSize(); }, 50);
        }
    }

    resizer.addEventListener('mousedown', (e) => {
        if (e.button !== 0) return;
        startDrag(e.clientX);
        const onMouseMove = (ev) => moveDrag(ev.clientX);
        const onMouseUp = () => {
            endDrag();
            window.removeEventListener('mousemove', onMouseMove);
            window.removeEventListener('mouseup', onMouseUp);
        };
        window.addEventListener('mousemove', onMouseMove);
        window.addEventListener('mouseup', onMouseUp);
        e.preventDefault();
    });

    resizer.addEventListener('touchstart', (e) => {
        if (e.touches.length !== 1) return;
        startDrag(e.touches[0].clientX);
        const onTouchMove = (ev) => {
            if (ev.touches.length === 1) {
                moveDrag(ev.touches[0].clientX);
                ev.preventDefault();
            }
        };
        const onTouchEnd = () => {
            endDrag();
            window.removeEventListener('touchmove', onTouchMove);
            window.removeEventListener('touchend', onTouchEnd);
        };
        window.addEventListener('touchmove', onTouchMove, { passive: false });
        window.addEventListener('touchend', onTouchEnd);
    }, { passive: true });

    resizer.addEventListener('dblclick', () => {
        currentWidth = DEFAULT_WIDTH;
        document.documentElement.style.setProperty('--sidebar-width', `${DEFAULT_WIDTH}px`);
        try {
            localStorage.removeItem('webgis_sidebar_width');
        } catch (e) {}
        if (map) {
            setTimeout(() => { map.invalidateSize(); }, 50);
        }
    });
}

function initDrawerResizer() {
    const resizer = document.getElementById('drawer-resizer');
    const drawer = document.getElementById('detail-drawer');
    if (!resizer || !drawer) return;

    const DEFAULT_WIDTH = 470;
    const MIN_WIDTH = 340;

    let isDragging = false;
    let startX = 0;
    let startWidth = DEFAULT_WIDTH;
    let currentWidth = DEFAULT_WIDTH;

    // Restore saved width from localStorage
    try {
        const saved = localStorage.getItem('webgis_drawer_width');
        if (saved && window.innerWidth >= 640) {
            const parsed = parseInt(saved, 10);
            const maxWidth = Math.floor(window.innerWidth * 0.8);
            if (parsed >= MIN_WIDTH && parsed <= maxWidth) {
                currentWidth = parsed;
                document.documentElement.style.setProperty('--drawer-width', `${currentWidth}px`);
            }
        }
    } catch (e) {}

    function startDrag(clientX) {
        if (window.innerWidth < 640) return;
        isDragging = true;
        startX = clientX;
        startWidth = drawer.getBoundingClientRect().width;
        document.body.classList.add('is-resizing-drawer');
        resizer.classList.add('bg-yellow-400');
    }

    function moveDrag(clientX) {
        if (!isDragging) return;
        const maxWidth = Math.floor(window.innerWidth * 0.8);
        const deltaX = startX - clientX;
        let newWidth = Math.round(startWidth + deltaX);

        if (newWidth < MIN_WIDTH) newWidth = MIN_WIDTH;
        if (newWidth > maxWidth) newWidth = maxWidth;

        currentWidth = newWidth;
        document.documentElement.style.setProperty('--drawer-width', `${newWidth}px`);

        if (map) {
            map.invalidateSize({ pan: false });
        }
    }

    function endDrag() {
        if (!isDragging) return;
        isDragging = false;
        document.body.classList.remove('is-resizing-drawer');
        resizer.classList.remove('bg-yellow-400');

        try {
            localStorage.setItem('webgis_drawer_width', currentWidth.toString());
        } catch (e) {}

        if (map) {
            setTimeout(() => { map.invalidateSize(); }, 50);
        }
    }

    resizer.addEventListener('mousedown', (e) => {
        if (e.button !== 0) return;
        startDrag(e.clientX);
        const onMouseMove = (ev) => moveDrag(ev.clientX);
        const onMouseUp = () => {
            endDrag();
            window.removeEventListener('mousemove', onMouseMove);
            window.removeEventListener('mouseup', onMouseUp);
        };
        window.addEventListener('mousemove', onMouseMove);
        window.addEventListener('mouseup', onMouseUp);
        e.preventDefault();
    });

    resizer.addEventListener('touchstart', (e) => {
        if (e.touches.length !== 1) return;
        startDrag(e.touches[0].clientX);
        const onTouchMove = (ev) => {
            if (ev.touches.length === 1) {
                moveDrag(ev.touches[0].clientX);
                ev.preventDefault();
            }
        };
        const onTouchEnd = () => {
            endDrag();
            window.removeEventListener('touchmove', onTouchMove);
            window.removeEventListener('touchend', onTouchEnd);
        };
        window.addEventListener('touchmove', onTouchMove, { passive: false });
        window.addEventListener('touchend', onTouchEnd);
    }, { passive: true });

    resizer.addEventListener('dblclick', () => {
        currentWidth = DEFAULT_WIDTH;
        document.documentElement.style.setProperty('--drawer-width', `${DEFAULT_WIDTH}px`);
        try {
            localStorage.removeItem('webgis_drawer_width');
        } catch (e) {}
        if (map) {
            setTimeout(() => { map.invalidateSize(); }, 50);
        }
    });
}

export function toggleLegendContent() {
    const content = document.getElementById('legend-content');
    const btn = document.getElementById('btn-toggle-legend');
    if (!content) return;
    if (content.classList.contains('hidden')) {
        content.classList.remove('hidden');
        if (btn) btn.textContent = '−';
    } else {
        content.classList.add('hidden');
        if (btn) btn.textContent = '+';
    }
}

function setupTabSwitching() {
    const validViews = ['peta', 'tabular', 'statistik', 'pendukung'];

    document.querySelectorAll('.app-nav-tab').forEach(btn => {
        btn.addEventListener('click', () => {
            const tab = btn.dataset.tab;
            if (tab) switchAppView(tab, true);
        });
    });

    // Sinkronisasi navigasi browser (Back/Forward)
    window.addEventListener('hashchange', () => {
        const currentHash = window.location.hash.replace('#', '');
        if (validViews.includes(currentHash)) {
            switchAppView(currentHash, false);
        }
    });

    // Pulihkan menu aktif dari URL hash atau localStorage saat F5 refresh
    const hash = window.location.hash.replace('#', '');
    let initialView = 'peta';
    if (validViews.includes(hash)) {
        initialView = hash;
    } else {
        try {
            const saved = localStorage.getItem('webgis_active_view');
            if (validViews.includes(saved)) {
                initialView = saved;
            }
        } catch (e) {}
    }

    if (initialView !== 'peta') {
        switchAppView(initialView, true);
    }
}

function setupFormCalculations() {
    const inputTotalKk = document.getElementById('form-jumlah-kk');
    const inputKkShm = document.getElementById('form-kk-shm');
    const inputKkNonShm = document.getElementById('form-kk-non-shm');
    const inputLuasTotal = document.getElementById('form-luas-ha');
    const inputLuasShm = document.getElementById('form-luas-shm');
    const inputLuasNonShm = document.getElementById('form-luas-non-shm');

    function calculate() {
        const total = parseFloat(inputTotalKk?.value) || 0;
        const shm = parseFloat(inputKkShm?.value) || 0;
        
        if (total > 0 && inputKkNonShm) {
            inputKkNonShm.value = Math.max(0, total - shm);
        }

        const pct = total > 0 ? ((shm / total) * 100).toFixed(2) : '0';
        const preview = document.getElementById('form-persentase-preview');
        if (preview) {
            preview.textContent = `${pct}% SHM`;
        }

        const luas = parseFloat(inputLuasTotal?.value) || 0;
        if (luas > 0 && inputLuasShm && (!inputLuasShm.value || inputLuasShm.dataset.auto === 'true')) {
            const lShm = ((parseFloat(pct) / 100) * luas).toFixed(2);
            inputLuasShm.value = lShm;
            inputLuasShm.dataset.auto = 'true';
            if (inputLuasNonShm) {
                inputLuasNonShm.value = (luas - parseFloat(lShm)).toFixed(2);
            }
        }
    }

    if (inputTotalKk) inputTotalKk.addEventListener('input', calculate);
    if (inputKkShm) inputKkShm.addEventListener('input', calculate);
    if (inputLuasTotal) inputLuasTotal.addEventListener('input', calculate);
}

function updateCalculatedPercentages() {
    const inputTotalKk = document.getElementById('form-jumlah-kk');
    const inputKkShm = document.getElementById('form-kk-shm');
    const total = parseFloat(inputTotalKk?.value) || 0;
    const shm = parseFloat(inputKkShm?.value) || 0;
    const pct = total > 0 ? ((shm / total) * 100).toFixed(2) : '0';
    const preview = document.getElementById('form-persentase-preview');
    if (preview) preview.textContent = `${pct}% SHM`;
}

// ==================== EXECUTIVE DASHBOARD & CHARTS ====================

export async function initAnalyticsDashboard() {
    try {
        const res = await fetch('/api/analytics/dashboard');
        const data = await res.json();
        if (!data.success) return;

        // Render Pie Chart (Provincial Composition)
        const pieCtx = document.getElementById('chart-pie-shm');
        if (pieCtx) {
            if (charts.pie) charts.pie.destroy();
            charts.pie = new Chart(pieCtx, {
                type: 'doughnut',
                data: {
                    labels: data.pie_composition.labels,
                    datasets: [{
                        data: data.pie_composition.data,
                        backgroundColor: data.pie_composition.colors,
                        borderWidth: 4,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom', labels: { boxWidth: 14, font: { size: 12, family: 'Plus Jakarta Sans' } } },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const val = context.raw;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const pct = ((val / total) * 100).toFixed(2);
                                    return ` ${context.label}: ${val.toLocaleString()} KK (${pct}%)`;
                                }
                            }
                        }
                    }
                }
            });
        }

        // Render Grouped Bar Chart (4 Regency comparative benchmark)
        const barCtx = document.getElementById('chart-bar-regencies');
        if (barCtx) {
            if (charts.bar) charts.bar.destroy();
            charts.bar = new Chart(barCtx, {
                type: 'bar',
                data: {
                    labels: data.rekap_kabupaten.map(r => r.singkat),
                    datasets: [
                        {
                            label: 'Sudah Sertifikat (KK SHM)',
                            data: data.rekap_kabupaten.map(r => r.kk_shm),
                            backgroundColor: '#4c1d95',
                            borderRadius: 6
                        },
                        {
                            label: 'Belum Punya Sertifikat (KK Non-SHM)',
                            data: data.rekap_kabupaten.map(r => r.kk_non_shm),
                            backgroundColor: '#eab308',
                            borderRadius: 6
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'top', labels: { boxWidth: 12, font: { size: 11, family: 'Plus Jakarta Sans' } } }
                    },
                    scales: {
                        x: { grid: { display: false } },
                        y: { beginAtZero: true, grid: { color: '#f1f5f9' } }
                    }
                }
            });
        }

        // Render Line Chart (Historical Trend 2005 - 2024)
        const lineCtx = document.getElementById('chart-line-trend');
        if (lineCtx) {
            if (charts.line) charts.line.destroy();
            charts.line = new Chart(lineCtx, {
                type: 'line',
                data: {
                    labels: data.historical_trend.map(h => h.tahun),
                    datasets: [
                        {
                            label: 'Kumulatif Transmigran (KK)',
                            data: data.historical_trend.map(h => h.kumulatif_kk),
                            borderColor: '#eab308',
                            backgroundColor: '#eab30820',
                            fill: true,
                            tension: 0.35,
                            pointRadius: 4
                        },
                        {
                            label: 'Realisasi Sertifikat SHM (KK)',
                            data: data.historical_trend.map(h => h.sertifikasi_shm_kk),
                            borderColor: '#4c1d95',
                            backgroundColor: '#4c1d9520',
                            fill: true,
                            tension: 0.35,
                            pointRadius: 4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'top', labels: { boxWidth: 12, font: { size: 11, family: 'Plus Jakarta Sans' } } }
                    },
                    scales: {
                        x: { grid: { display: false } },
                        y: { beginAtZero: true, grid: { color: '#f1f5f9' } }
                    }
                }
            });
        }

        // Render Spatial Clustering Priority Matrix Table
        renderClusteringMatrix(data.clustering);

        // Update KPI card for PostGIS Area
        const gisAreaBadge = document.getElementById('kpi-postgis-area');
        if (gisAreaBadge && data.kpi.postgis_computed_luas_ha) {
            gisAreaBadge.textContent = `${Number(data.kpi.postgis_computed_luas_ha).toLocaleString()} Ha`;
        }

    } catch (e) {
        console.error('Failed to init analytics dashboard:', e);
    }
}

function renderClusteringMatrix(clusteringData) {
    const container = document.getElementById('clustering-container');
    if (!container || !clusteringData || !clusteringData.clusters) return;

    const clusters = clusteringData.clusters;
    let html = '';

    Object.entries(clusters).forEach(([key, cl]) => {
        const colorBorder = key === 'prioritas_1' ? 'border-rose-500' : (key === 'prioritas_2' ? 'border-amber-500' : 'border-emerald-500');
        const colorBg = key === 'prioritas_1' ? 'bg-rose-50/60' : (key === 'prioritas_2' ? 'bg-amber-50/60' : 'bg-emerald-50/60');
        const colorText = key === 'prioritas_1' ? 'text-rose-700' : (key === 'prioritas_2' ? 'text-amber-700' : 'text-emerald-700');

        html += `
            <div class="border-l-4 ${colorBorder} ${colorBg} p-4 rounded-xl border border-slate-200/80 mb-4 shadow-2xs">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-2">
                    <div>
                        <h4 class="font-bold text-sm ${colorText} flex items-center gap-2">
                            <span>${cl.label}</span>
                            <span class="text-[10px] px-2 py-0.5 rounded-full font-bold bg-white shadow-2xs border border-slate-200">${cl.total_kawasan} Kawasan</span>
                        </h4>
                    </div>
                    <div class="text-xs font-semibold text-slate-600">
                        Total Backlog: <span class="font-mono text-slate-900 font-bold">${cl.total_kk_non_shm.toLocaleString()} KK</span> (${cl.total_luas_non_shm_ha.toLocaleString()} Ha)
                    </div>
                </div>
                <p class="text-xs text-slate-700 mb-3 bg-white/80 p-2.5 rounded-xl border border-slate-200/60">
                    <strong class="text-slate-900">Rekomendasi Kebijakan ATR/BPN:</strong> ${cl.rekomendasi}
                </p>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
                    ${cl.items.map(it => `
                        <div class="bg-white p-2.5 rounded-xl border border-slate-200 shadow-2xs flex items-center justify-between text-xs">
                            <div class="min-w-0 pr-2">
                                <strong class="block truncate text-slate-900 font-bold">${it.nama}</strong>
                                <span class="text-[10px] text-slate-500 block">${it.kabupaten.replace('Kabupaten ', '')} • Skor: ${it.skor_urgensi}</span>
                                <span class="text-[10px] font-bold text-amber-600 font-mono">${it.kk_non_shm} KK belum SHM</span>
                            </div>
                            <button onclick="window.focusKawasanOnMap(${it.id})" class="px-2 py-1 rounded bg-blue-50 hover:bg-[#1a73e8] hover:text-white text-blue-700 text-[10px] font-semibold transition shrink-0 cursor-pointer">
                                Peta
                            </button>
                        </div>
                    `).join('')}
                </div>
            </div>
        `;
    });

    container.innerHTML = html;
}

// ==================== AUDIT TRAIL VIEWER ====================

export async function loadAuditLogs(page = 1) {
    const tbody = document.getElementById('audit-table-body');
    const paginationEl = document.getElementById('audit-pagination');
    if (!tbody) return;

    tbody.innerHTML = '<tr><td colspan="6" class="text-center py-6 text-slate-400 text-xs">Memuat catatan log audit...</td></tr>';

    const actionFilter = document.getElementById('audit-filter-action')?.value || '';
    const kabFilter = document.getElementById('audit-filter-kabupaten')?.value || '';

    try {
        const url = `/api/audit-logs?page=${page}&action=${encodeURIComponent(actionFilter)}&kabupaten=${encodeURIComponent(kabFilter)}`;
        const res = await fetch(url);
        const data = await res.json();

        if (!data.data || data.data.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" class="text-center py-6 text-slate-400 text-xs">Belum ada rekaman audit log.</td></tr>';
            return;
        }

        tbody.innerHTML = data.data.map(log => {
            let badgeColor = 'bg-slate-100 text-slate-800';
            if (log.action.includes('CREATE')) badgeColor = 'bg-emerald-100 text-emerald-800 border-emerald-300';
            else if (log.action.includes('UPDATE')) badgeColor = 'bg-sky-100 text-sky-800 border-sky-300';
            else if (log.action.includes('GEOMETRY')) badgeColor = 'bg-blue-100 text-blue-800 border-blue-300';
            else if (log.action.includes('DELETE')) badgeColor = 'bg-rose-100 text-rose-800 border-rose-300';
            else if (log.action.includes('IMPORT')) badgeColor = 'bg-amber-100 text-amber-800 border-amber-300';
            else if (log.action.includes('LOGIN')) badgeColor = 'bg-indigo-100 text-indigo-800 border-indigo-300';

            const dateStr = new Date(log.created_at).toLocaleString('id-ID', {
                day: '2-digit', month: 'short', year: 'numeric',
                hour: '2-digit', minute: '2-digit', second: '2-digit'
            });

            return `
                <tr class="border-b border-slate-100 hover:bg-slate-50/80 transition text-xs text-slate-700">
                    <td class="py-2.5 px-3 font-mono text-[11px] text-slate-500">${dateStr}</td>
                    <td class="py-2.5 px-3">
                        <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold border ${badgeColor}">
                            ${log.action}
                        </span>
                    </td>
                    <td class="py-2.5 px-3">
                        <strong class="text-slate-900 block">${log.user_name}</strong>
                        <span class="text-[10px] text-slate-400 capitalize">${log.user_role.replace('_', ' ')}</span>
                    </td>
                    <td class="py-2.5 px-3">
                        <strong class="text-slate-800 block">${log.kawasan_nama || '-'}</strong>
                        <span class="text-[10px] text-slate-500">${log.kabupaten || '-'}</span>
                    </td>
                    <td class="py-2.5 px-3 text-slate-600 max-w-xs">
                        <span class="line-clamp-2">${log.notes || '-'}</span>
                    </td>
                    <td class="py-2.5 px-3 text-[11px] font-mono text-slate-400">
                        ${log.ip_address || '127.0.0.1'}
                    </td>
                </tr>
            `;
        }).join('');

        // Pagination controls
        if (paginationEl && data.last_page > 1) {
            paginationEl.innerHTML = `
                <div class="flex items-center justify-between text-xs text-slate-500 px-3 py-2">
                    <span>Halaman ${data.current_page} dari ${data.last_page} (${data.total} catatan log)</span>
                    <div class="flex gap-1.5">
                        <button ${data.prev_page_url ? `onclick="window.loadAuditLogs(${data.current_page - 1})"` : 'disabled'} class="px-2.5 py-1 rounded bg-slate-100 hover:bg-slate-200 disabled:opacity-40 text-xs font-semibold">Sebelumnya</button>
                        <button ${data.next_page_url ? `onclick="window.loadAuditLogs(${data.current_page + 1})"` : 'disabled'} class="px-2.5 py-1 rounded bg-slate-100 hover:bg-slate-200 disabled:opacity-40 text-xs font-semibold">Berikutnya</button>
                    </div>
                </div>
            `;
        }

    } catch (e) {
        tbody.innerHTML = `<tr><td colspan="6" class="text-center py-6 text-rose-500 text-xs">Gagal memuat audit log: ${e.message}</td></tr>`;
    }
}

// ==================== DYNAMIC CRUD MODAL HANDLERS ====================

export function openCreateModal() {
    if (!authState.permissions.can_create) {
        alert('Akses ditolak: Anda harus masuk sebagai Super Admin atau Operator Kabupaten untuk menambah data. Silakan klik menu persona di pojok kanan atas.');
        return;
    }

    const modal = document.getElementById('form-modal');
    const form = document.getElementById('kawasan-form');
    if (!modal || !form) return;

    form.reset();
    document.getElementById('form-modal-title').textContent = 'Tambah Kawasan Transmigrasi Baru';
    document.getElementById('form-method').value = 'POST';
    form.action = '/kawasan';
    document.getElementById('form-persentase-preview').textContent = '0%';

    // Auto-select kabupaten if operator kabupaten
    if (authState.permissions.is_operator && authState.permissions.kabupaten) {
        const kabSelect = document.getElementById('form-kabupaten');
        if (kabSelect) {
            kabSelect.value = authState.permissions.kabupaten;
        }
    }

    modal.classList.remove('hidden');
}

export async function openEditModal(id) {
    const feature = transmigrasiFeatures.find(f => f.properties.id === id);
    if (feature) {
        const props = feature.properties;
        if (!authState.permissions.is_super_admin) {
            if (!authState.permissions.is_operator || authState.permissions.kabupaten !== props.kabupaten) {
                alert(`Akses ditolak: Anda hanya berwenang mengubah data untuk wilayah ${authState.permissions.kabupaten || 'tidak ada'}.`);
                return;
            }
        }
    }

    const modal = document.getElementById('form-modal');
    const form = document.getElementById('kawasan-form');
    if (!modal || !form) return;

    try {
        const response = await fetch(`/kawasan/${id}`);
        const data = await response.json();

        document.getElementById('form-modal-title').textContent = `Edit Data: ${data.nama}`;
        document.getElementById('form-method').value = 'PUT';
        form.action = `/kawasan/${id}`;

        document.getElementById('form-nama').value = data.nama;
        document.getElementById('form-tipe').value = data.tipe;
        document.getElementById('form-kabupaten').value = data.kabupaten;
        document.getElementById('form-kecamatan').value = data.kecamatan;
        document.getElementById('form-desa').value = data.desa;
        document.getElementById('form-tahun').value = data.tahun_penempatan;
        document.getElementById('form-jumlah-kk').value = data.jumlah_kk;
        document.getElementById('form-jumlah-jiwa').value = data.jumlah_jiwa;
        document.getElementById('form-kk-shm').value = data.kk_shm;
        document.getElementById('form-kk-non-shm').value = data.kk_non_shm;
        document.getElementById('form-luas-ha').value = data.luas_ha;
        document.getElementById('form-luas-shm').value = data.luas_shm_ha || '';
        document.getElementById('form-luas-non-shm').value = data.luas_non_shm_ha || '';
        document.getElementById('form-status').value = data.status_perkembangan || 'Berkembang';
        document.getElementById('form-skor').value = data.skor_kemandirian || 70;
        document.getElementById('form-latitude').value = data.latitude;
        document.getElementById('form-longitude').value = data.longitude;
        document.getElementById('form-komoditas').value = Array.isArray(data.komoditas_unggulan) ? data.komoditas_unggulan.join(', ') : (data.komoditas_unggulan || '');
        document.getElementById('form-asal').value = data.asal_transmigran || '';
        document.getElementById('form-deskripsi').value = data.deskripsi || '';

        // Sarana prasarana
        if (data.sarana_prasarana) {
            document.getElementById('form-fasilitas-kesehatan').value = data.sarana_prasarana.kesehatan || '';
            document.getElementById('form-fasilitas-pendidikan').value = data.sarana_prasarana.pendidikan || '';
            document.getElementById('form-fasilitas-air').value = data.sarana_prasarana.air_bersih || '';
            document.getElementById('form-fasilitas-listrik').value = data.sarana_prasarana.listrik || '';
            document.getElementById('form-fasilitas-jalan').value = data.sarana_prasarana.akses_jalan || '';
            document.getElementById('form-fasilitas-ekonomi').value = data.sarana_prasarana.ekonomi || '';
        }

        updateCalculatedPercentages();
        modal.classList.remove('hidden');
    } catch (err) {
        alert('Gagal mengambil data kawasan untuk diedit: ' + err.message);
    }
}

export function closeFormModal() {
    const modal = document.getElementById('form-modal');
    if (modal) modal.classList.add('hidden');
}

export function pickCoordinateFromMap() {
    isPickingCoordinates = true;
    closeFormModal();
    switchAppView('peta');
    document.body.style.cursor = 'crosshair';

    const alertEl = document.getElementById('pick-coord-alert');
    if (alertEl) alertEl.classList.remove('hidden');
}

export async function deleteKawasan(id, name) {
    const feature = transmigrasiFeatures.find(f => f.properties.id === id);
    if (feature) {
        const props = feature.properties;
        if (!authState.permissions.is_super_admin) {
            if (!authState.permissions.is_operator || authState.permissions.kabupaten !== props.kabupaten) {
                alert(`Akses ditolak: Anda hanya berwenang menghapus data untuk wilayah ${authState.permissions.kabupaten || 'tidak ada'}.`);
                return;
            }
        }
    }

    if (!confirm(`Apakah Anda yakin ingin menghapus kawasan "${name}"? Data yang dihapus tidak dapat dikembalikan dan akan dicatat dalam audit trail.`)) {
        return;
    }

    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        const response = await fetch(`/kawasan/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        });

        const result = await response.json();
        if (result.success) {
            showToast('success', result.message);
            closeDetailDrawer();
            await loadGeoJsonData();
            loadAuditLogs();
        } else {
            alert(result.message);
        }
    } catch (err) {
        alert('Gagal menghapus kawasan: ' + err.message);
    }
}

// ==================== MASS DATA IMPORT MODAL ====================

export function openImportModal() {
    if (!authState.permissions.can_import) {
        alert('Akses ditolak: Hanya Super Admin dan Operator Kabupaten yang dapat mengimpor data massal.');
        return;
    }
    const modal = document.getElementById('import-modal');
    if (modal) modal.classList.remove('hidden');
}

export function closeImportModal() {
    const modal = document.getElementById('import-modal');
    if (modal) modal.classList.add('hidden');
}

export async function submitImportForm(e) {
    e.preventDefault();
    const form = e.target;
    const fileInput = form.file;

    if (!fileInput.files || fileInput.files.length === 0) {
        alert('Silakan pilih berkas Excel (.xlsx) atau CSV terlebih dahulu.');
        return;
    }

    const formData = new FormData(form);
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

    const btnSubmit = form.querySelector('button[type="submit"]');
    const originalText = btnSubmit.innerHTML;
    btnSubmit.disabled = true;
    btnSubmit.innerHTML = 'Memproses data...';

    try {
        const res = await fetch('/data/import', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: formData
        });

        const data = await res.json();
        if (data.success) {
            closeImportModal();
            showToast('success', data.message);
            await loadGeoJsonData();
            loadAuditLogs();
        } else {
            alert(data.message || 'Gagal mengimpor berkas.');
        }
    } catch (err) {
        alert('Terjadi kesalahan impor: ' + err.message);
    } finally {
        btnSubmit.disabled = false;
        btnSubmit.innerHTML = originalText;
    }
}

// ==================== TOAST & UTILITIES ====================

function showToast(type, message) {
    const toast = document.createElement('div');
    const colorClass = type === 'success' ? 'bg-[#081e36] border-blue-900' : 'bg-gray-900 border-gray-700';

    toast.className = `fixed top-4 right-4 z-50 text-white px-4 py-3 rounded-lg shadow-xl flex items-center gap-3 border ${colorClass} transition-all duration-300`;
    toast.innerHTML = `
        <span class="p-1.5 bg-yellow-500/20 text-yellow-400 rounded-lg">
            <svg class="w-4 h-4 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        </span>
        <div>
            <strong class="block text-xs font-bold text-white">${type === 'success' ? 'Berhasil!' : 'Informasi'}</strong>
            <p class="text-xs text-blue-100 font-medium">${message}</p>
        </div>
    `;

    document.body.appendChild(toast);
    setTimeout(() => {
        toast.style.opacity = '0';
        setTimeout(() => toast.remove(), 300);
    }, 4000);
}

export function exportGeoJSON() {
    window.open('/api/transmigrasi/geojson', '_blank');
}
