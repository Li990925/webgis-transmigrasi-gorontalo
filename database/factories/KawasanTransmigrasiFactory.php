<?php

namespace Database\Factories;

use App\Models\KawasanTransmigrasi;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<KawasanTransmigrasi>
 */
class KawasanTransmigrasiFactory extends Factory
{
    protected $model = KawasanTransmigrasi::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $nama = 'UPT '.fake()->city();
        $jumlahKK = fake()->numberBetween(150, 700);
        $kkShm = fake()->numberBetween(50, $jumlahKK);
        $kkNonShm = $jumlahKK - $kkShm;
        $persentaseShm = round(($kkShm / $jumlahKK) * 100, 2);
        $luasHa = fake()->randomFloat(2, 1000, 5000);
        $luasShmHa = round(($persentaseShm / 100) * $luasHa, 2);
        $luasNonShmHa = round($luasHa - $luasShmHa, 2);

        return [
            'slug' => Str::slug($nama.'-'.fake()->unique()->numberBetween(100, 999)),
            'nama' => $nama,
            'tipe' => fake()->randomElement(['KTM', 'UPT', 'SP']),
            'kabupaten' => fake()->randomElement([
                'Kabupaten Gorontalo',
                'Kabupaten Boalemo',
                'Kabupaten Pohuwato',
                'Kabupaten Gorontalo Utara',
            ]),
            'kecamatan' => fake()->city(),
            'desa' => fake()->streetName(),
            'tahun_penempatan' => fake()->numberBetween(2005, 2024),
            'jumlah_kk' => $jumlahKK,
            'jumlah_jiwa' => $jumlahKK * 4,
            'kk_shm' => $kkShm,
            'kk_non_shm' => $kkNonShm,
            'persentase_shm' => $persentaseShm,
            'luas_ha' => $luasHa,
            'luas_shm_ha' => $luasShmHa,
            'luas_non_shm_ha' => $luasNonShmHa,
            'komoditas_unggulan' => ['Jagung', 'Padi', 'Kelapa'],
            'asal_transmigran' => 'Jawa Timur, Jawa Tengah, Warga Lokal',
            'sarana_prasarana' => [
                'kesehatan' => 'Poskesdes',
                'pendidikan' => 'SD Negeri',
                'air_bersih' => 'Pamsimas',
                'listrik' => 'PLN',
                'akses_jalan' => 'Jalan Rabat Beton',
                'ekonomi' => 'KUD',
            ],
            'status_perkembangan' => fake()->randomElement(['Mandiri', 'Berkembang', 'Tahap Pembinaan']),
            'skor_kemandirian' => fake()->numberBetween(60, 95),
            'latitude' => fake()->latitude(0.4, 1.0),
            'longitude' => fake()->longitude(121.3, 123.3),
            'polygon_geojson' => null,
            'deskripsi' => fake()->paragraph(),
        ];
    }
}
