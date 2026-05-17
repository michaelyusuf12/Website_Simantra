<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelolakegiatan extends Model
{
    use HasFactory;

    protected $table = 'kelolakegiatans'; // nama tabel default dari Laravel

    protected $primaryKey = 'id_kegiatan';

    protected $fillable = [
        'nama_mitra',
        'honor',
        'nama_kegiatan',
        'bulan_kegiatan',
    ];
}
