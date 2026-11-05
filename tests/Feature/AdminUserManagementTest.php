<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminUserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_profile_page_with_users_list(): void
    {
        $admin = User::factory()->create(['name' => 'Admin Boss']);
        $otherUser = User::factory()->create(['name' => 'Autre Admin']);

        $response = $this
            ->actingAs($admin)
            ->get(route('profile.edit'));

        $response->assertOk();
        $response->assertSee('Admin Boss');
        $response->assertSee('Autre Admin');
    }

    public function test_admin_can_create_new_administrator(): void
    {
        $admin = User::factory()->create();

        $response = $this
            ->actingAs($admin)
            ->post(route('admin.users.store'), [
                'name' => 'Nouvel Admin',
                'email' => 'nouveau@4kbrothers.ma',
                'password' => 'password123',
                'password_confirmation' => 'password123',
            ]);

        $response->assertRedirect(route('profile.edit'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'name' => 'Nouvel Admin',
            'email' => 'nouveau@4kbrothers.ma',
        ]);
    }

    public function test_admin_can_update_another_administrator_details(): void
    {
        $admin = User::factory()->create();
        $targetUser = User::factory()->create(['name' => 'Old Name', 'email' => 'old@4kbrothers.ma']);

        $response = $this
            ->actingAs($admin)
            ->put(route('admin.users.update', $targetUser), [
                'name' => 'New Name',
                'email' => 'new@4kbrothers.ma',
            ]);

        $response->assertRedirect(route('profile.edit'));
        $response->assertSessionHas('success');

        $targetUser->refresh();
        $this->assertSame('New Name', $targetUser->name);
        $this->assertSame('new@4kbrothers.ma', $targetUser->email);
    }

    public function test_admin_can_change_password_of_another_administrator(): void
    {
        $admin = User::factory()->create();
        $targetUser = User::factory()->create();

        $response = $this
            ->actingAs($admin)
            ->put(route('admin.users.password.update', $targetUser), [
                'password' => 'newsecurepassword123',
                'password_confirmation' => 'newsecurepassword123',
            ]);

        $response->assertRedirect(route('profile.edit'));
        $response->assertSessionHas('success');

        $targetUser->refresh();
        $this->assertTrue(Hash::check('newsecurepassword123', $targetUser->password));
    }

    public function test_admin_can_delete_another_administrator(): void
    {
        $admin = User::factory()->create();
        $targetUser = User::factory()->create();

        $response = $this
            ->actingAs($admin)
            ->delete(route('admin.users.destroy', $targetUser));

        $response->assertRedirect(route('profile.edit'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('users', [
            'id' => $targetUser->id,
        ]);
    }

    public function test_admin_cannot_delete_self_via_users_destroy(): void
    {
        $admin = User::factory()->create();
        User::factory()->create(); // Ensure not the last user

        $response = $this
            ->actingAs($admin)
            ->delete(route('admin.users.destroy', $admin));

        $response->assertRedirect(route('profile.edit'));
        $response->assertSessionHas('error');

        $this->assertDatabaseHas('users', [
            'id' => $admin->id,
        ]);
    }

    public function test_cannot_delete_last_remaining_administrator(): void
    {
        $admin = User::factory()->create();

        // Simulate trying to delete when only 1 admin exists
        $response = $this
            ->actingAs($admin)
            ->delete(route('admin.users.destroy', $admin));

        $response->assertRedirect(route('profile.edit'));
        $response->assertSessionHas('error');

        $this->assertDatabaseHas('users', [
            'id' => $admin->id,
        ]);
    }
}
