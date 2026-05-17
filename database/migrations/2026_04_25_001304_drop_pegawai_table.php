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
    Schema::dropIfExists('pegawai');
}

public function down(): void
{
    // Opsional: mendefinisikan ulang tabel jika ingin di-rollback
}
};
