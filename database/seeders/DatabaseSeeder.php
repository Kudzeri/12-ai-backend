<?php

namespace Database\Seeders;

use App\Models\Advertisement;
use App\Models\Category;
use App\Models\ContactInfo;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Category::truncate();
        User::truncate();
        Advertisement::truncate();
        Post::truncate();
        ContactInfo::truncate();

        $users = User::factory()->count(10)->create();

        $categories = Category::factory()->count(10)->create();

        $advertisements = Advertisement::factory()
            ->count(10)
            ->create([
                'user_id' => $users->random()->id,
                'category_id' => $categories->random()->id,
                'subcategory_id' => $categories->random()->id
            ]);

        foreach ($advertisements as $advertisement) {
            Post::factory()->create([
                'advertisement_id' => $advertisement->id,
            ]);

            ContactInfo::factory()->create([
                'advertisement_id' => $advertisement->id,
            ]);
        }
    }
}
