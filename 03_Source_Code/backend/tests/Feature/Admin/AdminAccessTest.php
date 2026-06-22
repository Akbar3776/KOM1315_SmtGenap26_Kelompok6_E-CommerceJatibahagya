<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_non_admin_cannot_access_admin_panel()
    {
        $user = User::factory()->create(['role' => 'customer']);

        $response = $this->actingAs($user)->get('/admin');

        // Expect not OK — app may redirect or return 403 depending on middleware
        $this->assertNotEquals(200, $response->getStatusCode());
    }

    public function test_admin_can_access_admin_panel_and_user_resource()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $someUser = User::factory()->create(['name' => 'Target User']);

        // Admin dashboard (Filament or custom)
        $response = $this->actingAs($admin)->get('/admin');
        $response->assertStatus(200);

        // Access user resource index (adjust path if your admin resource path differs)
        // Filament commonly exposes resources under /admin/resources/{resource}
        $usersIndex = $this->actingAs($admin)->get('/admin/resources/users');
        $usersIndex->assertStatus(200);

        // Optionally assert the page contains the user's name
        $usersIndex->assertSee('Target User');
    }
}