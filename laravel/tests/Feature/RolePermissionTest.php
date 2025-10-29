<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RolePermissionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test permissions and roles
        Permission::create(['name' => 'manage users']);
        Permission::create(['name' => 'manage teams']);
        $admin = Role::create(['name' => 'admin']);
        Role::create(['name' => 'coach']);

        // Give admin role the manage users permission
        $admin->givePermissionTo('manage users');
    }

    public function test_admin_can_access_protected_route(): void
    {
        // Create and authenticate user with admin role
        $adminUser = User::factory()->create(['email' => 'admin@example.com']);
        $adminUser->assignRole('admin');

        $response = $this
            ->actingAs($adminUser)
            ->get('/admin-only');

        $response->assertOk()
                ->assertSee('Hello admin');
    }

    public function test_non_admin_cannot_access_protected_route(): void
    {
        // Create and authenticate regular user
        $user = User::factory()->create(['email' => 'user@example.com']);

        $response = $this
            ->actingAs($user)
            ->get('/admin-only');

        $response->assertForbidden();
    }

    public function test_admin_has_manage_users_permission(): void
    {
        $adminUser = User::factory()->create(['email' => 'admin2@example.com']);
        $adminUser->assignRole('admin');

        $this->assertTrue($adminUser->hasPermissionTo('manage users'));
    }

    public function test_coach_role_exists(): void
    {
        $coachUser = User::factory()->create(['email' => 'coach@example.com']);
        $coachUser->assignRole('coach');

        $this->assertTrue($coachUser->hasRole('coach'));
    }

    public function test_guest_cannot_access_protected_route(): void
    {
        $response = $this->get('/admin-only');

        $response->assertStatus(302) // Redirect to login
                ->assertRedirect('/login');
    }
}