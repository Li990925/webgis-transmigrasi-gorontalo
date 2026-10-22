<?php

namespace Tests\Feature;

use App\Models\KawasanTransmigrasi;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class KawasanTransmigrasiTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test the WebGIS landing page returns successful response and renders view elements.
     */
    public function test_landing_page_renders_successfully(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('SI-TRANSMIGRASI', false);
        $response->assertSee('GORONTALO', false);
        $response->assertSee('Data Tabular Transmigrasi', false);
        $response->assertSee('Capaian Sertifikasi Hak Milik', false);
        $response->assertSee('Kabupaten Gorontalo', false);
        $response->assertSee('Kabupaten Boalemo', false);
        $response->assertSee('Kabupaten Pohuwato', false);
        $response->assertSee('Kabupaten Gorontalo Utara', false);
    }

    /**
     * Test the GeoJSON API returns valid GeoJSON format.
     */
    public function test_geojson_api_returns_valid_features(): void
    {
        $response = $this->get('/api/transmigrasi/geojson');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'type',
            'name',
            'features' => [
                '*' => [
                    'type',
                    'geometry' => [
                        'type',
                        'coordinates',
                    ],
                    'properties' => [
                        'id',
                        'nama',
                        'tipe',
                        'kabupaten',
                        'jumlah_kk',
                        'jumlah_jiwa',
                        'kk_shm',
                        'kk_non_shm',
                        'persentase_shm',
                        'luas_ha',
                    ],
                ],
            ],
        ]);
    }

    /**
     * Test single kawasan can be fetched in JSON format for the dynamic edit modal.
     */
    public function test_can_fetch_single_kawasan_json(): void
    {
        $item = KawasanTransmigrasi::first();
        $this->assertNotNull($item);

        $response = $this->getJson("/kawasan/{$item->id}");

        $response->assertStatus(200);
        $response->assertJsonPath('id', $item->id);
        $response->assertJsonPath('nama', $item->nama);
        $response->assertJsonPath('kabupaten', $item->kabupaten);
    }

    /**
     * Test creating a new transmigration area dynamically via POST.
     */
    public function test_can_create_new_kawasan_dynamically(): void
    {
        $payload = [
            'nama' => 'UPT Test Baru Dumolabu',
            'tipe' => 'UPT',
            'kabupaten' => 'Kabupaten Boalemo',
            'kecamatan' => 'Tilamuta',
            'desa' => 'Dumolabu',
            'tahun_penempatan' => 2021,
            'jumlah_kk' => 300,
            'jumlah_jiwa' => 1200,
            'kk_shm' => 240,
            'kk_non_shm' => 60,
            'luas_ha' => 1500,
            'latitude' => 0.5500,
            'longitude' => 122.3500,
            'status_perkembangan' => 'Pembinaan',
            'skor_kemandirian' => 78,
            'komoditas_unggulan' => 'Jagung, Kelapa, Nilam',
            'sarana_prasarana' => [
                'kesehatan' => 'Poskesdes aktif',
                'pendidikan' => 'SD Negeri 1',
                'air_bersih' => 'Sumur bor terdistribusi',
                'listrik' => 'PLN 24 jam',
                'akses_jalan' => 'Rabat beton jalan usaha tani',
                'ekonomi' => 'Koperasi tani dan lumbung jagung',
            ],
            'asal_transmigran' => 'Jawa Timur dan Warga Lokal Gorontalo',
            'deskripsi' => 'Kawasan percontohan integrasi pertanian jagung dan kelapa.',
        ];

        $response = $this->postJson('/kawasan', $payload);

        $response->assertStatus(201);
        $response->assertJsonPath('success', true);
        $response->assertJsonPath('data.nama', 'UPT Test Baru Dumolabu');
        $response->assertJsonPath('data.persentase_shm', fn ($val) => (float) $val == 80.0);

        $this->assertDatabaseHas('kawasan_transmigrasis', [
            'nama' => 'UPT Test Baru Dumolabu',
            'kabupaten' => 'Kabupaten Boalemo',
            'jumlah_kk' => 300,
            'kk_shm' => 240,
        ]);
    }

    /**
     * Test updating an existing transmigration area dynamically via PUT.
     */
    public function test_can_update_kawasan_dynamically(): void
    {
        $item = KawasanTransmigrasi::where('nama', 'UPT Test Baru Dumolabu')->first()
            ?? KawasanTransmigrasi::first();

        $updatePayload = [
            'nama' => 'UPT Test Baru Terupdate',
            'tipe' => 'KTM',
            'kabupaten' => 'Kabupaten Boalemo',
            'kecamatan' => 'Tilamuta',
            'desa' => 'Dumolabu Jaya',
            'tahun_penempatan' => 2021,
            'jumlah_kk' => 320,
            'jumlah_jiwa' => 1280,
            'kk_shm' => 280,
            'kk_non_shm' => 40,
            'luas_ha' => 1600,
            'latitude' => 0.5510,
            'longitude' => 122.3510,
        ];

        $response = $this->putJson("/kawasan/{$item->id}", $updatePayload);

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
        $response->assertJsonPath('data.nama', 'UPT Test Baru Terupdate');
        $response->assertJsonPath('data.tipe', 'KTM');
        $response->assertJsonPath('data.persentase_shm', fn ($val) => (float) $val == 87.5);

        $this->assertDatabaseHas('kawasan_transmigrasis', [
            'id' => $item->id,
            'nama' => 'UPT Test Baru Terupdate',
            'tipe' => 'KTM',
            'kk_shm' => 280,
        ]);
    }

    /**
     * Test deleting a transmigration area dynamically via DELETE.
     */
    public function test_can_delete_kawasan_dynamically(): void
    {
        $item = KawasanTransmigrasi::create([
            'slug' => 'test-hapus-area-'.time(),
            'nama' => 'UPT Akan Dihapus',
            'tipe' => 'UPT',
            'kabupaten' => 'Kabupaten Pohuwato',
            'kecamatan' => 'Lemito',
            'desa' => 'Kenari',
            'tahun_penempatan' => 2018,
            'jumlah_kk' => 100,
            'jumlah_jiwa' => 400,
            'kk_shm' => 50,
            'kk_non_shm' => 50,
            'persentase_shm' => 50,
            'luas_ha' => 500,
            'latitude' => 0.6500,
            'longitude' => 121.6500,
        ]);

        $response = $this->deleteJson("/kawasan/{$item->id}");

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);

        $this->assertDatabaseMissing('kawasan_transmigrasis', [
            'id' => $item->id,
        ]);
    }
}
