<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('kawasan_transmigrasis', function (Blueprint $table) {
            // 1. Rincian Alokasi Lahan (LU I, LU II & Pekarangan)
            $table->decimal('luas_lahan_pekarangan_ha', 10, 2)->default(0)->after('luas_non_shm_ha');
            $table->decimal('luas_lahan_usaha_1_ha', 10, 2)->default(0)->after('luas_lahan_pekarangan_ha');
            $table->decimal('luas_lahan_usaha_2_ha', 10, 2)->default(0)->after('luas_lahan_usaha_1_ha');

            // 2. Target & Realisasi Bidang SHM
            $table->unsignedInteger('target_bidang_shm')->default(0)->after('kk_non_shm');
            $table->unsignedInteger('bidang_shm_terbit')->default(0)->after('target_bidang_shm');
            $table->unsignedInteger('bidang_shm_proses')->default(0)->after('bidang_shm_terbit');

            // 3. Tahapan Sertifikasi ATR/BPN
            $table->string('tahapan_shm')->default('Tahap Pengukuran & Sidang PBT')->after('bidang_shm_proses');

            // 4. Program Pemberdayaan Ekonomi Transmigrasi
            $table->json('program_pemberdayaan')->nullable()->after('komoditas_unggulan');
            $table->unsignedInteger('jumlah_kegiatan_pemberdayaan')->default(0)->after('program_pemberdayaan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kawasan_transmigrasis', function (Blueprint $table) {
            $table->dropColumn([
                'luas_lahan_pekarangan_ha',
                'luas_lahan_usaha_1_ha',
                'luas_lahan_usaha_2_ha',
                'target_bidang_shm',
                'bidang_shm_terbit',
                'bidang_shm_proses',
                'tahapan_shm',
                'program_pemberdayaan',
                'jumlah_kegiatan_pemberdayaan',
            ]);
        });
    }
};
