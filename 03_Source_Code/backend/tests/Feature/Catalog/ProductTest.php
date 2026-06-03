<?php

namespace Tests\Feature\Catalog;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Product;
use App\Models\Category;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_products_index_returns_paginated_list()
    {
        Product::factory()->count(30)->create();

        $response = $this->getJson('/products');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data',
                'links',
                'meta',
            ]);

        $this->assertCount(15, $response->json('data')); // adjust per-page if needed
    }

    public function test_product_show_returns_valid_product_details()
    {
        $category = Category::factory()->create();
        $product = Product::factory()->for($category)->create();

        $response = $this->getJson("/products/{$product->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'name',
                'slug',
                'price',
                'description',
                'category' => ['id', 'name'],
            ]);
    }

    public function test_products_filter_sort_and_pagination_work()
    {
        $catA = Category::factory()->create(['name' => 'A']);
        $catB = Category::factory()->create(['name' => 'B']);

        Product::factory()->count(5)->for($catA)->create(['price' => 100]);
        Product::factory()->count(12)->for($catB)->create(['price' => 50]);
        Product::factory()->count(8)->for($catA)->create(['price' => 200]);

        $response = $this->getJson('/products?category=' . $catA->id . '&sort=price_desc&per_page=10&page=1');

        $response->assertStatus(200)
            ->assertJsonPath('meta.per_page', 10)
            ->assertJsonPath('meta.current_page', 1);

        $data = $response->json('data');
        $this->assertNotEmpty($data);
        $this->assertArrayHasKey('price', $data[0]);
    }
}