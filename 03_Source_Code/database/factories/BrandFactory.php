<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Brand>
 */
class BrandFactory extends Factory
{
    protected $model = \App\Models\Brand::class;

    public function definition(): array
    {
        return [
            'name' => fake()->unique()->company(),
            'logo' => null,
        ];
    }
}
