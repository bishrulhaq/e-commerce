<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = \App\Models\Product::class;

    public function definition()
    {
        return [
            'name' => $this->faker->unique()->words(3, true),
            'slug' => $this->faker->unique()->slug,
            'description' => $this->faker->sentence(10),
            'cost_price' => $this->faker->randomFloat(2, 10, 100), // Random cost between 10 and 100
            'regular_price' => $this->faker->randomFloat(2, 100, 300), // Random regular price
            'sale_price' => $this->faker->optional(0.5)->randomFloat(2, 50, 200), // Optional sale price
            'sale_start' => $this->faker->optional(0.3)->dateTimeBetween('-1 month', 'now'),
            'sale_end' => $this->faker->optional(0.3)->dateTimeBetween('now', '+1 month'),
            'stock' => $this->faker->numberBetween(0, 500),
        ];
    }
}
