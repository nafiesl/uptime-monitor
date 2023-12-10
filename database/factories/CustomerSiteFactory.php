<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerSiteFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => $this->faker->words(3, true),
            'url' => $this->faker->url,
            'is_active' => 1,
            'owner_id' => function () {
                return User::factory()->create()->id;
            },
        ];
    }
}
