<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\Sku;
use Illuminate\Database\Eloquent\Factories\Factory;

class SkuFactory extends Factory
{
    protected $model = Sku::class;

    public function definition(): array
    {
        return [
            'event_id' => Event::factory(),
            'name' => fake()->word() . ' Ticket',
            'category' => fake()->randomElement(['VIP', 'Regular', 'Premium']),
            'price' => fake()->numberBetween(50000, 500000),
            'stock' => fake()->numberBetween(10, 100),
            'day_type' => fake()->randomElement(['weekday', 'weekend']),
        ];
    }
}
