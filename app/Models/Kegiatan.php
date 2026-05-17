<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    use HasFactory;

    // TAMBAHAN WAJIB: Beritahu Laravel nama Primary Key sesuai ERD!
    protected $primaryKey = 'id_kegiatan';

    protected $fillable = [
        'kode_kegiatan',
        'nama_kegiatan',
        'penanggung_jawab',
        'nama_tim',
        'fungsi',
        'jenis_kegiatan',
        'tgl_mulai',
        'tgl_selesai',
        'target_dokumen',
        'honor_pcl_per_dokumen',       
        'honor_pml_per_dokumen',       
        'honor_pengolahan_per_dokumen', 
    ];

    /**
     * Relasi ke tabel Rincian (Detail_Penugasans)
     * 1 Kegiatan bisa ada di banyak Detail Penugasan
     */
    public function detailPenugasans()
    {
        return $this->hasMany(DetailPenugasan::class, 'id_kegiatan', 'id_kegiatan');
    }
}