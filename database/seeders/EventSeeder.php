<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('events')->insert([
            [
                'vendor_id' => 1,
                'event_category_id' => 1,
                'name' => 'Sunset Beach Party',
                'description' => 'Nikmati pesta pantai dengan live music dan BBQ.',
                'image' => 'events/sunset_beach_party.jpg',
                'start_date' => now()->addDays(3)->format('Y-m-d'),
                'end_date' => now()->addDays(4)->format('Y-m-d'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'vendor_id' => 2,
                'event_category_id' => 2,
                'name' => 'Mountain Adventure',
                'description' => 'Jelajahi alam bebas dengan pendakian seru.',
                'image' => 'events/mountain_adventure.jpg',
                'start_date' => now()->addWeek()->format('Y-m-d'),
                'end_date' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'vendor_id' => 3,
                'event_category_id' => 3,
                'name' => 'Fun Game Day',
                'description' => 'Ajak keluarga menikmati hari penuh permainan.',
                'image' => 'events/fun_game_day.jpg',
                'start_date' => now()->addWeeks(2)->format('Y-m-d'),
                'end_date' => now()->addWeeks(2)->addDay()->format('Y-m-d'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'vendor_id' => 1,
                'event_category_id' => 4,
                'name' => 'Cultural Festival',
                'description' => 'Pameran seni dan budaya lokal.',
                'image' => 'events/cultural_festival.jpg',
                'start_date' => now()->addWeeks(3)->format('Y-m-d'),
                'end_date' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'vendor_id' => 2,
                'event_category_id' => 1,
                'name' => 'Beach Volleyball Tournament',
                'description' => 'Turnamen voli pantai dengan hadiah menarik.',
                'image' => 'events/beach_volleyball.jpg',
                'start_date' => now()->addMonth()->format('Y-m-d'),
                'end_date' => now()->addMonth()->addDay()->format('Y-m-d'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'vendor_id' => 3,
                'event_category_id' => 2,
                'name' => 'Peak Challenge',
                'description' => 'Tantang diri Anda dengan pendakian puncak gunung.',
                'image' => 'events/peak_challenge.jpg',
                'start_date' => now()->addMonth()->addWeek()->format('Y-m-d'),
                'end_date' => now()->addMonth()->addWeek()->addDays(2)->format('Y-m-d'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'vendor_id' => 1,
                'event_category_id' => 3,
                'name' => 'Family Fun Carnival',
                'description' => 'Karnaval keluarga dengan banyak permainan seru.',
                'image' => 'events/family_fun_carnival.jpg',
                'start_date' => now()->addMonths(2)->format('Y-m-d'),
                'end_date' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'vendor_id' => 2,
                'event_category_id' => 4,
                'name' => 'Traditional Dance Show',
                'description' => 'Saksikan tari-tarian tradisional yang memukau.',
                'image' => 'events/traditional_dance.jpg',
                'start_date' => now()->addMonths(2)->addWeek()->format('Y-m-d'),
                'end_date' => now()->addMonths(2)->addWeek()->addDay()->format('Y-m-d'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'vendor_id' => 3,
                'event_category_id' => 1,
                'name' => 'Night Beach Festival',
                'description' => 'Festival pantai malam dengan lampu dan musik.',
                'image' => 'events/night_beach_festival.jpg',
                'start_date' => now()->addMonths(3)->format('Y-m-d'),
                'end_date' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'vendor_id' => 1,
                'event_category_id' => 2,
                'name' => 'Hiking and Camping Weekend',
                'description' => 'Akhir pekan seru dengan hiking dan berkemah.',
                'image' => 'events/hiking_camping.jpg',
                'start_date' => now()->addMonths(3)->addWeek()->format('Y-m-d'),
                'end_date' => now()->addMonths(3)->addWeek()->addDays(2)->format('Y-m-d'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
