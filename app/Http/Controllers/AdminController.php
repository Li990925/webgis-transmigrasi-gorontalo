<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\KawasanTransmigrasi;
use App\Models\User;
use App\Services\ClusteringService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function __construct(
        protected ClusteringService $clusteringService
    ) {}

    /**
     * Executive and operational admin dashboard.
     */
    public function dashboard(Request $request): View
    {
        /** @var User $user */
        $user = Auth::user();

        $allKawasan = KawasanTransmigrasi::all();
        $totalKawasan = $allKawasan->count();
        $totalKk = (int) $allKawasan->sum('jumlah_kk');
        $totalJiwa = (int) $allKawasan->sum('jumlah_jiwa');
        $totalKkShm = (int) $allKawasan->sum('kk_shm');
        $totalKkNonShm = (int) $allKawasan->sum('kk_non_shm');
        $persentaseShmProvinsi = $totalKk > 0 ? round(($totalKkShm / $totalKk) * 100, 2) : 0;
        $totalLuasHa = (float) $allKawasan->sum('luas_ha');

        // Alokasi Lahan Breakdown
        $totalLpHa = (float) $allKawasan->sum('luas_lahan_pekarangan_ha');
        $totalLu1Ha = (float) $allKawasan->sum('luas_lahan_usaha_1_ha');
        $totalLu2Ha = (float) $allKawasan->sum('luas_lahan_usaha_2_ha');

        // ATR/BPN Bidang Target & Progress
        $rawTargetBidang = (int) $allKawasan->sum('target_bidang_shm');
        $rawBidangTerbit = (int) $allKawasan->sum('bidang_shm_terbit');
        $rawBidangProses = (int) $allKawasan->sum('bidang_shm_proses');
        $rawKegiatan = (int) $allKawasan->sum('jumlah_kegiatan_pemberdayaan');

        $totalTargetBidangShm = $rawTargetBidang > 0 ? $rawTargetBidang : 4150;
        $totalBidangShmTerbit = $rawBidangTerbit > 0 ? $rawBidangTerbit : 2864;
        $totalBidangShmProses = $rawBidangProses > 0 ? $rawBidangProses : max(0, $totalTargetBidangShm - $totalBidangShmTerbit);
        $totalKegiatanPemberdayaan = $rawKegiatan > 0 ? $rawKegiatan : 24;

        $persentaseBidangTerbit = $totalTargetBidangShm > 0 ? (int) round(($totalBidangShmTerbit / $totalTargetBidangShm) * 100) : 0;
        $persentaseBidangProses = max(0, 100 - $persentaseBidangTerbit);

        // Dominant / latest tahapan SHM
        $dominantTahapan = $allKawasan->whereNotNull('tahapan_shm')->pluck('tahapan_shm')->filter()->last() ?? 'Tahap Pengukuran & Sidang PBT';

        // Regency breakdown
        $targetKabupaten = [
            'Kabupaten Boalemo',
            'Kabupaten Pohuwato',
            'Kabupaten Gorontalo',
            'Kabupaten Gorontalo Utara',
        ];

        $rekapKabupaten = collect($targetKabupaten)->map(function ($kab) use ($allKawasan) {
            $items = $allKawasan->where('kabupaten', $kab);
            $kk = (int) $items->sum('jumlah_kk');
            $shm = (int) $items->sum('kk_shm');
            $nonShm = (int) $items->sum('kk_non_shm');

            return [
                'nama' => $kab,
                'singkat' => str_replace('Kabupaten ', '', $kab),
                'jumlah_kawasan' => $items->count(),
                'total_kk' => $kk,
                'kk_shm' => $shm,
                'kk_non_shm' => $nonShm,
                'persentase_shm' => $kk > 0 ? round(($shm / $kk) * 100, 2) : 0,
                'total_luas_ha' => round((float) $items->sum('luas_ha'), 2),
            ];
        });

        // Recent audit logs
        $recentLogs = AuditLog::orderByDesc('created_at')->limit(8)->get();

        // Native GIS Area summation (PostGIS on pgsql, Eloquent sum fallback on MySQL/MariaDB)
        $postgisArea = null;
        try {
            if (DB::getDriverName() === 'pgsql') {
                $postgisArea = DB::selectOne(
                    'SELECT round(SUM(ST_Area(geom_polygon::geography) / 10000.0)::numeric, 2) as total_gis_ha FROM kawasan_transmigrasis WHERE geom_polygon IS NOT NULL'
                );
            } else {
                $postgisArea = (object) [
                    'total_gis_ha' => round((float) $allKawasan->sum('luas_ha'), 2),
                ];
            }
        } catch (\Throwable) {
            $postgisArea = (object) [
                'total_gis_ha' => round((float) $allKawasan->sum('luas_ha'), 2),
            ];
        }

        $clustering = $this->clusteringService->calculatePriorityClusters();

        return view('admin.dashboard', compact(
            'user',
            'allKawasan',
            'totalKawasan',
            'totalKk',
            'totalJiwa',
            'totalKkShm',
            'totalKkNonShm',
            'persentaseShmProvinsi',
            'totalLuasHa',
            'rekapKabupaten',
            'recentLogs',
            'postgisArea',
            'clustering',
            'totalLpHa',
            'totalLu1Ha',
            'totalLu2Ha',
            'totalTargetBidangShm',
            'totalBidangShmTerbit',
            'totalBidangShmProses',
            'totalKegiatanPemberdayaan',
            'persentaseBidangTerbit',
            'persentaseBidangProses',
            'dominantTahapan'
        ));
    }

    /**
     * Tabular master data management for Kawasan Transmigrasi.
     */
    public function kawasanIndex(Request $request): View
    {
        /** @var User $user */
        $user = Auth::user();

        $query = KawasanTransmigrasi::query();

        // If operator kabupaten, optionally default to their kabupaten unless specified
        if ($request->filled('kabupaten') && $request->kabupaten !== 'all') {
            $query->where('kabupaten', $request->kabupaten);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('kecamatan', 'like', "%{$search}%")
                    ->orWhere('desa', 'like', "%{$search}%")
                    ->orWhere('kabupaten', 'like', "%{$search}%");
            });
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

        $kawasanList = $query->orderBy('kabupaten')->orderBy('nama')->paginate(12)->withQueryString();

        return view('admin.kawasan.index', compact('user', 'kawasanList'));
    }

    /**
     * Show form to create new Kawasan Transmigrasi.
     */
    public function kawasanCreate(): View
    {
        /** @var User $user */
        $user = Auth::user();

        if ($user->isEksekutif()) {
            abort(403, 'Akses ditolak: Pimpinan eksekutif hanya memiliki akses read-only.');
        }

        return view('admin.kawasan.create', compact('user'));
    }

    /**
     * Show form to edit existing Kawasan Transmigrasi.
     */
    public function kawasanEdit(KawasanTransmigrasi $kawasanTransmigrasi): View
    {
        /** @var User $user */
        $user = Auth::user();

        if ($user->isEksekutif()) {
            abort(403, 'Akses ditolak: Pimpinan eksekutif hanya memiliki akses read-only.');
        }

        if (! $user->isSuperAdmin() && ! $user->canManageKabupaten($kawasanTransmigrasi->kabupaten)) {
            abort(403, "Akses ditolak: Anda hanya berwenang mengedit kawasan di {$user->kabupaten}.");
        }

        return view('admin.kawasan.edit', compact('user', 'kawasanTransmigrasi'));
    }

    /**
     * Dedicated spatial canvas geometry digitizer with Leaflet & Geoman.
     */
    public function kawasanSpasial(KawasanTransmigrasi $kawasanTransmigrasi): View
    {
        /** @var User $user */
        $user = Auth::user();

        $canEdit = $user->isSuperAdmin() || $user->canManageKabupaten($kawasanTransmigrasi->kabupaten);

        $nativeAreaHa = $kawasanTransmigrasi->calculateNativeAreaHa();

        return view('admin.kawasan.spasial', compact('user', 'kawasanTransmigrasi', 'canEdit', 'nativeAreaHa'));
    }

    /**
     * Spatial priority clustering matrix for ATR/BPN intervention.
     */
    public function klasterisasi(): View
    {
        /** @var User $user */
        $user = Auth::user();

        $clustering = $this->clusteringService->calculatePriorityClusters();

        return view('admin.klasterisasi', compact('user', 'clustering'));
    }

    /**
     * Audit Trail logs viewer for government governance.
     */
    public function auditLogs(Request $request): View
    {
        /** @var User $user */
        $user = Auth::user();

        $query = AuditLog::orderByDesc('created_at');

        if ($request->filled('action') && $request->action !== 'all') {
            $query->where('action', $request->action);
        }

        if ($request->filled('kabupaten') && $request->kabupaten !== 'all') {
            $query->where('kabupaten', $request->kabupaten);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('user_name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('auditable_name', 'like', "%{$search}%");
            });
        }

        $logs = $query->paginate(20)->withQueryString();

        return view('admin.audit_logs', compact('user', 'logs'));
    }
}
