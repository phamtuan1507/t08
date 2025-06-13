<?php


namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category;
use App\Models\Product;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'description' => $this->faker->sentence,
            'category_id' => Category::factory(), // Tạo category giả lập
            'image' => $this->faker->imageUrl(),
            'stock' => $this->faker->numberBetween(0, 100),
        ];
    }
}
