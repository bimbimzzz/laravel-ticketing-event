<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('event_categories')->insert([
            [
                'name' => 'Pantai',
                'description' => 'Kategori untuk acara atau kegiatan yang berlangsung di pantai, seperti pesta pantai, olahraga air, atau piknik tepi laut.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Gunung',
                'description' => 'Kategori untuk acara atau kegiatan yang berfokus pada pendakian, wisata alam di kawasan pegunungan, atau kegiatan petualangan lainnya.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Permainan',
                'description' => 'Kategori untuk acara atau aktivitas yang melibatkan permainan, seperti taman bermain, permainan tradisional, atau kompetisi game.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Budaya',
                'description' => 'Kategori untuk acara atau kegiatan yang menonjolkan seni, tradisi, atau warisan budaya, seperti festival budaya, pameran seni, atau pertunjukan tari.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
