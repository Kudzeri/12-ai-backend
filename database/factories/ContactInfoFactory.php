<?php

namespace Database\Factories;

use App\Models\ContactInfo;
use App\Models\Advertisement;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContactInfoFactory extends Factory
{
    protected $model = ContactInfo::class;

    public function definition(): array
    {
        return [
            'advertisement_id' => Advertisement::factory(),
            'phone_number' => $this->faker->phoneNumber(),
            'backup_phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->unique()->safeEmail(),
            'website_link' => $this->faker->url(),
            'country' => $this->faker->country(),
            'location' => $this->faker->address(),
        ];
    }
}
