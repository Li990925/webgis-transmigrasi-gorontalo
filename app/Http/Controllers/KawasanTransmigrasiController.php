<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreKawasanTransmigrasiRequest;
use App\Http\Requests\UpdateKawasanTransmigrasiRequest;
use App\Models\AuditLog;
use App\Models\KawasanTransmigrasi;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class KawasanTransmigrasiController extends Controller
{
    /**
     * Display the WebGIS landing page with spatial map, tabular data, and supporting infrastructure.
     */
    public function index(Request $request): View
    {
        $query = KawasanTransmigrasi::query();

        if ($request->filled('kabupaten') && $request->kabupaten !== 'all') {
            $query->where('kabupaten', $request->kabupaten);
        }

        if ($request->filled('status_shm')) {
            if ($request->status_shm === 'tinggi') {
                $query->where('persentase_shm', '>=', 75);
            } elseif ($request->status_shm === 'sedang') {
                $query->whereBetween('persentase_shm', [50, 74.99]);
            } elseif ($request->status_shm === 'rendah') {
                $query->where('persentase_shm', '<', 50);
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('kecamatan', 'like', "%{$search}%")
                    ->orWhere('desa', 'like', "%{$search}%")
                    ->orWhere('komoditas_unggulan', 'like', "%{$search}%");
            });
        }

        $allKawasan = KawasanTransmigrasi::orderBy('kabupaten')->orderBy('nama')->get();
        $filteredKawasan = $query->orderBy('kabupaten')->orderBy('nama')->get();

        // Calculate aggregated KPI statistics
        $totalKawasan = $allKawasan->count();
        $totalKk = $allKawasan->sum('jumlah_kk');
        $totalJiwa = $allKawasan->sum('jumlah_jiwa');
        $totalKkShm = $allKawasan->sum('kk_shm');
        $totalKkNonShm = $allKawasan->sum('kk_non_shm');
        $persentaseShmProvinsi = $totalKk > 0 ? round(($totalKkShm / $totalKk) * 100, 2) : 0;

        $totalLuasHa = $allKawasan->sum('luas_ha');
        $totalLuasShmHa = $allKawasan->sum('luas_shm_ha');
        $totalLuasNonShmHa = $allKawasan->sum('luas_non_shm_ha');

        // Aggregation per 4 specific Regency (Kabupaten)
        $targetKabupaten = [
            'Kabupaten Boalemo',
            'Kabupaten Pohuwato',
            'Kabupaten Gorontalo',
            'Kabupaten Gorontalo Utara',
        ];

        $rekapKabupaten = collect($targetKabupaten)->map(function ($kab) use ($allKawasan) {
            $items = $allKawasan->where('kabupaten', $kab);
            $kk = $items->sum('jumlah_kk');
            $shm = $items->sum('kk_shm');
            $nonShm = $items->sum('kk_non_shm');

            return [
                'nama' => $kab,
                'singkat' => str_replace('Kabupaten ', '', $kab),
                'jumlah_kawasan' => $items->count(),
                'total_kk' => $kk,
                'total_jiwa' => $items->sum('jumlah_jiwa'),
                'kk_shm' => $shm,
                'kk_non_shm' => $nonShm,
                'persentase_shm' => $kk > 0 ? round(($shm / $kk) * 100, 2) : 0,
                'total_luas_ha' => $items->sum('luas_ha'),
                'luas_shm_ha' => $items->sum('luas_shm_ha'),
                'luas_non_shm_ha' => $items->sum('luas_non_shm_ha'),
            ];
        });

        return view('welcome', compact(
            'allKawasan',
            'filteredKawasan',
            'totalKawasan',
            'totalKk',
            'totalJiwa',
            'totalKkShm',
            'totalKkNonShm',
            'persentaseShmProvinsi',
            'totalLuasHa',
            'totalLuasShmHa',
            'totalLuasNonShmHa',
            'rekapKabupaten'
        ));
    }

    /**
     * Store a newly created KawasanTransmigrasi in storage.
     */
    public function store(StoreKawasanTransmigrasiRequest $request): RedirectResponse|JsonResponse
    {
        /** @var User|null $user */
        $user = Auth::user();

        if ($user && ! $user->isSuperAdmin() && ! $user->isOperatorKabupaten()) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak: Anda tidak memiliki wewenang menambah data.',
            ], 403);
        }

        $validated = $request->validated();

        if ($user && $user->isOperatorKabupaten() && ! $user->canManageKabupaten($validated['kabupaten'])) {
            return response()->json([
                'success' => false,
                'message' => "Akses ditolak: Anda hanya berwenang menambah data di wilayah {$user->kabupaten}.",
            ], 403);
        }

        $slug = Str::slug($validated['nama'].'-'.$validated['kabupaten']);
        if (KawasanTransmigrasi::where('slug', $slug)->exists()) {
            $slug .= '-'.time();
        }
        $validated['slug'] = $slug;

        // Auto calculate SHM percentage
        if ($validated['jumlah_kk'] > 0) {
            $validated['persentase_shm'] = round(($validated['kk_shm'] / $validated['jumlah_kk']) * 100, 2);
        } else {
            $validated['persentase_shm'] = 0;
        }

        // Auto calculate area if not provided
        if (empty($validated['luas_shm_ha'])) {
            $validated['luas_shm_ha'] = round(($validated['persentase_shm'] / 100) * $validated['luas_ha'], 2);
        }
        if (empty($validated['luas_non_shm_ha'])) {
            $validated['luas_non_shm_ha'] = round($validated['luas_ha'] - $validated['luas_shm_ha'], 2);
        }

        // Handle komoditas input
        if (isset($validated['komoditas_unggulan']) && is_string($validated['komoditas_unggulan'])) {
            $validated['komoditas_unggulan'] = array_values(array_filter(array_map('trim', explode(',', $validated['komoditas_unggulan']))));
        }

        // Auto generate default rectangular polygon around center if none provided
        if (empty($validated['polygon_geojson'])) {
            $lat = (float) $validated['latitude'];
            $lng = (float) $validated['longitude'];
            $delta = 0.015;
            $validated['polygon_geojson'] = [
                [$lat + $delta, $lng - $delta],
                [$lat + $delta, $lng + $delta],
                [$lat - $delta, $lng + $delta],
                [$lat - $delta, $lng - $delta],
                [$lat + $delta, $lng - $delta],
            ];
        }

        $kawasan = KawasanTransmigrasi::create($validated);

        AuditLog::record(
            'CREATE',
            $kawasan,
            null,
            $validated,
            "Penambahan kawasan transmigrasi baru: {$kawasan->nama}",
            $user
        );

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Data kawasan transmigrasi berhasil ditambahkan!',
                'data' => $kawasan,
            ], 201);
        }

        return redirect()->route('admin.kawasan.index')->with('success', "Kawasan {$kawasan->nama} berhasil ditambahkan!");
    }

    /**
     * Show single KawasanTransmigrasi in JSON format for editing modal.
     */
    public function show(KawasanTransmigrasi $kawasanTransmigrasi): JsonResponse
    {
        return response()->json($kawasanTransmigrasi);
    }

    /**
     * Update the specified KawasanTransmigrasi in storage.
     */
    public function update(UpdateKawasanTransmigrasiRequest $request, KawasanTransmigrasi $kawasanTransmigrasi): RedirectResponse|JsonResponse
    {
        /** @var User|null $user */
        $user = Auth::user();

        if ($user && ! $user->isSuperAdmin() && ! $user->canManageKabupaten($kawasanTransmigrasi->kabupaten)) {
            return response()->json([
                'success' => false,
                'message' => "Akses ditolak: Anda hanya berwenang memperbarui data untuk wilayah {$user->kabupaten}.",
            ], 403);
        }

        $validated = $request->validated();
        $oldValues = $kawasanTransmigrasi->toArray();

        if (isset($validated['jumlah_kk']) && isset($validated['kk_shm'])) {
            $validated['persentase_shm'] = $validated['jumlah_kk'] > 0
                ? round(($validated['kk_shm'] / $validated['jumlah_kk']) * 100, 2)
                : 0;

            if (isset($validated['luas_ha'])) {
                $validated['luas_shm_ha'] = round(($validated['persentase_shm'] / 100) * $validated['luas_ha'], 2);
                $validated['luas_non_shm_ha'] = round($validated['luas_ha'] - $validated['luas_shm_ha'], 2);
            }
        }

        if (isset($validated['komoditas_unggulan']) && is_string($validated['komoditas_unggulan'])) {
            $validated['komoditas_unggulan'] = array_values(array_filter(array_map('trim', explode(',', $validated['komoditas_unggulan']))));
        }

        $kawasanTransmigrasi->update($validated);

        AuditLog::record(
            'UPDATE',
            $kawasanTransmigrasi,
            $oldValues,
            $validated,
            "Pembaruan data atribut kawasan transmigrasi: {$kawasanTransmigrasi->nama}",
            $user
        );

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Data kawasan berhasil diperbarui!',
                'data' => $kawasanTransmigrasi,
            ]);
        }

        return redirect()->route('admin.kawasan.index')->with('success', "Data {$kawasanTransmigrasi->nama} berhasil diperbarui!");
    }

    /**
     * Dedicated endpoint to update spatial geometries from Leaflet Geoman editor.
     */
    public function updateGeometry(Request $request, KawasanTransmigrasi $kawasanTransmigrasi): JsonResponse
    {
        /** @var User|null $user */
        $user = Auth::user();

        if ($user && ! $user->isSuperAdmin() && ! $user->canManageKabupaten($kawasanTransmigrasi->kabupaten)) {
            return response()->json([
                'success' => false,
                'message' => "Akses ditolak: Anda tidak memiliki wewenang mengedit geometri wilayah {$kawasanTransmigrasi->kabupaten}.",
            ], 403);
        }

        $validated = $request->validate([
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'polygon_geojson' => 'required|array',
        ]);

        $oldValues = [
            'latitude' => $kawasanTransmigrasi->latitude,
            'longitude' => $kawasanTransmigrasi->longitude,
            'polygon_geojson' => $kawasanTransmigrasi->polygon_geojson,
        ];

        $kawasanTransmigrasi->update($validated);

        // Recalculate native PostGIS area
        $nativeAreaHa = $kawasanTransmigrasi->calculateNativeAreaHa();

        AuditLog::record(
            'GEOMETRY_EDIT',
            $kawasanTransmigrasi,
            $oldValues,
            $validated,
            "Perubahan geometri spasial (poligon/marker) kawasan: {$kawasanTransmigrasi->nama}".($nativeAreaHa ? " (Luas PostGIS: {$nativeAreaHa} Ha)" : ''),
            $user
        );

        return response()->json([
            'success' => true,
            'message' => "Geometri kawasan {$kawasanTransmigrasi->nama} berhasil disimpan ke PostGIS!",
            'data' => [
                'kawasan' => $kawasanTransmigrasi,
                'postgis_area_ha' => $nativeAreaHa,
            ],
        ]);
    }

    /**
     * Remove the specified KawasanTransmigrasi from storage.
     */
    public function destroy(Request $request, KawasanTransmigrasi $kawasanTransmigrasi): RedirectResponse|JsonResponse
    {
        /** @var User|null $user */
        $user = Auth::user();

        if ($user && ! $user->isSuperAdmin() && ! $user->canManageKabupaten($kawasanTransmigrasi->kabupaten)) {
            return response()->json([
                'success' => false,
                'message' => "Akses ditolak: Anda hanya berwenang menghapus data di wilayah {$user->kabupaten}.",
            ], 403);
        }

        $nama = $kawasanTransmigrasi->nama;
        $oldValues = $kawasanTransmigrasi->toArray();
        $kawasanTransmigrasi->delete();

        AuditLog::record(
            'DELETE',
            null,
            $oldValues,
            null,
            "Penghapusan data kawasan transmigrasi: {$nama} ({$kawasanTransmigrasi->kabupaten})",
            $user
        );

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Data {$nama} berhasil dihapus!",
            ]);
        }

        return redirect()->route('admin.kawasan.index')->with('success', "Kawasan {$nama} berhasil dihapus!");
    }

    /**
     * Provide GeoJSON FeatureCollection endpoint for GIS layers.
     */
    public function geojson(): JsonResponse
    {
        $kawasan = KawasanTransmigrasi::all();

        $features = $kawasan->map(function ($item) {
            $polygonCoords = [];
            if (! empty($item->polygon_geojson)) {
                // GeoJSON format requires [Longitude, Latitude]
                $polygonCoords = [
                    array_map(fn ($pt) => [$pt[1], $pt[0]], $item->polygon_geojson),
                ];
            }

            return [
                'type' => 'Feature',
                'geometry' => [
                    'type' => 'Polygon',
                    'coordinates' => $polygonCoords,
                ],
                'properties' => [
                    'id' => $item->id,
                    'slug' => $item->slug,
                    'nama' => $item->nama,
                    'tipe' => $item->tipe,
                    'kabupaten' => $item->kabupaten,
                    'kecamatan' => $item->kecamatan,
                    'desa' => $item->desa,
                    'tahun_penempatan' => $item->tahun_penempatan,
                    'jumlah_kk' => $item->jumlah_kk,
                    'jumlah_jiwa' => $item->jumlah_jiwa,
                    'kk_shm' => $item->kk_shm,
                    'kk_non_shm' => $item->kk_non_shm,
                    'persentase_shm' => $item->persentase_shm,
                    'luas_ha' => $item->luas_ha,
                    'luas_shm_ha' => $item->luas_shm_ha,
                    'luas_non_shm_ha' => $item->luas_non_shm_ha,
                    'komoditas_unggulan' => $item->komoditas_unggulan,
                    'sarana_prasarana' => $item->sarana_prasarana,
                    'status_perkembangan' => $item->status_perkembangan,
                    'skor_kemandirian' => $item->skor_kemandirian,
                    'latitude' => $item->latitude,
                    'longitude' => $item->longitude,
                ],
            ];
        });

        return response()->json([
            'type' => 'FeatureCollection',
            'name' => 'Kawasan_Transmigrasi_Provinsi_Gorontalo',
            'crs' => [
                'type' => 'name',
                'properties' => ['name' => 'urn:ogc:def:crs:OGC:1.3:CRS84'],
            ],
            'features' => $features,
        ]);
    }
}
