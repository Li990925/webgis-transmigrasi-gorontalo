<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Unauthenticated user cannot access user management.
     */
    public function test_unauthenticated_user_cannot_access_user_management(): void
    {
        $response = $this->get('/admin/users');

        $response->assertRedirect('/login');
    }

    /**
     * Operator and Eksekutif cannot access user management (RBAC 403).
     */
    public function test_non_super_admin_cannot_access_user_management(): void
    {
        $operator = User::where('role', 'operator_kabupaten')->first();
        $eksekutif = User::where('role', 'eksekutif')->first();

        $this->assertNotNull($operator);
        $this->assertNotNull($eksekutif);

        // Operator
        $resOperator = $this->actingAs($operator)->get('/admin/users');
        $resOperator->assertStatus(403);

        // Eksekutif
        $resEksekutif = $this->actingAs($eksekutif)->get('/admin/users');
        $resEksekutif->assertStatus(403);
    }

    /**
     * Super Admin can view user management index page.
     */
    public function test_super_admin_can_view_user_management_index(): void
    {
        $admin = User::where('role', 'super_admin')->first();
        $this->assertNotNull($admin);

        $response = $this->actingAs($admin)->get('/admin/users');

        $response->assertStatus(200);
        $response->assertSee('Daftar Akun Aparatur', false);
        $response->assertSee('Tambah Aparatur Baru', false);
        $response->assertSee($admin->email, false);
    }

    /**
     * Super Admin can view user create form.
     */
    public function test_super_admin_can_view_create_form(): void
    {
        $admin = User::where('role', 'super_admin')->first();

        $response = $this->actingAs($admin)->get('/admin/users/create');

        $response->assertStatus(200);
        $response->assertSee('Pendaftaran Akun Aparatur Baru', false);
        $response->assertSee('Kabupaten Boalemo', false);
        $response->assertSee('Kabupaten Pohuwato', false);
    }

    /**
     * Super Admin can create a new operator user with audit logging.
     */
    public function test_super_admin_can_create_new_operator(): void
    {
        $admin = User::where('role', 'super_admin')->first();
        $email = 'bambang.'.uniqid().'@gorontalo.go.id';

        $payload = [
            'name' => 'Bambang Sudirman, S.Kom',
            'email' => $email,
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'operator_kabupaten',
            'kabupaten' => 'Kabupaten Boalemo',
            'jabatan' => 'Staff Pemetaan SIG Boalemo',
        ];

        $response = $this->actingAs($admin)->post('/admin/users', $payload);

        $response->assertRedirect('/admin/users');
        $response->assertSessionHas('success');

        $created = User::where('email', $email)->first();
        $this->assertNotNull($created);
        $this->assertEquals('operator_kabupaten', $created->role);
        $this->assertEquals('Kabupaten Boalemo', $created->kabupaten);
        $this->assertTrue(Hash::check('password123', $created->password));

        // Verify audit log entry
        $this->assertDatabaseHas('audit_logs', [
            'action' => 'CREATE_USER',
            'user_id' => $admin->id,
        ]);
    }

    /**
     * Super Admin can update an existing user.
     */
    public function test_super_admin_can_update_user(): void
    {
        $admin = User::where('role', 'super_admin')->first();

        $user = User::factory()->create([
            'role' => 'operator_kabupaten',
            'kabupaten' => 'Kabupaten Boalemo',
            'jabatan' => 'Staf Muda',
        ]);

        $updatePayload = [
            'name' => 'Nama Baru Diperbarui',
            'email' => $user->email,
            'role' => 'operator_kabupaten',
            'kabupaten' => 'Kabupaten Pohuwato',
            'jabatan' => 'Koordinator Lapangan',
            'password' => '',
            'password_confirmation' => '',
        ];

        $response = $this->actingAs($admin)->put("/admin/users/{$user->id}", $updatePayload);

        $response->assertRedirect('/admin/users');
        $response->assertSessionHas('success');

        $user->refresh();
        $this->assertEquals('Nama Baru Diperbarui', $user->name);
        $this->assertEquals('Kabupaten Pohuwato', $user->kabupaten);
        $this->assertEquals('Koordinator Lapangan', $user->jabatan);

        // Verify audit log entry
        $this->assertDatabaseHas('audit_logs', [
            'action' => 'UPDATE_USER',
            'user_id' => $admin->id,
        ]);
    }

    /**
     * Super Admin cannot downgrade their own role while logged in.
     */
    public function test_super_admin_cannot_downgrade_own_role(): void
    {
        $admin = User::where('role', 'super_admin')->first();

        $response = $this->actingAs($admin)->put("/admin/users/{$admin->id}", [
            'name' => $admin->name,
            'email' => $admin->email,
            'role' => 'operator_kabupaten',
            'kabupaten' => 'Kabupaten Boalemo',
            'jabatan' => $admin->jabatan,
        ]);

        $response->assertSessionHas('error');
        $admin->refresh();
        $this->assertEquals('super_admin', $admin->role);
    }

    /**
     * Super Admin can delete another user.
     */
    public function test_super_admin_can_delete_other_user(): void
    {
        $admin = User::where('role', 'super_admin')->first();

        $userToDelete = User::factory()->create([
            'email' => 'todelete@gorontalo.go.id',
            'role' => 'operator_kabupaten',
        ]);

        $response = $this->actingAs($admin)->delete("/admin/users/{$userToDelete->id}");

        $response->assertRedirect('/admin/users');
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('users', ['id' => $userToDelete->id]);

        // Verify audit log entry
        $this->assertDatabaseHas('audit_logs', [
            'action' => 'DELETE_USER',
            'user_id' => $admin->id,
        ]);
    }

    /**
     * Super Admin cannot delete themselves while logged in.
     */
    public function test_super_admin_cannot_delete_themselves(): void
    {
        $admin = User::where('role', 'super_admin')->first();

        $response = $this->actingAs($admin)->delete("/admin/users/{$admin->id}");

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }
}
