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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->year('tahun'); // Tahun aturan berlaku
            $table->tinyInteger('posisi_kode')->unsigned(); // 1 = Lapangan, 2 = Pengolahan
            $table->decimal('batas_honor', 15, 2); // Batas honor bulanan
            $table->timestamps();

            // Kombinasi tahun dan posisi harus unik
            $table->unique(['tahun', 'posisi_kode']); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};