<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mitra extends Model
{
    use HasFactory;

    protected $table = 'mitras';

    // Primary Key yang sebenarnya di database (ikon kunci emas)
    protected $primaryKey = 'id_user';

    protected $fillable = [
        'id_user',
        'nama_petugas',
        'posisi_petugas',
        'email',
        'telepon',
        'alamat',
        'sobat_id',
        'kode_prov',
        'kode_kab'
    ];

    // --- TAMBAHKAN KODE RELASI INI ---
    /**
     * Relasi ke model User
     * Menghubungkan id_user di tabel mitras dengan id di tabel users
     */
    public function user()
    {
        // Sesuaikan parameter ketiga ('id') jika primary key di tabel users milikmu bukan 'id'
        return $this->belongsTo(User::class, 'id_user', 'id_user'); 
    }
    public function getRouteKeyName()
    {
        return 'sobat_id';
    }
}