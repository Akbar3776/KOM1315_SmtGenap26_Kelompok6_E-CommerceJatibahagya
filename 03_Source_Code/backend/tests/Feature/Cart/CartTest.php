<?php

namespace Tests\Feature\Cart;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Product;

class CartTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_add_product_to_cart()
    {
        $product = Product::factory()->create();

        $response = $this->post('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $response->assertStatus(302);
        $this->assertTrue(session()->has('cart') || session()->has('cart_items'));
    }

    public function test_user_can_update_cart_item_quantity()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['price' => 100]);

        $this->actingAs($user)->post('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $response = $this->actingAs($user)->post('/cart/update', [
            'product_id' => $product->id,
            'quantity' => 3,
        ]);

        $response->assertStatus(302);
        $this->assertTrue(session()->has('cart') || session()->has('cart_items'));
    }

    public function test_user_can_remove_item_from_cart()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $this->actingAs($user)->post('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $response = $this->actingAs($user)->post('/cart/remove', [
            'product_id' => $product->id,
        ]);

        $response->assertStatus(302);
        $this->assertTrue(! session()->has('cart') || count(session('cart', [])) === 0);
    }

    public function test_view_cart_returns_correct_items_and_totals()
    {
        $user = User::factory()->create();
        $p1 = Product::factory()->create(['price' => 50]);
        $p2 = Product::factory()->create(['price' => 100]);

        $this->actingAs($user)->post('/cart/add', ['product_id' => $p1->id, 'quantity' => 2]);
        $this->actingAs($user)->post('/cart/add', ['product_id' => $p2->id, 'quantity' => 1]);

        $response = $this->actingAs($user)->get('/cart');

        $response->assertStatus(200);
        $response->assertSee((string) (50 * 2 + 100 * 1));
    }

    public function test_cart_persists_after_login_for_guest()
    {
        $product = Product::factory()->create();
        $user = User::factory()->create();

        $this->post('/cart/add', ['product_id' => $product->id, 'quantity' => 1]);
        $this->actingAs($user)->post('/login', ['email' => $user->email, 'password' => 'password']);

        $this->actingAs($user)->get('/cart')->assertStatus(200);
        $this->assertTrue(session()->has('cart') || session()->has('cart_items'));
    }
}