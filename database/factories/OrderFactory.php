<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'event_id' => Event::factory(),
            'quantity' => fake()->numberBetween(1, 5),
            'total_price' => fake()->numberBetween(50000, 1000000),
            'event_date' => now()->addDays(7),
            'status_payment' => 'pending',
        ];
    }

    public function paid(): static
    {
        return $this->state(fn() => ['status_payment' => 'success']);
    }

    public function refundPending(): static
    {
        return $this->state(fn() => [
            'status_payment' => 'refund_pending',
            'cancel_reason' => 'Saya tidak bisa hadir di acara tersebut.',
            'cancelled_at' => now(),
        ]);
    }

    public function refunded(): static
    {
        return $this->state(fn() => [
            'status_payment' => 'refunded',
            'cancel_reason' => 'Saya tidak bisa hadir di acara tersebut.',
            'cancelled_at' => now()->subDay(),
            'refund_note' => 'Refund approved by admin.',
            'refunded_at' => now(),
        ]);
    }
}
