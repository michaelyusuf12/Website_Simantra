<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
    Schema::table('kegiatans', function (Blueprint $table) {
        // Tambahkan kolom setelah target_dokumen 
        // Gunakan decimal untuk nilai uang, nullable karena hanya salah satu set yang terisi
        $table->decimal('honor_pcl_per_dokumen', 15, 2)->nullable()->after('target_dokumen'); 
        $table->decimal('honor_pml_per_dokumen', 15, 2)->nullable()->after('honor_pcl_per_dokumen');
        $table->decimal('honor_pengolahan_per_dokumen', 15, 2)->nullable()->after('honor_pml_per_dokumen');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    Schema::table('kegiatans', function (Blueprint $table) {
        // Hapus kolom jika migrasi dibatalkan
        $table->dropColumn(['honor_pcl_per_dokumen', 'honor_pml_per_dokumen', 'honor_pengolahan_per_dokumen']);
        });
    }
};
