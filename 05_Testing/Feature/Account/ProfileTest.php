<?php

namespace Tests\Feature\Account;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_update_profile_information()
    {
        $user = User::factory()->create([
            'name' => 'Old Name',
            'phone' => '081000000000',
        ]);

        $this->actingAs($user)->post('/account/profile', [
            'name' => 'New Name',
            'phone' => '081999999999',
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'New Name',
            'phone' => '081999999999',
        ]);
    }

    public function test_profile_update_validation_errors_returned()
    {
        $user = User::factory()->create();

        // missing required fields
        $response = $this->actingAs($user)->post('/account/profile', [
            'name' => '',
            'phone' => '',
        ]);

        $response->assertSessionHasErrors(['name', 'phone']);
    }

    public function test_user_can_change_password_with_current_password()
    {
        $user = User::factory()->create([
            'password' => bcrypt('oldpassword'),
        ]);

        $response = $this->actingAs($user)->post('/account/password', [
            'current_password' => 'oldpassword',
            'password' => 'newsecret123',
            'password_confirmation' => 'newsecret123',
        ]);

        $response->assertStatus(302); // expects redirect after success

        $this->assertTrue(Hash::check('newsecret123', $user->fresh()->password));
    }

    public function test_change_password_requires_current_password_and_validation()
    {
        $user = User::factory()->create([
            'password' => bcrypt('oldpassword'),
        ]);

        // wrong current password
        $response = $this->actingAs($user)->post('/account/password', [
            'current_password' => 'wrongpassword',
            'password' => 'newsecret123',
            'password_confirmation' => 'newsecret123',
        ]);

        $response->assertSessionHasErrors(['current_password']);

        // missing new password confirmation
        $response2 = $this->actingAs($user)->post('/account/password', [
            'current_password' => 'oldpassword',
            'password' => 'short',
            'password_confirmation' => '',
        ]);

        $response2->assertSessionHasErrors(['password']);
    }
}