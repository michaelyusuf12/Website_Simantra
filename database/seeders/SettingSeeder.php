<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
// use App\Models\Setting; // <-- Baris ini mungkin hilang atau salah
use App\Models\Setting; // <-- TAMBAHKAN ATAU PERBAIKI BARIS INI
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('settings')->truncate();
        $tahun = Carbon::now()->year;

        // Buat atau perbarui setting Lapangan (posisi=1) untuk tahun ini
        Setting::updateOrCreate( // <-- Baris ini sekarang bisa menemukan class Setting
            ['tahun' => $tahun, 'posisi_kode' => 1],
            ['batas_honor' => 6000000.00]
        );

        // Buat atau perbarui setting Pengolahan (posisi=2) untuk tahun ini
        Setting::updateOrCreate(
            ['tahun' => $tahun, 'posisi_kode' => 2],
            ['batas_honor' => 4500000.00]
        );

        $this->command->info("Contoh data setting (batas honor per posisi tahun $tahun) berhasil dibuat/diperbarui.");
    }
}