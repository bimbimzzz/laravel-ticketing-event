<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SkuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('skus')->insert([
            [
                'name' => 'Dewasa',
                'category' => 'Regular',
                'event_id' => 1, // Assuming event_id 1 exists
                'price' => 100000.00,
                'stock' => 50,
                'day_type' => 'Weekday',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Anak-Anak',
                'category' => 'Regular',
                'event_id' => 1, // Assuming event_id 1 exists
                'price' => 50000.00,
                'stock' => 30,
                'day_type' => 'Weekday',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'VIP Dewasa',
                'category' => 'VIP',
                'event_id' => 2, // Assuming event_id 2 exists
                'price' => 200000.00,
                'stock' => 20,
                'day_type' => 'Weekend',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'VIP Anak-Anak',
                'category' => 'VIP',
                'event_id' => 2, // Assuming event_id 2 exists
                'price' => 150000.00,
                'stock' => 15,
                'day_type' => 'Weekend',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Keluarga',
                'category' => 'Bundle',
                'event_id' => 3, // Assuming event_id 3 exists
                'price' => 300000.00,
                'stock' => 10,
                'day_type' => 'Weekday',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Promo Akhir Tahun',
                'category' => 'Promo',
                'event_id' => 3, // Assuming event_id 3 exists
                'price' => 75000.00,
                'stock' => 25,
                'day_type' => 'Weekday',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
