<?php

namespace Tests\Feature;

use App\Models\KawasanTransmigrasi;
use App\Models\User;
use App\Services\ClusteringService;
use App\Services\ExcelService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class SpatialAndDataManagementTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test native PostGIS spatial calculation.
     */
    public function test_native_postgis_computes_area(): void
    {
        $settlement = KawasanTransmigrasi::whereNotNull('polygon_geojson')->first();
        $this->assertNotNull($settlement);

        $nativeArea = $settlement->calculateNativeAreaHa();
        $this->assertNotNull($nativeArea);
        $this->assertGreaterThan(0, $nativeArea);
    }

    /**
     * Test geometry update endpoint saves to PostGIS and logs audit trail.
     */
    public function test_can_update_geometry_via_postgis_endpoint(): void
    {
        $superAdmin = User::where('role', 'super_admin')->first();
        $settlement = KawasanTransmigrasi::first();

        $newPolygon = [
            [0.670, 122.350],
            [0.670, 122.390],
            [0.650, 122.390],
            [0.650, 122.350],
            [0.670, 122.350],
        ];

        $response = $this->actingAs($superAdmin)
            ->patchJson("/api/kawasan/{$settlement->id}/geometry", [
                'latitude' => 0.660,
                'longitude' => 122.370,
                'polygon_geojson' => $newPolygon,
            ]);

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
        $this->assertNotNull($response->json('data.postgis_area_ha'));

        // Check audit log recorded GEOMETRY_EDIT
        $this->assertDatabaseHas('audit_logs', [
            'action' => 'GEOMETRY_EDIT',
            'auditable_id' => $settlement->id,
        ]);
    }

    /**
     * Test executive dashboard analytics API.
     */
    public function test_executive_analytics_dashboard_endpoint(): void
    {
        $response = $this->getJson('/api/analytics/dashboard');

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
        $response->assertJsonStructure([
            'kpi' => [
                'total_kawasan',
                'total_kk',
                'total_jiwa',
                'total_kk_shm',
                'total_kk_non_shm',
                'persentase_shm',
                'total_luas_ha',
                'postgis_computed_luas_ha',
            ],
            'pie_composition' => [
                'labels',
                'data',
                'percentages',
            ],
            'rekap_kabupaten',
            'historical_trend',
            'clustering' => [
                'clusters',
                'summary',
            ],
        ]);
    }

    /**
     * Test ClusteringService multivariate priority classification.
     */
    public function test_clustering_service_classifies_priorities(): void
    {
        $service = new ClusteringService;
        $result = $service->calculatePriorityClusters();

        $this->assertArrayHasKey('clusters', $result);
        $this->assertArrayHasKey('prioritas_1', $result['clusters']);
        $this->assertArrayHasKey('prioritas_2', $result['clusters']);
        $this->assertArrayHasKey('prioritas_3', $result['clusters']);

        // Check recommendation string exists
        $this->assertNotEmpty($result['clusters']['prioritas_1']['rekomendasi']);
        $this->assertNotEmpty($result['clusters']['prioritas_1']['items']);
    }

    /**
     * Test Excel export endpoint returns binary download.
     */
    public function test_can_export_excel(): void
    {
        $response = $this->get('/data/export?format=xlsx');

        $response->assertStatus(200);
        $this->assertStringContainsString('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', $response->headers->get('content-type') ?? '');
    }

    /**
     * Test CSV export endpoint.
     */
    public function test_can_export_csv(): void
    {
        $response = $this->get('/data/export?format=csv');

        $response->assertStatus(200);
        $this->assertStringContainsString('text/csv', $response->headers->get('content-type') ?? '');
    }

    /**
     * Test Excel template download endpoint.
     */
    public function test_can_download_template(): void
    {
        $response = $this->get('/data/template');

        $response->assertStatus(200);
        $this->assertStringContainsString('spreadsheet', $response->headers->get('content-type') ?? '');
    }

    /**
     * Test printable report view renders official elements.
     */
    public function test_printable_report_renders_with_official_elements(): void
    {
        $response = $this->get('/laporan/cetak');

        $response->assertStatus(200);
        $response->assertSee('Dinas Tenaga Kerja, ESDM dan Transmigrasi', false);
        $response->assertSee('Laporan Eksekutif Perkembangan dan Legalitas Hak Milik', false);
        $response->assertSee('HASIL KLASTERISASI SPASIAL', false);
        $response->assertSee('Dr. H. Rusli Habibie', false);
        $response->assertSee('Ir. H. Marwan Monoarfa', false);
        $response->assertSee('sig-name', false);
        $response->assertSee('editable-nip', false);
        $this->assertSame(1, substr_count($response->getContent(), 'class="kop-logo"'));
    }

    /**
     * Test mass import from Excel/CSV file.
     */
    public function test_can_import_data_file(): void
    {
        $superAdmin = User::where('role', 'super_admin')->first();

        // Generate temporary test xlsx file using ExcelService
        $excelService = new ExcelService;
        $tempPath = storage_path('app/temp_test_import.xlsx');
        $excelService->generateTemplate($tempPath);

        $file = new UploadedFile($tempPath, 'test_import.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', null, true);

        $response = $this->actingAs($superAdmin)
            ->post('/data/import', [
                'file' => $file,
            ]);

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
        $this->assertGreaterThan(0, $response->json('details.created') + $response->json('details.updated'));

        if (file_exists($tempPath)) {
            @unlink($tempPath);
        }
    }
}
