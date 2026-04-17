<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => 'Admin',
                'email' => 'admin@fic22.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'), // Password aman
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
                'phone' => '081245678912',
                'is_vendor' => 1, // Bukan vendor
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane.smith@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('securePassword456!'), // Password aman
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
                'phone' => '081298765432',
                'is_vendor' => 1, // Vendor
            ],
            [
                'name' => 'Michael Johnson',
                'email' => 'michael.johnson@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('securePassword789!'),
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
                'phone' => '082134567890',
                'is_vendor' => 0, // Bukan vendor
            ],
            [
                'name' => 'Emily Davis',
                'email' => 'emily.davis@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('securePassword321!'),
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
                'phone' => '081923456789',
                'is_vendor' => 1, // Vendor
            ],
            [
                'name' => 'David Wilson',
                'email' => 'david.wilson@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('securePassword654!'),
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
                'phone' => '082345678910',
                'is_vendor' => 0, // Bukan vendor
            ],
        ]);
    }
}
