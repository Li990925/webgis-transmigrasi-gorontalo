<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\KawasanTransmigrasi;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Reader\CSV\Reader as CsvReader;
use OpenSpout\Reader\XLSX\Reader as XlsxReader;
use OpenSpout\Writer\CSV\Writer as CsvWriter;
use OpenSpout\Writer\XLSX\Writer as XlsxWriter;

class ExcelService
{
    /**
     * Export settlements to an XLSX file at given path.
     *
     * @param  Collection<int, KawasanTransmigrasi>|null  $settlements
     */
    public function exportToPath(string $filePath, $settlements = null, string $format = 'xlsx'): void
    {
        $writer = $format === 'csv' ? new CsvWriter : new XlsxWriter;
        $writer->openToFile($filePath);

        $headers = [
            'No',
            'Nama Kawasan',
            'Tipe',
            'Kabupaten',
            'Kecamatan',
            'Desa',
            'Tahun Penempatan',
            'Jumlah KK',
            'Jumlah Jiwa',
            'KK Bersertifikat (SHM)',
            'KK Belum SHM',
            'Persentase SHM (%)',
            'Luas Wilayah (Ha)',
            'Luas SHM (Ha)',
            'Luas Belum SHM (Ha)',
            'Status Perkembangan',
            'Skor Kemandirian',
            'Latitude',
            'Longitude',
            'Komoditas Unggulan',
            'Asal Transmigran',
            'Deskripsi',
        ];

        $writer->addRow(Row::fromValues($headers));

        $items = $settlements ?? KawasanTransmigrasi::orderBy('kabupaten')->orderBy('nama')->get();
        $no = 1;

        foreach ($items as $item) {
            $komoditas = is_array($item->komoditas_unggulan)
                ? implode(', ', $item->komoditas_unggulan)
                : ($item->komoditas_unggulan ?? '');

            $row = [
                $no++,
                $item->nama,
                $item->tipe,
                $item->kabupaten,
                $item->kecamatan,
                $item->desa,
                (int) $item->tahun_penempatan,
                (int) $item->jumlah_kk,
                (int) $item->jumlah_jiwa,
                (int) $item->kk_shm,
                (int) $item->kk_non_shm,
                (float) $item->persentase_shm,
                (float) $item->luas_ha,
                (float) $item->luas_shm_ha,
                (float) $item->luas_non_shm_ha,
                $item->status_perkembangan,
                (int) $item->skor_kemandirian,
                (float) $item->latitude,
                (float) $item->longitude,
                $komoditas,
                $item->asal_transmigran ?? '',
                $item->deskripsi ?? '',
            ];

            $writer->addRow(Row::fromValues($row));
        }

        $writer->close();
    }

    /**
     * Generate template XLSX file.
     */
    public function generateTemplate(string $filePath): void
    {
        $writer = new XlsxWriter;
        $writer->openToFile($filePath);

        $headers = [
            'Nama Kawasan',
            'Tipe',
            'Kabupaten',
            'Kecamatan',
            'Desa',
            'Tahun Penempatan',
            'Jumlah KK',
            'Jumlah Jiwa',
            'KK SHM',
            'KK Non SHM',
            'Luas Ha',
            'Luas SHM Ha',
            'Luas Non SHM Ha',
            'Status Perkembangan',
            'Skor Kemandirian',
            'Latitude',
            'Longitude',
            'Komoditas (pisahkan koma)',
            'Asal Transmigran',
            'Deskripsi',
        ];

        $writer->addRow(Row::fromValues($headers));

        $examples = [
            [
                'UPT Wonosari Baru',
                'UPT',
                'Kabupaten Boalemo',
                'Wonosari',
                'Desa Sukamakmur',
                2015,
                300,
                1200,
                210,
                90,
                1850.5,
                1295.3,
                555.2,
                'Berkembang',
                75,
                0.6650000,
                122.3800000,
                'Jagung, Padi Sawah, Kelapa Dalam',
                'Jawa Timur, TPS Boalemo',
                'Kawasan permukiman produktif sentra jagung.',
            ],
            [
                'SP Ayula Sejahtera',
                'SP',
                'Kabupaten Gorontalo',
                'Boliyohuto',
                'Desa Ayula',
                2018,
                200,
                800,
                140,
                60,
                1200.0,
                840.0,
                360.0,
                'Berkembang',
                72,
                0.6400000,
                122.6800000,
                'Cokelat, Jagung, Sapi',
                'Jawa Tengah, Lokal Gorontalo',
                'Permukiman transmigrasi mandiri dengan peternakan sapi.',
            ],
        ];

        foreach ($examples as $example) {
            $writer->addRow(Row::fromValues($example));
        }

        $writer->close();
    }

