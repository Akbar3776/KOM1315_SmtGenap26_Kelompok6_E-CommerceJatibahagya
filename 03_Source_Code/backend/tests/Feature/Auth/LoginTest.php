<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_verified_user_can_login_and_redirects_to_orders(): void
    {
        $password = 'secret123';
        $user = User::factory()->create([
            'email' => 'verified@example.test',
            'password' => Hash::make($password),
            'is_verified' => true,
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => $password,
        ]);

        $response->assertRedirect('/orders');
        $this->assertAuthenticatedAs($user);
    }

    public function test_unverified_user_cannot_login(): void
    {
        $password = 'secret123';
        $user = User::factory()->create([
            'email' => 'unverified@example.test',
            'password' => Hash::make($password),
            'is_verified' => false,
        ]);

        $response = $this->from('/login')->post('/login', [
            'email' => $user->email,
            'password' => $password,
        ]);

        $response->assertRedirect('/login');
        $this->assertGuest();
    }

    public function test_authenticated_user_can_logout(): void
    {
        $password = 'secret123';
        $user = User::factory()->create([
            'email' => 'logout@example.test',
            'password' => Hash::make($password),
            'is_verified' => true,
        ]);

        $this->actingAs($user);

        $response = $this->post('/logout');

        $response->assertRedirect('/');
        $this->assertGuest();
    }
}