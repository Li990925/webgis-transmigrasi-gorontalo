<?php

namespace App\Models;

use Database\Factories\KawasanTransmigrasiFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\DB;

class KawasanTransmigrasi extends Model
{
    /** @use HasFactory<KawasanTransmigrasiFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'slug',
        'nama',
        'tipe',
        'kabupaten',
        'kecamatan',
        'desa',
        'tahun_penempatan',
        'jumlah_kk',
        'jumlah_jiwa',
        'kk_shm',
        'kk_non_shm',
        'persentase_shm',
        'luas_ha',
        'luas_shm_ha',
        'luas_non_shm_ha',
        'komoditas_unggulan',
        'asal_transmigran',
        'sarana_prasarana',
        'status_perkembangan',
        'skor_kemandirian',
        'latitude',
        'longitude',
        'polygon_geojson',
        'deskripsi',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tahun_penempatan' => 'integer',
            'jumlah_kk' => 'integer',
            'jumlah_jiwa' => 'integer',
            'kk_shm' => 'integer',
            'kk_non_shm' => 'integer',
            'persentase_shm' => 'float',
            'luas_ha' => 'float',
            'luas_shm_ha' => 'float',
            'luas_non_shm_ha' => 'float',
            'komoditas_unggulan' => 'array',
            'sarana_prasarana' => 'array',
            'skor_kemandirian' => 'integer',
            'latitude' => 'float',
            'longitude' => 'float',
            'polygon_geojson' => 'array',
        ];
    }

    /**
     * Boot the model.
     */
    protected static function booted(): void
    {
        static::saving(function (self $model) {
            if ($model->jumlah_kk > 0) {
                $model->persentase_shm = min(100.0, max(0.0, round(($model->kk_shm / $model->jumlah_kk) * 100, 2)));
            }
        });

        static::saved(function (self $model) {
            $model->syncSpatialGeometries();
        });
    }

    /**
     * Synchronize native spatial geom_point and geom_polygon.
     */
    public function syncSpatialGeometries(): void
    {
        $driver = DB::getDriverName();

        if ($this->latitude && $this->longitude) {
            try {
                if ($driver === 'pgsql') {
                    DB::statement(
                        'UPDATE kawasan_transmigrasis SET geom_point = ST_SetSRID(ST_MakePoint(?, ?), 4326) WHERE id = ?',
                        [$this->longitude, $this->latitude, $this->id]
                    );
                } elseif ($driver === 'mysql') {
                    DB::statement(
                        'UPDATE kawasan_transmigrasis SET geom_point = ST_SRID(Point(?, ?), 4326) WHERE id = ?',
                        [$this->longitude, $this->latitude, $this->id]
                    );
                }
            } catch (\Throwable $e) {
                // Fallback / ignore if spatial function not supported
            }
        }

        if (! empty($this->polygon_geojson) && is_array($this->polygon_geojson)) {
            $coords = $this->polygon_geojson;
            if (count($coords) >= 3) {
                // Ensure polygon is closed
                if ($coords[0] !== $coords[count($coords) - 1]) {
                    $coords[] = $coords[0];
                }
                $points = [];
                foreach ($coords as $pt) {
                    if (is_array($pt) && count($pt) >= 2) {
                        // Leaflet coordinate is [lat, lng], in WKT it is 'lng lat'
                        $lat = (float) $pt[0];
                        $lng = (float) $pt[1];
                        $points[] = "{$lng} {$lat}";
                    }
                }
                if (count($points) >= 4) {
                    $wkt = 'POLYGON(('.implode(', ', $points).'))';
                    try {
                        if ($driver === 'pgsql') {
                            DB::statement(
                                'UPDATE kawasan_transmigrasis SET geom_polygon = ST_SetSRID(ST_GeomFromText(?, 4326), 4326) WHERE id = ?',
                                [$wkt, $this->id]
                            );
                        } elseif ($driver === 'mysql') {
                            DB::statement(
                                'UPDATE kawasan_transmigrasis SET geom_polygon = ST_SRID(ST_GeomFromText(?), 4326) WHERE id = ?',
                                [$wkt, $this->id]
                            );
                        }
                    } catch (\Throwable $e) {
                        // Tolerate invalid manual geometry
                    }
                }
            }
        }
    }

    /**
     * Calculate native area in hectares using PostGIS ST_Area over geography, or model fallback.
     */
    public function calculateNativeAreaHa(): ?float
    {
        if (DB::getDriverName() === 'pgsql') {
            $res = DB::selectOne(
                'SELECT (ST_Area(geom_polygon::geography) / 10000.0) as area_ha FROM kawasan_transmigrasis WHERE id = ? AND geom_polygon IS NOT NULL',
                [$this->id]
            );

            return $res && $res->area_ha !== null ? round((float) $res->area_ha, 2) : null;
        }

        return $this->luas_ha !== null ? round((float) $this->luas_ha, 2) : null;
    }

    /**
     * Scope query to find settlements within radius in meters.
     */
    public function scopeWithinDistance($query, float $lat, float $lng, float $meters)
    {
        if (DB::getDriverName() === 'pgsql') {
            return $query->whereRaw(
                'ST_DWithin(geom_point::geography, ST_SetSRID(ST_MakePoint(?, ?), 4326)::geography, ?)',
                [$lng, $lat, $meters]
            );
        }

        // Standard Haversine formula for MySQL, MariaDB, and SQLite
        return $query->whereRaw(
            '(6371000 * acos(least(1.0, greatest(-1.0, cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))))) <= ?',
            [$lat, $lng, $lat, $meters]
        );
    }

    /**
     * Audit log relationship.
     */
    public function auditLogs(): MorphMany
    {
        return $this->morphMany(AuditLog::class, 'auditable');
    }
}
