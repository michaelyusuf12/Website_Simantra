<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penugasan extends Model
{
    use HasFactory;

    protected $table = 'penugasans';
    protected $primaryKey = 'id_penugasan';

    protected $fillable = [
        'no_surat',
        'tanggal_surat',
        'tahun_anggaran',
        'mitra_id',
        'id_user',
        'total_nilai_perjanjian',
        'status_kontrak',
        'bulan_kegiatan', 
    ];

    public function mitra()
    {
        // INI KUNCI UTAMANYA:
        // Parameter 1: Model tujuan
        // Parameter 2: Kolom di tabel penugasans (mitra_id)
        // Parameter 3: Kolom di tabel mitras (sobat_id)
        return $this->belongsTo(Mitra::class, 'mitra_id', 'sobat_id'); 
    }

    public function details()
    {
        return $this->hasMany(DetailPenugasan::class, 'id_penugasan', 'id_penugasan');
    }
}