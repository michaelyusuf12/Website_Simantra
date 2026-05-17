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
        Schema::create('detail_penugasans', function (Blueprint $table) {
            // 1. Primary Key sesuai ERD
            $table->id('id_detail_penugasan');

            // 2. Relasi ke Induk (Penugasans) -> Mencari id_penugasan
            $table->unsignedBigInteger('id_penugasan');
            $table->foreign('id_penugasan')->references('id_penugasan')->on('penugasans')->onDelete('cascade');

            // 3. Relasi ke Kegiatan -> Mencari id_kegiatan
            $table->unsignedBigInteger('id_kegiatan');
            $table->foreign('id_kegiatan')->references('id_kegiatan')->on('kegiatans')->onDelete('cascade');

            // 4. Kolom Rincian Lainnya sesuai ERD
            $table->string('uraian_tugas')->nullable(); // Untuk menyimpan peran (PCL/PML/Petugas)
            $table->integer('volume');
            $table->string('satuan')->nullable();
            $table->decimal('harga_satuan', 15, 2);
            $table->string('kode_beban_anggaran')->nullable();
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_penugasans');
    }
};