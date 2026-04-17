<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\EventCategory;
use App\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition(): array
    {
        return [
            'vendor_id' => Vendor::factory(),
            'event_category_id' => EventCategory::factory(),
            'name' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'image' => 'default.png',
            'start_date' => now()->addDays(7),
            'end_date' => now()->addDays(14),
        ];
    }
}
