<?php

namespace Database\Factories;

use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'category_id' => 2,
            'brand_id' => 4,
            'name' => $this->faker->name,
            'image_url' => "product-1.jpg",
            'price' => $this->faker->randomFloat(2, 0, 1000),
            'old_price' => $this->faker->randomFloat(2, 0, 1000),
            'description' => $this->faker->text(200),
            'tags' => $this->faker->text(10),
            'is_best_sell' => $this->faker->numberBetween(0, 1),
            'is_new' => $this->faker->numberBetween(0, 1),
            'sort_order' => $this->faker->numberBetween(0, 4),
            'active' => $this->faker->numberBetween(0, 1),
            'amount' => $this->faker->randomNumber(),
            'specifications' => json_encode(["ram" => "8GB"]),
            'created_at' => Carbon::now()
        ];
    }
}
