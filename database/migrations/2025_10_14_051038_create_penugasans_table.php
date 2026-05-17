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
            // 1. Primary Key sesuai ERD
            $table->id('id_penugasan'); 
            
            // 2. Relasi ke Mitra (TIPE STRING menyesuaikan sobat_id)
            $table->string('mitra_id'); 
            $table->foreign('mitra_id')->references('sobat_id')->on('mitras')->onDelete('cascade');
            
            // 3. Relasi ke User (Pegawai yang membuat)
            $table->unsignedBigInteger('id_user')->nullable(); 
            // Opsional: Buka komentar di bawah ini jika PK di tabel users adalah 'id'
            // $table->foreign('id_user')->references('id')->on('users')->onDelete('set null');

            // 4. Data Surat sesuai ERD
            $table->string('no_surat');
            $table->date('tanggal_surat');
            $table->string('tahun_anggaran', 4)->nullable();
            
            // 5. Total Nilai Kontrak Keseluruhan
            $table->decimal('total_nilai_perjanjian', 15, 2)->default(0);
            
            // 6. Status & Bulan
            $table->string('status_kontrak')->default('Menunggu Approval');
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