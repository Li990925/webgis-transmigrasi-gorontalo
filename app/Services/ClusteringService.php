<?php

namespace App\Services;

use App\Models\KawasanTransmigrasi;

class ClusteringService
{
    /**
     * Compute spatial multivariate priority clustering for land certification intervention.
     *
     * @return array{
     *     clusters: array<string, array{
     *         label: string,
     *         level: string,
     *         badge_color: string,
     *         total_kawasan: int,
     *         total_kk_non_shm: int,
     *         total_luas_non_shm_ha: float,
     *         rekomendasi: string,
     *         items: list<array<string, mixed>>
     *     }>,
     *     summary: array{
     *         total_urgensi_tinggi: int,
     *         total_urgensi_sedang: int,
     *         total_urgensi_rendah: int,
     *         kabupaten_paling_kritis: string
     *     }
     * }
     */
    public function calculatePriorityClusters(): array
    {
        $settlements = KawasanTransmigrasi::all();

        if ($settlements->isEmpty()) {
            return [
                'clusters' => [],
                'prioritas_1' => [],
                'prioritas_2' => [],
                'prioritas_3' => [],
                'summary' => [
                    'total_urgensi_tinggi' => 0,
                    'total_urgensi_sedang' => 0,
                    'total_urgensi_rendah' => 0,
                    'kabupaten_paling_kritis' => '-',
                ],
            ];
        }

        $maxKkNonShm = max(1, $settlements->max('kk_non_shm'));
        $maxLuasNonShm = max(1, $settlements->max('luas_non_shm_ha'));

        $scored = $settlements->map(function (KawasanTransmigrasi $item) use ($maxKkNonShm, $maxLuasNonShm) {
            $persenNonShm = max(0, 100 - $item->persentase_shm);

            // Normalized component weights (Simple Additive Weighting - SAW):
            // 40% percentage of non-SHM, 35% absolute volume of non-SHM families, 25% unregistered land area
            $scorePersen = ($persenNonShm / 100) * 40;
            $scoreKk = ($item->kk_non_shm / $maxKkNonShm) * 35;
            $scoreLuas = ($item->luas_non_shm_ha / $maxLuasNonShm) * 25;

            $compositeScore = round($scorePersen + $scoreKk + $scoreLuas, 2);

            if ($compositeScore >= 55 || $persenNonShm >= 50) {
                $clusterKey = 'prioritas_1';
                $clusterName = 'Prioritas I (Mendesak / Intervensi Khusus)';
                $clusterBadge = 'danger';
                $rekomendasi = 'Percepatan alokasi PTSL Terpadu khusus transmigrasi dan penyelesaian tapal batas HPL dengan ATR/BPN.';
            } elseif ($compositeScore >= 30) {
                $clusterKey = 'prioritas_2';
                $clusterName = 'Prioritas II (Penanganan Menengah / Terjadwal)';
                $clusterBadge = 'warning';
                $rekomendasi = 'Pemberkasan Lahan Usaha II (LU-II) dan pemutakhiran data fisik/yuridis pemegang hak.';
            } else {
                $clusterKey = 'prioritas_3';
                $clusterName = 'Prioritas III (Pemeliharaan & Monitoring)';
                $clusterBadge = 'success';
                $rekomendasi = 'Pemeliharaan patok batas bidang dan fasilitasi peningkatan sertifikat elektronik.';
            }

            return [
                'id' => $item->id,
                'slug' => $item->slug,
                'nama' => $item->nama,
                'kabupaten' => $item->kabupaten,
                'kecamatan' => $item->kecamatan,
                'desa' => $item->desa,
                'jumlah_kk' => $item->jumlah_kk,
                'kk_shm' => $item->kk_shm,
                'kk_non_shm' => $item->kk_non_shm,
                'persentase_shm' => $item->persentase_shm,
                'persentase_non_shm' => round($persenNonShm, 2),
                'luas_ha' => $item->luas_ha,
                'luas_non_shm_ha' => $item->luas_non_shm_ha,
                'score_persen' => round($scorePersen, 2),
                'score_kk' => round($scoreKk, 2),
                'score_luas' => round($scoreLuas, 2),
                'skor_urgensi' => $compositeScore,
                'cluster_key' => $clusterKey,
                'cluster_name' => $clusterName,
                'cluster_badge' => $clusterBadge,
                'rekomendasi' => $rekomendasi,
                'latitude' => $item->latitude,
                'longitude' => $item->longitude,
            ];
        })->sortByDesc('skor_urgensi')->values();

        $clusters = [
            'prioritas_1' => [
                'label' => 'Prioritas I (Mendesak / Intervensi Khusus ATR/BPN)',
                'level' => 'Tinggi',
                'badge_color' => '#ef4444',
                'total_kawasan' => $scored->where('cluster_key', 'prioritas_1')->count(),
                'total_kk_non_shm' => $scored->where('cluster_key', 'prioritas_1')->sum('kk_non_shm'),
                'total_luas_non_shm_ha' => round($scored->where('cluster_key', 'prioritas_1')->sum('luas_non_shm_ha'), 2),
                'rekomendasi' => 'Akselerasi penerbitan SK Hak Pengelolaan Lahan (HPL) Transmigrasi, percepatan PTSL khusus transmigrasi dengan Kantor Pertanahan ATR/BPN, serta mediasi tapal batas desa.',
                'items' => $scored->where('cluster_key', 'prioritas_1')->values()->all(),
            ],
            'prioritas_2' => [
                'label' => 'Prioritas II (Penanganan Menengah / Terjadwal)',
                'level' => 'Sedang',
                'badge_color' => '#f59e0b',
                'total_kawasan' => $scored->where('cluster_key', 'prioritas_2')->count(),
                'total_kk_non_shm' => $scored->where('cluster_key', 'prioritas_2')->sum('kk_non_shm'),
                'total_luas_non_shm_ha' => round($scored->where('cluster_key', 'prioritas_2')->sum('luas_non_shm_ha'), 2),
                'rekomendasi' => 'Pemberkasan subjek/objek pemilikan tanah pekarangan & usaha I, sinkronisasi data kependudukan Dukcapil untuk persiapan pendaftaran sertifikat reguler.',
                'items' => $scored->where('cluster_key', 'prioritas_2')->values()->all(),
            ],
            'prioritas_3' => [
                'label' => 'Prioritas III (Pemeliharaan & Monitoring Wilayah)',
                'level' => 'Rendah',
                'badge_color' => '#10b981',
                'total_kawasan' => $scored->where('cluster_key', 'prioritas_3')->count(),
                'total_kk_non_shm' => $scored->where('cluster_key', 'prioritas_3')->sum('kk_non_shm'),
                'total_luas_non_shm_ha' => round($scored->where('cluster_key', 'prioritas_3')->sum('luas_non_shm_ha'), 2),
                'rekomendasi' => 'Monitoring kepatuhan batas tanah, penguatan legalitas sertifikat waris/peralihan hak, serta fasilitasi peningkatan hak milik menjadi sertifikat elektronik.',
                'items' => $scored->where('cluster_key', 'prioritas_3')->values()->all(),
            ],
        ];

        // Find regency with highest backlog of Non-SHM families
        $kabupatenSummary = $settlements->groupBy('kabupaten')->map(fn ($group) => $group->sum('kk_non_shm'));
        $palingKritis = $kabupatenSummary->sortDesc()->keys()->first() ?? 'Kabupaten Gorontalo';

        return [
            'clusters' => $clusters,
            'prioritas_1' => $clusters['prioritas_1']['items'],
            'prioritas_2' => $clusters['prioritas_2']['items'],
            'prioritas_3' => $clusters['prioritas_3']['items'],
            'metode' => [
                'nama' => 'Simple Additive Weighting (SAW) / Weighted Scoring Multi-Kriteria',
                'kriteria' => [
                    [
                        'kode' => 'C1',
                        'nama' => 'Persentase Kesenjangan Non-SHM',
                        'bobot' => '40%',
                        'bobot_persen' => 40,
                        'sifat' => 'Benefit (Semakin tinggi belum sertifikat, semakin prioritas intervensi)',
                        'deskripsi' => 'Proporsi KK yang belum bersertifikat terhadap total KK penempatan kawasan.',
                    ],
                    [
                        'kode' => 'C2',
                        'nama' => 'Volume KK Terdampak (KK Non-SHM)',
                        'bobot' => '35%',
                        'bobot_persen' => 35,
                        'sifat' => 'Benefit (Semakin banyak KK terdampak, semakin mendesak)',
                        'deskripsi' => 'Jumlah absolut kepala keluarga nyata yang membutuhkan kepastian hukum hak milik.',
                    ],
                    [
                        'kode' => 'C3',
                        'nama' => 'Luas Lahan Belum SHM (Hektar)',
                        'bobot' => '25%',
                        'bobot_persen' => 25,
                        'sifat' => 'Benefit (Semakin luas lahan, semakin besar kebutuhan pengukuran spasial BPN)',
                        'deskripsi' => 'Total luasan fisik tanah pekarangan dan usaha yang belum terbit sertifikat.',
                    ],
                ],
                'rumus' => 'Skor Urgensi = ((% Non-SHM / 100) × 40) + ((KK Non-SHM / Max KK Non-SHM) × 35) + ((Luas Non-SHM / Max Luas Non-SHM) × 25)',
                'ambang_batas' => [
                    'prioritas_1' => 'Skor Urgensi ≥ 55.00 atau Kesenjangan Non-SHM ≥ 50%',
                    'prioritas_2' => 'Skor Urgensi 30.00 s/d 54.99',
                    'prioritas_3' => 'Skor Urgensi < 30.00',
                ],
            ],
            'summary' => [
                'total_urgensi_tinggi' => $clusters['prioritas_1']['total_kawasan'],
                'total_urgensi_sedang' => $clusters['prioritas_2']['total_kawasan'],
                'total_urgensi_rendah' => $clusters['prioritas_3']['total_kawasan'],
                'kabupaten_paling_kritis' => $palingKritis,
            ],
        ];
    }
}
