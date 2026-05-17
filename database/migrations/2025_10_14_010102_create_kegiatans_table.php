<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kegiatans', function (Blueprint $table) {
            // UBAH BARIS INI: Sesuai dengan ERD Anda!
            $table->id('id_kegiatan'); 
            
            $table->string('kode_kegiatan')->unique();
            $table->string('nama_kegiatan');
            $table->string('penanggung_jawab')->nullable();
            $table->string('nama_tim')->nullable();
            $table->string('fungsi');
            $table->string('jenis_kegiatan');
            $table->date('tgl_mulai');
            $table->date('tgl_selesai');
            
            // Tambahan honor dari ERD (saya lihat di file awal Anda tidak ada, padahal di model ada)
            $table->decimal('honor_pcl_per_dokumen', 15, 2)->default(0);       
            $table->decimal('honor_pml_per_dokumen', 15, 2)->default(0);       
            $table->decimal('honor_pengolahan_per_dokumen', 15, 2)->default(0);
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kegiatans');
    }
};