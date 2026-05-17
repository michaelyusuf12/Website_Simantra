<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory; // Aktifkan HasFactory

    /**
     * Atribut yang boleh diisi secara massal.
     * Sesuaikan dengan struktur tabel settings terakhir Anda.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tahun',
        'posisi_kode', // Untuk kode 1 (Lapangan) atau 2 (Pengolahan)
        'batas_honor',
        'dasar_aturan',
        'updated_by'
    ];

    /**
     * Atribut yang harus disembunyikan saat serialisasi. (Opsional untuk Setting)
     *
     * @var array<int, string>
     */
    protected $hidden = [
        // Biasanya tidak ada yang perlu disembunyikan di model Setting
    ];

    /**
     * Atribut yang harus di-cast ke tipe data asli. (Opsional untuk Setting)
     *
     * @var array<string, string>
     */
    protected $casts = [
        'batas_honor' => 'decimal:2', // Pastikan batas_honor dibaca sebagai angka desimal
    ];
}