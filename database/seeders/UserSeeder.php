<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Hapus user lama jika ada
        User::truncate();

        // Buat user baru dengan kolom 'fungsi'
        User::create([
            'username' => 'admin',
            'password' => Hash::make('password'),
            'nip' => '199001012020011001',
            'fungsi' => 'Produksi', // <-- UBAH 'seksi' MENJADI 'fungsi' DI SINI
        ]);
    }
}