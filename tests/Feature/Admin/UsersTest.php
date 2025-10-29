<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Branch;
use App\Models\Group;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UsersTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_send_password_reset_link()
    {
        \Mail::fake();
        $this->actingAsAdmin();
        $user = User::factory()->create(['email' => 'resetme@example.com']);
        $user->assignRole('user');

        $response = $this->post(route('admin.users.sendReset', $user));
        $response->assertRedirect();

        \Mail::assertQueued(\Illuminate\Auth\Notifications\ResetPassword::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });
    }
    use RefreshDatabase;

    public function test_admin_can_update_user_roles_inline_patch()
    {
        $this->actingAsAdmin();
        $user = User::factory()->create();
        $user->assignRole('user');

        $response = $this->patch(route('admin.users.update', $user), [
            'roles' => ['admin', 'coach'],
        ]);

        $response->assertRedirect();
        $user->refresh();
        $this->assertTrue($user->hasRole('admin'));
        $this->assertTrue($user->hasRole('coach'));
        $this->assertFalse($user->hasRole('user'));
    }
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Seed roles and permissions
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'coach']);
        Role::firstOrCreate(['name' => 'user']);
    }

    private function actingAsAdmin(): User
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $this->actingAs($admin);
        return $admin;
    }

    public function test_admin_can_create_user_with_roles_and_branch_group()
    {
        $this->actingAsAdmin();
        $branch = Branch::factory()->create();
        $group = Group::factory()->create(['branch_id' => $branch->id]);

        $response = $this->post(route('admin.users.store'), [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'branch_id' => $branch->id,
            'group_id' => $group->id,
            'roles' => ['coach'],
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', [
            'email' => 'testuser@example.com',
            'branch_id' => $branch->id,
            'group_id' => $group->id,
        ]);
        $user = User::where('email', 'testuser@example.com')->first();
        $this->assertTrue($user->hasRole('coach'));
        $this->assertTrue(Hash::check('password123', $user->password));
    }

    public function test_admin_cannot_assign_group_not_in_branch()
    {
        $this->actingAsAdmin();
        $branch1 = Branch::factory()->create();
        $branch2 = Branch::factory()->create();
        $group = Group::factory()->create(['branch_id' => $branch2->id]);

        $response = $this->post(route('admin.users.store'), [
            'name' => 'Test User',
            'email' => 'testuser2@example.com',
            'branch_id' => $branch1->id,
            'group_id' => $group->id,
            'roles' => ['coach'],
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('group_id');
        $this->assertDatabaseMissing('users', [
            'email' => 'testuser2@example.com',
        ]);
    }

    public function test_admin_can_update_user_roles_and_branch_group()
    {
        $this->actingAsAdmin();
        $branch = Branch::factory()->create();
        $group = Group::factory()->create(['branch_id' => $branch->id]);
        $user = User::factory()->create();
        $user->assignRole('user');

        $response = $this->put(route('admin.users.updateFull', $user), [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'branch_id' => $branch->id,
            'group_id' => $group->id,
            'roles' => ['admin'],
            'password' => '', // keep current
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $user->refresh();
        $this->assertEquals('Updated Name', $user->name);
        $this->assertEquals('updated@example.com', $user->email);
        $this->assertEquals($branch->id, $user->branch_id);
        $this->assertEquals($group->id, $user->group_id);
        $this->assertTrue($user->hasRole('admin'));
    }
}
