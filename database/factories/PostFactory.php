<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\Advertisement;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array
    {
        return [
            'advertisement_id' => Advertisement::factory(),
            'description' => $this->faker->paragraph(),
            'feature' => $this->faker->text(),
            'images' => json_encode([$this->faker->imageUrl(), $this->faker->imageUrl()]),
        ];
    }
}
