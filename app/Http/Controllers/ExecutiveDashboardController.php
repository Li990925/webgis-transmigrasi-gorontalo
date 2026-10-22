<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\KawasanTransmigrasi;
use App\Services\ClusteringService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExecutiveDashboardController extends Controller
{
    public function __construct(
        protected ClusteringService $clusteringService
    ) {}

    /**
     * Return comprehensive analytics and clustering data for executive dashboard.
     */
    public function analyticsData(Request $request): JsonResponse
    {
        $allKawasan = KawasanTransmigrasi::all();

        $totalKk = (int) $allKawasan->sum('jumlah_kk');
        $totalKkShm = (int) $allKawasan->sum('kk_shm');
        $totalKkNonShm = (int) $allKawasan->sum('kk_non_shm');
        $persentaseShmProvinsi = $totalKk > 0 ? round(($totalKkShm / $totalKk) * 100, 2) : 0;

        $totalLuasHa = (float) $allKawasan->sum('luas_ha');
        $totalLuasShmHa = (float) $allKawasan->sum('luas_shm_ha');
        $totalLuasNonShmHa = (float) $allKawasan->sum('luas_non_shm_ha');

        // Regency comparison
        $kabupatens = [
            'Kabupaten Boalemo',
            'Kabupaten Pohuwato',
            'Kabupaten Gorontalo',
            'Kabupaten Gorontalo Utara',
        ];

        $rekapKabupaten = [];
        foreach ($kabupatens as $kab) {
            $items = $allKawasan->where('kabupaten', $kab);
            $kk = (int) $items->sum('jumlah_kk');
            $shm = (int) $items->sum('kk_shm');
            $nonShm = (int) $items->sum('kk_non_shm');
            $pct = $kk > 0 ? round(($shm / $kk) * 100, 2) : 0;

            $rekapKabupaten[] = [
                'kabupaten' => $kab,
                'singkat' => str_replace('Kabupaten ', '', $kab),
                'jumlah_kawasan' => $items->count(),
                'total_kk' => $kk,
                'kk_shm' => $shm,
                'kk_non_shm' => $nonShm,
                'persentase_shm' => $pct,
                'total_luas_ha' => round((float) $items->sum('luas_ha'), 2),
                'luas_shm_ha' => round((float) $items->sum('luas_shm_ha'), 2),
                'luas_non_shm_ha' => round((float) $items->sum('luas_non_shm_ha'), 2),
            ];
        }

        // Historical progression data (2005 - 2024)
        $historicalTrend = [
            ['tahun' => 2005, 'penempatan_kk' => 250, 'kumulatif_kk' => 250, 'sertifikasi_shm_kk' => 120, 'persen_shm' => 48.0],
            ['tahun' => 2008, 'penempatan_kk' => 680, 'kumulatif_kk' => 930, 'sertifikasi_shm_kk' => 480, 'persen_shm' => 51.6],
            ['tahun' => 2010, 'penempatan_kk' => 500, 'kumulatif_kk' => 1430, 'sertifikasi_shm_kk' => 820, 'persen_shm' => 57.3],
            ['tahun' => 2012, 'penempatan_kk' => 650, 'kumulatif_kk' => 2080, 'sertifikasi_shm_kk' => 1240, 'persen_shm' => 59.6],
            ['tahun' => 2014, 'penempatan_kk' => 450, 'kumulatif_kk' => 2530, 'sertifikasi_shm_kk' => 1610, 'persen_shm' => 63.6],
            ['tahun' => 2016, 'penempatan_kk' => 380, 'kumulatif_kk' => 2910, 'sertifikasi_shm_kk' => 1980, 'persen_shm' => 68.0],
            ['tahun' => 2018, 'penempatan_kk' => 250, 'kumulatif_kk' => 3160, 'sertifikasi_shm_kk' => 2250, 'persen_shm' => 71.2],
            ['tahun' => 2020, 'penempatan_kk' => 150, 'kumulatif_kk' => 3310, 'sertifikasi_shm_kk' => 2420, 'persen_shm' => 73.1],
            ['tahun' => 2022, 'penempatan_kk' => 120, 'kumulatif_kk' => 3430, 'sertifikasi_shm_kk' => 2580, 'persen_shm' => 75.2],
            ['tahun' => 2024, 'penempatan_kk' => 0, 'kumulatif_kk' => $totalKk, 'sertifikasi_shm_kk' => $totalKkShm, 'persen_shm' => $persentaseShmProvinsi],
        ];

        // Spatial Clustering analysis
        $clustering = $this->clusteringService->calculatePriorityClusters();

        // Native PostGIS Area summation
        $postgisArea = DB::selectOne(
            'SELECT round(SUM(ST_Area(geom_polygon::geography) / 10000.0)::numeric, 2) as total_gis_ha FROM kawasan_transmigrasis WHERE geom_polygon IS NOT NULL'
        );

        return response()->json([
            'success' => true,
            'kpi' => [
                'total_kawasan' => $allKawasan->count(),
                'total_kk' => $totalKk,
                'total_jiwa' => (int) $allKawasan->sum('jumlah_jiwa'),
                'total_kk_shm' => $totalKkShm,
                'total_kk_non_shm' => $totalKkNonShm,
                'persentase_shm' => $persentaseShmProvinsi,
                'total_luas_ha' => $totalLuasHa,
                'total_luas_shm_ha' => $totalLuasShmHa,
                'total_luas_non_shm_ha' => $totalLuasNonShmHa,
                'postgis_computed_luas_ha' => (float) ($postgisArea->total_gis_ha ?? $totalLuasHa),
            ],
            'pie_composition' => [
                'labels' => ['Sudah SHM (Sertifikat Hak Milik)', 'Belum Bersertifikat (Non-SHM)'],
                'data' => [$totalKkShm, $totalKkNonShm],
                'percentages' => [$persentaseShmProvinsi, round(100 - $persentaseShmProvinsi, 2)],
                'colors' => ['#4c1d95', '#eab308'],
            ],
            'rekap_kabupaten' => $rekapKabupaten,
            'historical_trend' => $historicalTrend,
            'clustering' => $clustering,
        ]);
    }

    /**
     * Return audit logs trail.
     */
    public function auditLogs(Request $request): JsonResponse
    {
        $query = AuditLog::orderByDesc('created_at');

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('kabupaten')) {
            $query->where('kabupaten', $request->kabupaten);
        }

        $logs = $query->paginate(25);

        return response()->json($logs);
    }
}
