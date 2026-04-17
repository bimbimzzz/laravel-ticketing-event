<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('vendors')->insert([
            [
                'user_id' => 1,
                'name' => 'Beachside Adventures',
                'location' => '123 Ocean View Rd, Bali',
                'phone' => '081234567890',
                'city' => 'Bali',
                'description' => 'Penyedia aktivitas dan pengalaman seru di tepi pantai.',
                'verify_status' => 'approved',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2,
                'name' => 'Mountain Trails Co.',
                'location' => '456 Hilltop Lane, Bandung',
                'phone' => '082345678901',
                'city' => 'Bandung',
                'description' => 'Pakar dalam menyediakan pengalaman mendaki gunung.',
                'verify_status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 3,
                'name' => 'Family Fun Inc.',
                'location' => '789 Carnival Blvd, Jakarta',
                'phone' => '083456789012',
                'city' => 'Jakarta',
                'description' => 'Spesialis dalam menyediakan hiburan keluarga.',
                'verify_status' => 'approved',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 1,
                'name' => 'Cultural Wonders',
                'location' => '321 Heritage Rd, Yogyakarta',
                'phone' => '084567890123',
                'city' => 'Yogyakarta',
                'description' => 'Penyelenggara acara budaya yang menginspirasi.',
                'verify_status' => 'rejected',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2,
                'name' => 'Ocean Explorers',
                'location' => '654 Seaside Blvd, Lombok',
                'phone' => '085678901234',
                'city' => 'Lombok',
                'description' => 'Menawarkan petualangan laut yang tak terlupakan.',
                'verify_status' => 'approved',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 3,
                'name' => 'Adventure Works',
                'location' => '987 Trekking St, Malang',
                'phone' => '086789012345',
                'city' => 'Malang',
                'description' => 'Mitra utama untuk semua kebutuhan petualangan.',
                'verify_status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
