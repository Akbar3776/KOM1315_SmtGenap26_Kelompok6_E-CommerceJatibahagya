<?php

namespace Tests\Feature\Address;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class AddressTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_add_address()
    {
        $user = User::factory()->create(['is_verified' => true, 'email_verified_at' => now()]);

        $payload = [
            'province_id' => 1,
            'regency_id' => 1,
            'district_id' => 1,
            'village_id' => 1,
            'full_address' => 'Jl. Contoh No.1',
            'postal_code' => '12345',
        ];

        $response = $this->actingAs($user)->post('/account/addresses', $payload);
        $response->assertStatus(302); // adjust if your app returns 200/JSON

        $this->assertDatabaseHas('user_addresses', [
            'user_id' => $user->id,
            'full_address' => 'Jl. Contoh No.1',
        ]);
    }

    public function test_address_validation_update_and_delete()
    {
        $user = User::factory()->create(['is_verified' => true, 'email_verified_at' => now()]);

        // Validation: missing required fields
        $response = $this->actingAs($user)->post('/account/addresses', []);
        $response->assertSessionHasErrors(['province_id', 'regency_id', 'district_id', 'village_id', 'full_address']);

        // Create an address (via controller) to update/delete
        $createPayload = [
            'province_id' => 1,
            'regency_id' => 1,
            'district_id' => 1,
            'village_id' => 1,
            'full_address' => 'Jl. Office No.2',
            'postal_code' => '54321',
        ];
        $this->actingAs($user)->post('/account/addresses', $createPayload);
        $address = DB::table('user_addresses')->where('user_id', $user->id)->first();
        $this->assertNotNull($address);

        // Update address
        $updatePayload = [
            'province_id' => 1,
            'regency_id' => 1,
            'district_id' => 1,
            'village_id' => 1,
            'full_address' => 'Jl. Office No.22',
            'postal_code' => '54321',
        ];
        $updateResponse = $this->actingAs($user)->put('/account/addresses/' . $address->id, $updatePayload);
        $updateResponse->assertStatus(302);
        $this->assertDatabaseHas('user_addresses', [
            'id' => $address->id,
            'full_address' => 'Jl. Office No.22',
        ]);

        // Delete address
        $deleteResponse = $this->actingAs($user)->delete('/account/addresses/' . $address->id);
        $deleteResponse->assertStatus(302);
        $this->assertDatabaseMissing('user_addresses', [
            'id' => $address->id,
        ]);
    }
}