<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Mail\SendOTP;
use App\Models\User;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_and_receives_otp_email()
    {
        Mail::fake();

        $payload = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'phone' => '081234567890',
        ];

        $response = $this->post('/register', $payload);

        $response->assertRedirect(route('verify'));

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'is_verified' => false,
        ]);

        $user = User::where('email', 'test@example.com')->first();
        $this->assertNotNull($user->otp);
        $this->assertNotNull($user->otp_expires_at);

        Mail::assertQueued(SendOTP::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });
    }

    public function test_registration_validation_errors_returned_for_missing_fields()
    {
        $response = $this->post('/register', []);

        $response->assertSessionHasErrors(['name', 'email', 'password']);
    }

    public function test_cannot_register_with_existing_email()
    {
        User::factory()->create(['email' => 'exists@example.com']);

        $payload = [
            'name' => 'New',
            'email' => 'exists@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'phone' => '081111111111',
        ];

        $response = $this->post('/register', $payload);

        $response->assertSessionHasErrors(['email']);
    }

    public function test_phone_unique_constraint_on_register()
    {
        User::factory()->create(['phone' => '081222222222']);

        $payload = [
            'name' => 'New',
            'email' => 'new@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'phone' => '081222222222',
        ];

        $response = $this->post('/register', $payload);

        $response->assertSessionHasErrors(['phone']);
    }

    public function test_otp_expires_after_configured_time()
    {
        $user = User::factory()->create([
            'otp' => '123456',
            'otp_expires_at' => now()->subMinutes(61),
            'is_verified' => false,
        ]);

        $response = $this->post(route('verify-post'), [
            'email' => $user->email,
            'otp' => '123456',
        ]);

        $response->assertSessionHasErrors();
        $this->assertFalse($user->fresh()->is_verified);
    }

    public function test_resend_otp_generates_new_otp_and_sends_email()
    {
        Mail::fake();

        $user = User::factory()->create([
            'is_verified' => false,
            'phone' => '081333333333',
        ]);

        $response = $this->post(route('resend.otp'), ['email' => $user->email]);

        $response->assertRedirect();
        $user = $user->fresh();
        $this->assertNotNull($user->otp);
        Mail::assertQueued(SendOTP::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });
    }

    public function test_unverified_user_cannot_login()
    {
        $user = User::factory()->create([
            'email' => 'notverified@example.com',
            'password' => bcrypt('secret123'),
            'is_verified' => false,
        ]);

        $response = $this->post('/login', [
            'email' => 'notverified@example.com',
            'password' => 'secret123',
        ]);

        $response->assertRedirect(); // expects redirect back / to login with errors
        $this->assertGuest();
    }

    public function test_password_is_hashed_on_registration()
    {
        $payload = [
            'name' => 'HashTest',
            'email' => 'hash@example.com',
            'password' => 'plainpassword',
            'password_confirmation' => 'plainpassword',
            'phone' => '081444444444',
        ];

        $this->post('/register', $payload);

        $user = User::where('email', 'hash@example.com')->first();
        $this->assertNotEquals('plainpassword', $user->password);
        $this->assertTrue(Hash::check('plainpassword', $user->password));
    }
}