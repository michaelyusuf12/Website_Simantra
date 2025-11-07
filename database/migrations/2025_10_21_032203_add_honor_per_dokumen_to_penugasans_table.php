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
    Schema::table('penugasans', function (Blueprint $table) {
        // Tambahkan kolom baru setelah 'kegiatan_id'
        $table->decimal('honor_per_dokumen', 15, 2)->default(0)->after('kegiatan_id'); 
        });
    }

    public function down(): void
    {
    Schema::table('penugasans', function (Blueprint $table) {
        $table->dropColumn('honor_per_dokumen');
        });
    }
};
