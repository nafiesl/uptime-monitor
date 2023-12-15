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
            'check_interval' => 1,
            'priority_code' => 'normal',
            'warning_threshold' => 5000,
            'down_threshold' => 10000,
            'notify_user_interval' => 5,
            'last_check_at' => null,
            'last_notify_user_at' => null,
        ];
    }
}
