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
        Schema::table('mitras', function (Blueprint $table) {
            // Hapus kolom 'id'
            $table->dropColumn('id'); 
        });
    }

    /**
     * Reverse the migrations.
     * (Untuk jaga-jaga jika perlu rollback)
     */
    public function down(): void
    {
        Schema::table('mitras', function (Blueprint $table) {
            // Tambahkan kembali kolom 'id' sebagai primary key sementara
            // 'first()' agar posisinya kembali di awal tabel
            $table->id()->first(); 
        });

        // Setelah menambahkan kembali kolom 'id', 
        // kita perlu menghapus PK 'sobat_id' sementara
        // agar 'id' bisa jadi PK lagi saat rollback penuh.
        // Catatan: Ini mungkin perlu penyesuaian jika ada foreign key
        Schema::table('mitras', function (Blueprint $table) {
             if (DB::getSchemaBuilder()->hasIndex('mitras', 'PRIMARY')) {
                 $primaryKeyColumns = DB::select("SHOW KEYS FROM mitras WHERE Key_name = 'PRIMARY'");
                 if (count($primaryKeyColumns) === 1 && $primaryKeyColumns[0]->Column_name === 'sobat_id') {
                     $table->dropPrimary('sobat_id'); // Hapus PK sobat_id jika itu PK nya
                 }
             }
        });
    }
};