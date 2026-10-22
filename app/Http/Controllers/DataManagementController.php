<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\KawasanTransmigrasi;
use App\Models\User;
use App\Services\ClusteringService;
use App\Services\ExcelService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DataManagementController extends Controller
{
    public function __construct(
        protected ExcelService $excelService,
        protected ClusteringService $clusteringService
    ) {}

    /**
     * Export all or filtered settlements to Excel (.xlsx) or CSV.
     */
    public function export(Request $request): BinaryFileResponse
    {
        $format = $request->query('format', 'xlsx');
        $kabupaten = $request->query('kabupaten');

        $query = KawasanTransmigrasi::query()->orderBy('kabupaten')->orderBy('nama');

        if ($kabupaten && $kabupaten !== 'all') {
            $query->where('kabupaten', $kabupaten);
        }

        $settlements = $query->get();

        $fileName = 'Data_Kawasan_Transmigrasi_Gorontalo_'.date('Ymd_His').'.'.$format;
        $tempPath = storage_path('app/temp_'.$fileName);

        $this->excelService->exportToPath($tempPath, $settlements, $format);

        /** @var User|null $user */
        $user = Auth::user();
        AuditLog::record(
            'EXPORT',
            null,
            null,
            ['count' => $settlements->count(), 'kabupaten' => $kabupaten, 'format' => $format],
            "Ekspor data {$settlements->count()} kawasan format {$format} oleh ".($user?->name ?? 'Tamu'),
            $user
        );

        return response()->download($tempPath, $fileName)->deleteFileAfterSend(true);
    }

    /**
     * Download sample Excel template.
     */
    public function downloadTemplate(): BinaryFileResponse
    {
        $fileName = 'Template_Impor_Transmigrasi_Gorontalo.xlsx';
        $tempPath = storage_path('app/'.$fileName);

        $this->excelService->generateTemplate($tempPath);

        return response()->download($tempPath, $fileName)->deleteFileAfterSend(true);
    }

    /**
     * Handle Excel or CSV file upload for mass data import.
     */
    public function import(Request $request): JsonResponse
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (! $user || (! $user->isSuperAdmin() && ! $user->isOperatorKabupaten())) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak: Hanya Super Admin dan Operator Kabupaten yang dapat mengimpor data.',
            ], 403);
        }

        $request->validate([
            'file' => 'required|file|mimes:xlsx,csv,txt|max:10240',
        ]);

        $uploadedFile = $request->file('file');
        $extension = $uploadedFile->getClientOriginalExtension();
        $storedPath = $uploadedFile->storeAs('imports', 'import_'.time().'.'.$extension);
        $fullPath = Storage::path($storedPath);

        $result = $this->excelService->importFromFile($fullPath, $user);

        if (file_exists($fullPath)) {
            @unlink($fullPath);
        }

        return response()->json([
            'success' => true,
            'message' => "Impor selesai: {$result['created']} kawasan baru ditambahkan, {$result['updated']} diperbarui.",
            'details' => $result,
        ]);
    }

    /**
     * Render the official printable report view formatted with Kop Dinas and signatures.
     */
    public function printReport(Request $request): View
    {
        $kabupaten = $request->query('kabupaten');
        $query = KawasanTransmigrasi::query()->orderBy('kabupaten')->orderBy('nama');

        if ($kabupaten && $kabupaten !== 'all') {
            $query->where('kabupaten', $kabupaten);
        }

        $settlements = $query->get();

        $totalKk = (int) $settlements->sum('jumlah_kk');
        $totalKkShm = (int) $settlements->sum('kk_shm');
        $totalKkNonShm = (int) $settlements->sum('kk_non_shm');
        $persentaseShm = $totalKk > 0 ? round(($totalKkShm / $totalKk) * 100, 2) : 0;

        $clustering = $this->clusteringService->calculatePriorityClusters();

        return view('laporan_cetak', [
            'settlements' => $settlements,
            'kabupatenFilter' => $kabupaten,
            'totalKawasan' => $settlements->count(),
            'totalKk' => $totalKk,
            'totalJiwa' => (int) $settlements->sum('jumlah_jiwa'),
            'totalKkShm' => $totalKkShm,
            'totalKkNonShm' => $totalKkNonShm,
            'persentaseShm' => $persentaseShm,
            'totalLuasHa' => (float) $settlements->sum('luas_ha'),
            'totalLuasShmHa' => (float) $settlements->sum('luas_shm_ha'),
            'totalLuasNonShmHa' => (float) $settlements->sum('luas_non_shm_ha'),
            'clustering' => $clustering,
            'tanggalCetak' => now()->translatedFormat('d F Y'),
        ]);
    }
}
