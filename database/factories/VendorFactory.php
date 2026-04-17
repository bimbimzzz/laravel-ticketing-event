<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\Factory;

class VendorFactory extends Factory
{
    protected $model = Vendor::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => fake()->company(),
            'description' => fake()->sentence(),
            'location' => fake()->address(),
            'phone' => fake()->phoneNumber(),
            'city' => fake()->city(),
            'verify_status' => 'approved',
        ];
    }
}
