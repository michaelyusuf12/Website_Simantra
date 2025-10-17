<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
    User::truncate(); 
    User::create([
        'username' => 'admin', 
        'password' => Hash::make('password'), 
        'nip' => '199001012020011001', // <-- Contoh NIP
        'seksi' => 'Produksi',       // <-- Contoh Seksi
        ]);
    }
}