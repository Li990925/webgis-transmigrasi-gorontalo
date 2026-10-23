<?php

namespace Tests\Feature;

use App\Models\KawasanTransmigrasi;
use App\Models\User;
use Tests\TestCase;

class RBACAndSecurityTest extends TestCase
{
    /**
     * Test 1-click persona switcher endpoint.
     */
    public function test_can_switch_persona_roles(): void
    {
        $response = $this->postJson('/api/auth/switch-role', [
            'role' => 'super_admin',
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
        $response->assertJsonPath('user.role', 'super_admin');

        $status = $this->getJson('/api/auth/status');
        $status->assertStatus(200);
        $status->assertJsonPath('authenticated', true);
        $status->assertJsonPath('permissions.is_super_admin', true);
        $status->assertJsonPath('permissions.can_create', true);
    }

    /**
     * Test operator kabupaten can update settlement in their assigned kabupaten.
     */
    public function test_operator_can_update_own_kabupaten(): void
    {
        $operatorBoalemo = User::where('role', 'operator_kabupaten')
            ->where('kabupaten', 'Kabupaten Boalemo')
            ->first();
        $this->assertNotNull($operatorBoalemo);

        $kawasanBoalemo = KawasanTransmigrasi::where('kabupaten', 'Kabupaten Boalemo')->first();
        $this->assertNotNull($kawasanBoalemo);

        $response = $this->actingAs($operatorBoalemo)
            ->putJson("/kawasan/{$kawasanBoalemo->id}", [
                'nama' => $kawasanBoalemo->nama,
                'tipe' => $kawasanBoalemo->tipe,
                'kabupaten' => $kawasanBoalemo->kabupaten,
                'kecamatan' => $kawasanBoalemo->kecamatan,
                'desa' => $kawasanBoalemo->desa,
                'tahun_penempatan' => $kawasanBoalemo->tahun_penempatan,
                'jumlah_kk' => $kawasanBoalemo->jumlah_kk,
                'jumlah_jiwa' => $kawasanBoalemo->jumlah_jiwa,
                'kk_shm' => $kawasanBoalemo->kk_shm + 5,
                'kk_non_shm' => max(0, $kawasanBoalemo->kk_non_shm - 5),
                'luas_ha' => $kawasanBoalemo->luas_ha,
                'latitude' => $kawasanBoalemo->latitude,
                'longitude' => $kawasanBoalemo->longitude,
            ]);

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);

        // Verify audit log was recorded
        $this->assertDatabaseHas('audit_logs', [
            'action' => 'UPDATE',
            'user_id' => $operatorBoalemo->id,
            'kabupaten' => 'Kabupaten Boalemo',
        ]);
    }

    /**
     * Test operator kabupaten cannot update settlement in a different kabupaten (RBAC isolation).
     */
    public function test_operator_cannot_update_other_kabupaten(): void
    {
        $operatorBoalemo = User::where('role', 'operator_kabupaten')
            ->where('kabupaten', 'Kabupaten Boalemo')
            ->first();
        $this->assertNotNull($operatorBoalemo);

        $kawasanPohuwato = KawasanTransmigrasi::where('kabupaten', 'Kabupaten Pohuwato')->first();
        $this->assertNotNull($kawasanPohuwato);

        $response = $this->actingAs($operatorBoalemo)
            ->putJson("/kawasan/{$kawasanPohuwato->id}", [
                'nama' => 'Illegal Update Test',
                'tipe' => 'UPT',
                'kabupaten' => 'Kabupaten Pohuwato',
                'kecamatan' => $kawasanPohuwato->kecamatan,
                'desa' => $kawasanPohuwato->desa,
                'tahun_penempatan' => 2015,
                'jumlah_kk' => 200,
                'jumlah_jiwa' => 800,
                'kk_shm' => 100,
                'kk_non_shm' => 100,
                'luas_ha' => 1000,
                'latitude' => 0.5,
                'longitude' => 121.5,
            ]);

        $response->assertStatus(403);
        $response->assertJsonPath('success', false);
    }

    /**
     * Test eksekutif has read-only access and cannot create or delete data.
     */
    public function test_eksekutif_is_read_only(): void
    {
        $eksekutif = User::where('role', 'eksekutif')->first();
        $this->assertNotNull($eksekutif);

        $kawasan = KawasanTransmigrasi::first();

        // Attempt deletion
        $delResponse = $this->actingAs($eksekutif)
            ->deleteJson("/kawasan/{$kawasan->id}");

        $delResponse->assertStatus(403);

        // Can access analytics
        $analytics = $this->actingAs($eksekutif)
            ->getJson('/api/analytics/dashboard');

        $analytics->assertStatus(200);
        $analytics->assertJsonPath('success', true);
    }

    /**
     * Test audit trail logs endpoint returns structured entries.
     */
    public function test_audit_logs_can_be_retrieved(): void
    {
        $response = $this->getJson('/api/audit-logs');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'action',
                    'user_name',
                    'user_role',
                    'created_at',
                ],
            ],
        ]);
    }

    /**
     * Test application responses include recommended HTTP security headers.
     */
    public function test_responses_include_security_headers(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('X-XSS-Protection', '1; mode=block');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
    }
}
