<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\Sku;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TicketFactory extends Factory
{
    protected $model = Ticket::class;

    public function definition(): array
    {
        return [
            'sku_id' => Sku::factory(),
            'event_id' => Event::factory(),
            'ticket_code' => 'TKT-' . strtoupper(Str::random(8)),
            'ticket_date' => now()->addDays(7),
            'status' => 'available',
        ];
    }
}
