<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Akun Admin
        User::create([
            'nama' => 'Administrator',
            'nip' => '198001012005011001',
            'username' => 'admin_simantra',
            'password' => Hash::make('password123'), // Semua password saya set 'password123' agar mudah
            'role' => 'admin',
        ]);

        // 2. Akun Pegawai
        User::create([
            'nama' => 'Budi Pegawai',
            'nip' => '199002022015031002',
            'username' => 'pegawai_ipds',
            'password' => Hash::make('password123'),
            'role' => 'pegawai',
        ]);

        // 3. Akun Kepala BPS
        User::create([
            'nama' => 'Drs. Ahmad (Kepala)',
            'nip' => '197003031995011001',
            'username' => 'kepala_bps',
            'password' => Hash::make('password123'),
            'role' => 'kepala',
        ]);

        // 4. Akun Mitra
        User::create([
            'nama' => 'Slamet Riadi',
            'nip' => '3201010101010001',
            'username' => '3201001',
            'password' => Hash::make('password123'),
            'role' => 'mitra',
        ]);
    }
}