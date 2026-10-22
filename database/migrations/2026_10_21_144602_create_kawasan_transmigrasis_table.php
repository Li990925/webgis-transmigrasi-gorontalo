<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('CREATE EXTENSION IF NOT EXISTS postgis;');
        }

        Schema::create('kawasan_transmigrasis', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('nama');
            $table->string('tipe'); // KTM, UPT, SP
            $table->string('kabupaten'); // Kabupaten Gorontalo, Kabupaten Boalemo, Kabupaten Pohuwato, Kabupaten Gorontalo Utara
            $table->string('kecamatan');
            $table->string('desa');
            $table->unsignedSmallInteger('tahun_penempatan');
            $table->unsignedInteger('jumlah_kk');
            $table->unsignedInteger('jumlah_jiwa');
            $table->unsignedInteger('kk_shm'); // Sudah sertifikasi hak milik
            $table->unsignedInteger('kk_non_shm'); // Belum punya hak milik sertifikasi
            $table->decimal('persentase_shm', 5, 2)->default(0);
            $table->decimal('luas_ha', 10, 2);
            $table->decimal('luas_shm_ha', 10, 2)->default(0);
            $table->decimal('luas_non_shm_ha', 10, 2)->default(0);
            $table->json('komoditas_unggulan')->nullable();
            $table->string('asal_transmigran')->nullable();
            $table->json('sarana_prasarana')->nullable();
            $table->string('status_perkembangan')->default('Berkembang');
            $table->unsignedTinyInteger('skor_kemandirian')->default(70);
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->json('polygon_geojson')->nullable();
            if (DB::getDriverName() === 'pgsql') {
                $table->geometry('geom_point', subtype: 'point', srid: 4326)->nullable();
                $table->geometry('geom_polygon', subtype: 'geometry', srid: 4326)->nullable();
                $table->spatialIndex('geom_point');
                $table->spatialIndex('geom_polygon');
            } else {
                $table->point('geom_point')->nullable();
                $table->polygon('geom_polygon')->nullable();
            }
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kawasan_transmigrasis');
    }
};
