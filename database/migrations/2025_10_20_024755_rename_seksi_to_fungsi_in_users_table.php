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
        Schema::table('users', function (Blueprint $table) {
            // Ubah nama kolom 'seksi' menjadi 'fungsi'
            $table->renameColumn('seksi', 'fungsi');
        });
    }

public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Jika di-rollback, kembalikan namanya menjadi 'seksi'
            $table->renameColumn('fungsi', 'seksi');
        });
    }   
};
