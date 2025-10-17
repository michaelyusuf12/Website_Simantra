<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Panggil seeder baru kita di sini
        $this->call([
            MitraSeeder::class,
            KegiatanSeeder::class,
            UserSeeder::class,
        ]);
    }
}