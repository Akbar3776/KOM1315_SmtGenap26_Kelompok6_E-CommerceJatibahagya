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
        $user = User::factory()->create();

        $payload = [
            'label' => 'Home',
            'recipient_name' => 'Test User',
            'phone' => '081234567890',
            'address' => 'Jl. Contoh No.1',
            'province_id' => 1,
            'city_id' => 1,
            'postal_code' => '12345',
        ];

        $response = $this->actingAs($user)->post('/account/addresses', $payload);

        $response->assertStatus(302); // adjust if your app returns 200/JSON

        $this->assertDatabaseHas('user_addresses', [
            'user_id' => $user->id,
            'label' => 'Home',
            'recipient_name' => 'Test User',
        ]);
    }

    public function test_address_validation_update_and_delete()
    {
        $user = User::factory()->create();

        // Validation: missing required fields
        $response = $this->actingAs($user)->post('/account/addresses', []);
        $response->assertSessionHasErrors(['label', 'recipient_name', 'address']);

        // Create an address (via controller) to update/delete
        $createPayload = [
            'label' => 'Office',
            'recipient_name' => 'Office User',
            'phone' => '081234000000',
            'address' => 'Jl. Office No.2',
            'province_id' => 1,
            'city_id' => 1,
            'postal_code' => '54321',
        ];
        $this->actingAs($user)->post('/account/addresses', $createPayload);
        $address = DB::table('user_addresses')->where('user_id', $user->id)->first();
        $this->assertNotNull($address);

        // Update address
        $updatePayload = [
            'label' => 'Office Updated',
            'recipient_name' => 'Office User Updated',
            'phone' => '081234999999',
            'address' => 'Jl. Office No.22',
        ];
        $updateResponse = $this->actingAs($user)->put('/account/addresses/' . $address->id, $updatePayload);
        $updateResponse->assertStatus(302);
        $this->assertDatabaseHas('user_addresses', [
            'id' => $address->id,
            'label' => 'Office Updated',
            'recipient_name' => 'Office User Updated',
        ]);

        // Delete address
        $deleteResponse = $this->actingAs($user)->delete('/account/addresses/' . $address->id);
        $deleteResponse->assertStatus(302);
        $this->assertDatabaseMissing('user_addresses', [
            'id' => $address->id,
        ]);
    }
}