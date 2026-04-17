<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Ticket;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tickets = [
            [
                'sku_id' => 1,
                'event_id' => 1,
                'ticket_code' => Str::uuid(),
                'ticket_date' => now()->addDays(3)->format('Y-m-d'),
                'status' => 'available',
            ],
            [
                'sku_id' => 2,
                'event_id' => 1,
                'ticket_code' => Str::uuid(),
                'ticket_date' => now()->addDays(4)->format('Y-m-d'),
                'status' => 'available',
            ],
            [
                'sku_id' => 3,
                'event_id' => 2,
                'ticket_code' => Str::uuid(),
                'ticket_date' => now()->addWeek()->format('Y-m-d'),
                'status' => 'available',
            ],
            [
                'sku_id' => 4,
                'event_id' => 2,
                'ticket_code' => Str::uuid(),
                'ticket_date' => now()->addWeek()->format('Y-m-d'),
                'status' => 'available',
            ],
            [
                'sku_id' => 5,
                'event_id' => 3,
                'ticket_code' => Str::uuid(),
                'ticket_date' => now()->addWeeks(2)->format('Y-m-d'),
                'status' => 'available',
            ],
            [
                'sku_id' => 5,
                'event_id' => 3,
                'ticket_code' => Str::uuid(),
                'ticket_date' => null,
                'status' => 'available',
            ],
        ];

        foreach ($tickets as $ticket) {
            Ticket::create($ticket);
        }
    }
}
