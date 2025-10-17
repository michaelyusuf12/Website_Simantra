<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kegiatan; // Jangan lupa import Model

class KegiatanSeeder extends Seeder
{
    public function run(): void
    {
        Kegiatan::create([
            'kode_kegiatan' => 'K001',
            'nama_kegiatan' => 'Survei Sosial Ekonomi Nasional',
            'fungsi' => 'Sosial',
            'jenis_kegiatan' => 'Lapangan',
            'tgl_mulai' => '2025-09-01',
            'tgl_selesai' => '2025-09-30',
        ]);

        Kegiatan::create([
            'kode_kegiatan' => 'K002',
            'nama_kegiatan' => 'Pendataan Potensi Desa',
            'fungsi' => 'Produksi',
            'jenis_kegiatan' => 'Pengolahan',
            'tgl_mulai' => '2025-10-01',
            'tgl_selesai' => '2025-10-31',
        ]);
    }
}