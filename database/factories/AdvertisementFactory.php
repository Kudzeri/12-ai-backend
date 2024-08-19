<?php

namespace Database\Factories;

use App\Models\Advertisement;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class AdvertisementFactory extends Factory
{
    protected $model = Advertisement::class;

    public function definition(): array
    {
        return [
            'user_id' => $this->faker->numberBetween(1, 10),
            'name' => $this->faker->word(),
            'category_id' => Category::factory(),
            'subcategory_id' => Category::factory(),
            'brand' => $this->faker->word(),
            'model' => $this->faker->word(),
            'conditions' => $this->faker->word(),
            'authenticity' => $this->faker->word(),
            'price' => $this->faker->numberBetween(1000, 50000),
            'negotiable' => $this->faker->boolean(),
            'tags' => $this->faker->words(3, true),
        ];
    }
}
