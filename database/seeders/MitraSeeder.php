<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Mitra; // Jangan lupa import Model

class MitraSeeder extends Seeder
{
    public function run(): void
    {
        Mitra::create([
            'nama_petugas' => 'Michael Sampes',
            'posisi_petugas' => 'Lapangan',
            'email' => 'michael@example.com',
            'telepon' => '081234567890',
            'alamat' => 'Pomalaa',
        ]);

        Mitra::create([
            'nama_petugas' => 'Andi Pratama',
            'posisi_petugas' => 'Admin',
            'email' => 'andi@example.com',
            'telepon' => '080987654321',
            'alamat' => 'Kolaka',
        ]);
    }
}