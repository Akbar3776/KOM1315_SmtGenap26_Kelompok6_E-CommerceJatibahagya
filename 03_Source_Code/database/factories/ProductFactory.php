<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category;
use App\Models\Brand;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = \App\Models\Product::class;

    public function definition(): array
    {
        return [
            'name' => fake()->words(3, true),
            'description' => fake()->sentence(),
            'price' => fake()->randomFloat(2, 1, 1000),
            'discount' => 0,
            'is_new_product' => false,
            'stock' => fake()->numberBetween(1, 100),
            'category_id' => Category::factory(),
            'brand_id' => Brand::factory(),
            'image' => null,
            'status' => 'active',
        ];
    }
}
