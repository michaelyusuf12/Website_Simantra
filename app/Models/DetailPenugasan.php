<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPenugasan extends Model
{
    protected $table = 'detail_penugasans';

    // Tentukan Primary Key kustom
    protected $primaryKey = 'id_detail_penugasan';

    protected $fillable = [
        'id_penugasan', 'id_kegiatan', 'uraian_tugas', 'volume', 
        'satuan', 'harga_satuan', 'kode_beban_anggaran', 'tanggal_mulai', 'tanggal_selesai'
    ];

    // Relasi ke Kegiatan
    public function kegiatan()
    {
        // Berdasarkan gambar, foreign key-nya adalah id_kegiatan
        return $this->belongsTo(Kegiatan::class, 'id_kegiatan', 'id_kegiatan');
    }
}