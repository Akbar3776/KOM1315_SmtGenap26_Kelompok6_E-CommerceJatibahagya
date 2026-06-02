<?php

namespace Tests\Feature\Social;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\Product;

class WishlistReviewQuestionTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_toggle_wishlist_and_retrieve_items()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $this->actingAs($user)->post('/wishlist/toggle', ['product_id' => $product->id]);
        $this->assertDatabaseHas('wishlists', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        $this->actingAs($user)->post('/wishlist/toggle', ['product_id' => $product->id]);
        $this->assertDatabaseMissing('wishlists', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        $response = $this->actingAs($user)->get('/wishlist');
        $response->assertStatus(200);
    }

    public function test_user_can_submit_review_and_validation_applies()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        // Validation error: missing fields
        $response = $this->actingAs($user)->post("/products/{$product->id}/reviews", []);
        $response->assertSessionHasErrors(['rating', 'comment']);

        // Successful review
        $payload = ['rating' => 5, 'comment' => 'Great product!'];
        $response = $this->actingAs($user)->post("/products/{$product->id}/reviews", $payload);
        $response->assertStatus(302); // or 201 if API
        $this->assertDatabaseHas('reviews', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'rating' => 5,
        ]);
    }

    public function test_user_can_submit_question_and_admin_can_mark_answered()
    {
        Mail::fake();

        $user = User::factory()->create();
        $admin = User::factory()->create(['role' => 'admin']);
        $product = Product::factory()->create();

        // Submit question (guest or auth)
        $response = $this->actingAs($user)->post("/products/{$product->id}/questions", [
            'question' => 'Is this available in size M?'
        ]);
        $response->assertStatus(302);
        $this->assertDatabaseHas('product_questions', [
            'product_id' => $product->id,
            'question' => 'Is this available in size M?',
            'answered' => false,
        ]);

        // Simulate admin marking as answered
        $question = \DB::table('product_questions')->where('product_id', $product->id)->first();
        $this->actingAs($admin)->post("/admin/questions/{$question->id}/answer", [
            'answer' => 'Yes, it is.',
        ]);
        $this->assertDatabaseHas('product_questions', [
            'id' => $question->id,
            'answered' => true,
        ]);
    }
}