    /**
     * Import settlements from file.
     *
     * @return array{
     *     success: bool,
     *     created: int,
     *     updated: int,
     *     skipped: int,
     *     errors: list<string>
     * }
     */
    public function importFromFile(string $filePath, ?User $user = null): array
    {
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $reader = ($extension === 'csv') ? new CsvReader : new XlsxReader;

        $reader->open($filePath);

        $created = 0;
        $updated = 0;
        $skipped = 0;
        $errors = [];
        $isFirstRow = true;

        foreach ($reader->getSheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $row) {
                if ($isFirstRow) {
                    $isFirstRow = false;

                    continue; // Skip header
                }

                $cells = $row->toArray();

                // If first column looks like a number (No), adjust offset
                $offset = (is_numeric($cells[0] ?? null) && count($cells) >= 21) ? 1 : 0;

                $nama = trim((string) ($cells[$offset + 0] ?? ''));
                if (empty($nama)) {
                    $skipped++;

                    continue;
                }

                $tipe = trim((string) ($cells[$offset + 1] ?? 'UPT'));
                $kabupaten = trim((string) ($cells[$offset + 2] ?? 'Kabupaten Boalemo'));

                // RBAC restriction check for operator kabupaten
                if ($user && $user->isOperatorKabupaten() && ! $user->canManageKabupaten($kabupaten)) {
                    $errors[] = "Baris '{$nama}' dilewati: Operator hanya berwenang untuk {$user->kabupaten}.";
                    $skipped++;

                    continue;
                }

                $kecamatan = trim((string) ($cells[$offset + 3] ?? ''));
                $desa = trim((string) ($cells[$offset + 4] ?? ''));
                $tahun = (int) ($cells[$offset + 5] ?? 2010);
                $jumlahKk = (int) ($cells[$offset + 6] ?? 100);
                $jumlahJiwa = (int) ($cells[$offset + 7] ?? ($jumlahKk * 4));
                $kkShm = (int) ($cells[$offset + 8] ?? 0);
                $kkNonShm = (int) ($cells[$offset + 9] ?? max(0, $jumlahKk - $kkShm));
                $persentaseShm = $jumlahKk > 0 ? round(($kkShm / $jumlahKk) * 100, 2) : 0;

                $luasHa = (float) ($cells[$offset + 10] ?? 1000);
                $luasShmHa = (float) ($cells[$offset + 11] ?? ($luasHa * ($persentaseShm / 100)));
                $luasNonShmHa = (float) ($cells[$offset + 12] ?? max(0, $luasHa - $luasShmHa));

                $status = trim((string) ($cells[$offset + 13] ?? 'Berkembang'));
                $skor = (int) ($cells[$offset + 14] ?? 70);
                $latitude = (float) ($cells[$offset + 15] ?? 0.65);
                $longitude = (float) ($cells[$offset + 16] ?? 122.50);

                $komoditasRaw = (string) ($cells[$offset + 17] ?? '');
                $komoditas = array_values(array_filter(array_map('trim', explode(',', $komoditasRaw))));

                $asal = trim((string) ($cells[$offset + 18] ?? 'Lokal'));
                $deskripsi = trim((string) ($cells[$offset + 19] ?? ''));

                $slug = Str::slug($nama);
                $existing = KawasanTransmigrasi::where('slug', $slug)
                    ->orWhere(function ($q) use ($nama, $kabupaten) {
                        $q->where('nama', $nama)->where('kabupaten', $kabupaten);
                    })->first();

                $payload = [
                    'slug' => $slug,
                    'nama' => $nama,
                    'tipe' => $tipe,
                    'kabupaten' => $kabupaten,
                    'kecamatan' => $kecamatan,
                    'desa' => $desa,
                    'tahun_penempatan' => $tahun,
                    'jumlah_kk' => $jumlahKk,
                    'jumlah_jiwa' => $jumlahJiwa,
                    'kk_shm' => $kkShm,
                    'kk_non_shm' => $kkNonShm,
                    'persentase_shm' => $persentaseShm,
                    'luas_ha' => $luasHa,
                    'luas_shm_ha' => $luasShmHa,
                    'luas_non_shm_ha' => $luasNonShmHa,
                    'status_perkembangan' => $status,
                    'skor_kemandirian' => $skor,
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'komoditas_unggulan' => $komoditas,
                    'asal_transmigran' => $asal,
                    'deskripsi' => $deskripsi,
                ];

                if ($existing) {
                    $old = $existing->toArray();
                    $existing->update($payload);
                    $updated++;

                    AuditLog::record(
                        'IMPORT_UPDATE',
                        $existing,
                        $old,
                        $payload,
                        'Pembaruan data massal via berkas impor oleh '.($user?->name ?? 'Sistem'),
                        $user
                    );
                } else {
                    $createdKawasan = KawasanTransmigrasi::create($payload);
                    $created++;

                    AuditLog::record(
                        'IMPORT_CREATE',
                        $createdKawasan,
                        null,
                        $payload,
                        'Penambahan data massal baru via berkas impor oleh '.($user?->name ?? 'Sistem'),
                        $user
                    );
                }
            }
        }

        $reader->close();

        return [
            'success' => true,
            'created' => $created,
            'updated' => $updated,
            'skipped' => $skipped,
            'errors' => $errors,
        ];
    }
}
