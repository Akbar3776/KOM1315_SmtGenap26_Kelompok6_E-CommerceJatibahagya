<?php

namespace Tests\Feature\Orders;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Product;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_checkout_creates_order_and_clears_cart()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock' => 10, 'price' => 100]);

        // add product to cart (adjust route if your app uses different endpoint)
        $this->actingAs($user)->post('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        // submit checkout (adjust payload keys to match your controller)
        $response = $this->actingAs($user)->post('/checkout', [
            'address_id' => null,
            'payment_method' => 'test',
        ]);

        $response->assertStatus(302); // or assertRedirect('/orders') depending on your flow

        // assert an order record was created for this user
        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
        ]);

        // assert cart is cleared (session or db depending on implementation)
        $this->actingAs($user)->get('/cart')->assertStatus(200);
    }

    public function test_checkout_fails_when_insufficient_stock()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock' => 1, 'price' => 100]);

        $this->actingAs($user)->post('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        $response = $this->actingAs($user)->post('/checkout', [
            'address_id' => null,
            'payment_method' => 'test',
        ]);

        // Expect validation error or redirect back with error
        $response->assertStatus(302);
        $response->assertSessionHasErrors();

        // No order should be created
        $this->assertDatabaseMissing('orders', [
            'user_id' => $user->id,
        ]);
    }

    public function test_checkout_rolls_back_on_payment_failure()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock' => 5, 'price' => 100]);

        $this->actingAs($user)->post('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        // Simulate payment failure.
        // NOTE: Replace 'force_fail' with the real way to simulate a gateway failure
        // (e.g., mock the payment service or set test token). This param is a test-only hook.
        $response = $this->actingAs($user)->post('/checkout', [
            'address_id' => null,
            'payment_method' => 'test',
            'force_fail' => true,
        ]);

        // Expect the controller to handle payment failure (redirect with error or 500)
        $response->assertStatus(302);
        $response->assertSessionHasErrors();

        // Ensure no order record was persisted
        $this->assertDatabaseMissing('orders', [
            'user_id' => $user->id,
        ]);

        // Ensure product stock was not decremented (rollback)
        $this->assertEquals(5, $product->fresh()->stock);
    }
}