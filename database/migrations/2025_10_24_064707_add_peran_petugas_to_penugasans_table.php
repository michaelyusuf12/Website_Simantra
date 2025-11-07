<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('penugasans', function (Blueprint $table) {
            // Menambahkan kolom baru 'peran_petugas' setelah 'bulan_kegiatan'
            $table->string('peran_petugas')->nullable()->after('bulan_kegiatan');
        });
    }

    public function down(): void
    {
        Schema::table('penugasans', function (Blueprint $table) {
            $table->dropColumn('peran_petugas');
        });
    }
};