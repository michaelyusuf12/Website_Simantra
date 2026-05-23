<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash; // Import Hash if needed, though casts handles it

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nama', 
        'username',
        'password',
        'nip',
        'role',      
        'fungsi',
        'foto',    
    ];

    protected $primaryKey = 'id_user';

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array // <-- TAMBAHKAN FUNCTION INI
    {
        return [
            'password' => 'hashed', // <-- Ini akan otomatis hash password
        ];
    }

    // Memberitahu Auth::attempt untuk pakai 'username'
    public function getAuthIdentifierName()
    {
        return 'username';
    }

   
    /**
     * Relasi ke tabel Mitras (Jika User ini adalah Mitra)
     */
    public function dataMitra()
    {
        // Hubungkan tabel users dan mitras melalui kunci utama 'id_user'
        return $this->hasOne(Mitra::class, 'id_user', 'id_user');
    }
}