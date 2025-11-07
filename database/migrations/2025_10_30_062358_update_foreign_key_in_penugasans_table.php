<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Cek dulu apakah tabel dan kolom ada
        if (Schema::hasTable('penugasans') && Schema::hasColumn('penugasans', 'mitra_id')) {
            Schema::table('penugasans', function (Blueprint $table) {

                // --- PERUBAHAN: HAPUS BAGIAN DROP FOREIGN KEY ---
                // Kita asumsikan FK lama sudah tidak ada
                /*
                try {
                    $table->dropForeign(['mitra_id']);
                    Log::info('Foreign key pada mitra_id berhasil dihapus.'); 
                } catch (\Exception $e) {
                     Log::warning("Tidak dapat menghapus foreign key pada mitra_id, kemungkinan sudah terhapus: " . $e->getMessage());
                }
                */
                // --- BATAS AKHIR PERUBAHAN ---

                // LANGSUNG Ubah tipe data kolom menjadi VARCHAR(255)
                // (Sesuaikan 255 jika panjang sobat_id Anda berbeda)
                $table->string('mitra_id', 255)->change(); 

                // LANGSUNG Tambahkan Foreign Key Constraint yang baru
                $table->foreign('mitra_id')
                      ->references('sobat_id') // <-- Referensi ke sobat_id (VARCHAR)
                      ->on('mitras')
                      ->onDelete('cascade'); 
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::table('penugasans', function (Blueprint $table) {
             // 1. Hapus foreign key baru
             // Kita pakai nama kolom saja agar fleksibel
             $table->dropForeign(['mitra_id']);

             // 2. Kembalikan tipe data ke BIGINT UNSIGNED
             $table->unsignedBigInteger('mitra_id')->change();

             // 3. Tambahkan kembali foreign key lama (ke 'id')
             $table->foreign('mitra_id')
                   ->references('id')
                   ->on('mitras')
                   ->onDelete('cascade');
        });
    }
};