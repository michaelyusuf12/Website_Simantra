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
    Schema::create('penugasans', function (Blueprint $table) {
        $table->id();
        $table->foreignId('mitra_id')->constrained('mitras')->onDelete('cascade');
        $table->foreignId('kegiatan_id')->constrained('kegiatans')->onDelete('cascade');
        $table->integer('honor');
        $table->integer('jumlah_dokumen')->nullable();
        $table->string('bulan_kegiatan');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penugasans');
    }
};
