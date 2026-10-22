<?php

namespace Tests\Feature;

use App\Models\KawasanTransmigrasi;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test the login page renders successfully with persona simulator.
     */
    public function test_login_page_renders_with_persona_simulator(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('Masuk ke Sistem Tata Kelola', false);
        $response->assertSee('SI-TRANSMIGRASI GORONTALO', false);
        $response->assertSee('Super Admin Provinsi', false);
        $response->assertSee('Operator Boalemo', false);
        $response->assertSee('Eksekutif / Pimpinan', false);
    }

    /**
     * Test unauthenticated access to admin panel redirects to login.
     */
    public function test_unauthenticated_access_redirects_to_login(): void
    {
        $response = $this->get('/admin/dashboard');

        $response->assertRedirect('/login');
    }

    /**
     * Test authenticated Super Admin can access admin dashboard.
     */
    public function test_super_admin_can_access_dashboard(): void
    {
        $admin = User::where('role', 'super_admin')->first();
        $this->assertNotNull($admin);

        $response = $this->actingAs($admin)->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Dashboard Eksekutif', false);
        $response->assertSee('Total Kawasan', false);
        $response->assertSee('Capaian SHM Provinsi', false);
        $response->assertSee('chart-pie-shm', false);
        $response->assertSee('chart-bar-kabupaten', false);
        $response->assertSee('chart-line-trend', false);
    }

    /**
     * Test master data kawasan index view in admin panel.
     */
    public function test_admin_can_access_kawasan_index(): void
    {
        $admin = User::where('role', 'super_admin')->first();

        $response = $this->actingAs($admin)->get('/admin/kawasan');

        $response->assertStatus(200);
        $response->assertSee('Master Data Kawasan Transmigrasi', false);
        $response->assertSee('Tambah Kawasan', false);
        $response->assertSee('Impor Excel', false);
        $response->assertSee('Ekspor XLSX', false);
    }

    /**
     * Test create kawasan form renders with Leaflet map picker.
     */
    public function test_admin_can_access_create_kawasan_form(): void
    {
        $admin = User::where('role', 'super_admin')->first();

        $response = $this->actingAs($admin)->get('/admin/kawasan/create');

        $response->assertStatus(200);
        $response->assertSee('Formulir Pendaftaran Kawasan Baru', false);
        $response->assertSee('picker-map', false);
        $response->assertSee('input_latitude', false);
        $response->assertSee('input_longitude', false);
    }

    /**
     * Test spatial canvas geometry digitizer view.
     */
    public function test_can_access_spatial_geometry_editor(): void
    {
        $admin = User::where('role', 'super_admin')->first();
        $kawasan = KawasanTransmigrasi::first();
        $this->assertNotNull($kawasan);

        $response = $this->actingAs($admin)->get("/admin/kawasan/{$kawasan->id}/spasial");

        $response->assertStatus(200);
        $response->assertSee('Editor Spasial Kanvas Geoman', false);
        $response->assertSee('spatial-canvas', false);
        $response->assertSee('btn-save-geometry', false);
        $response->assertSee($kawasan->nama, false);
    }

    /**
     * Test spatial priority clustering view.
     */
    public function test_can_access_klasterisasi_view(): void
    {
        $admin = User::where('role', 'super_admin')->first();

        $response = $this->actingAs($admin)->get('/admin/klasterisasi');

        $response->assertStatus(200);
        $response->assertSee('Matriks Prioritas Intervensi Sertifikasi ATR/BPN', false);
        $response->assertSee('Simple Additive Weighting (SAW)', false);
        $response->assertSee('Bobot 40%', false);
        $response->assertSee('Bobot 35%', false);
        $response->assertSee('Bobot 25%', false);
        $response->assertSee('PRIORITAS I', false);
        $response->assertSee('PRIORITAS II', false);
        $response->assertSee('PRIORITAS III', false);
    }

    /**
     * Test audit trail logs view in admin panel.
     */
    public function test_can_access_audit_logs_view(): void
    {
        $admin = User::where('role', 'super_admin')->first();

        $response = $this->actingAs($admin)->get('/admin/audit-logs');

        $response->assertStatus(200);
        $response->assertSee('Log Aktivitas Sistem', false);
        $response->assertSee('Waktu (WITA)', false);
        $response->assertSee('Tipe Aksi', false);
    }

    /**
     * Test Eksekutif (read-only) cannot access create or edit forms.
     */
    public function test_eksekutif_is_blocked_from_create_form(): void
    {
        $eksekutif = User::where('role', 'eksekutif')->first();
        $this->assertNotNull($eksekutif);

        $response = $this->actingAs($eksekutif)->get('/admin/kawasan/create');

        $response->assertStatus(403);
    }

    /**
     * Test 1-click persona switch in web session.
     */
    public function test_web_persona_switcher(): void
    {
        $response = $this->post('/switch-persona', [
            'role' => 'operator_boalemo',
        ]);

        $response->assertRedirect('/admin/dashboard');
        $this->assertAuthenticated();

        /** @var User $currentUser */
        $currentUser = auth()->user();
        $this->assertEquals('operator_kabupaten', $currentUser->role);
        $this->assertEquals('Kabupaten Boalemo', $currentUser->kabupaten);
    }

    /**
     * Test web logout.
     */
    public function test_web_logout(): void
    {
        $admin = User::where('role', 'super_admin')->first();

        $response = $this->actingAs($admin)->post('/logout');

        $response->assertRedirect('/');
        $this->assertGuest();
    }

    /**
     * Test public portal header shows secure login button for guests and no insecure persona switcher.
     */
    public function test_guest_public_portal_header_is_secure(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Masuk Aparatur', false);
        $response->assertDontSee('id="btn-persona-switcher"', false);
        $response->assertDontSee('id="persona-modal"', false);
        $response->assertDontSee('Masuk 1-Klik', false);
    }

    /**
     * Test public portal header shows admin panel shortcut and logout for authenticated users.
     */
    public function test_authenticated_public_portal_header(): void
    {
        $admin = User::where('role', 'super_admin')->first();

        $response = $this->actingAs($admin)->get('/');

        $response->assertStatus(200);
        $response->assertSee('Panel Admin', false);
        $response->assertSee('Keluar (Logout)', false);
        $response->assertSee($admin->name, false);
        $response->assertDontSee('id="btn-persona-switcher"', false);
        $response->assertDontSee('id="persona-modal"', false);
    }
}
