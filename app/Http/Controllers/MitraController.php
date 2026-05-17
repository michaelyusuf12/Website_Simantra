<?php

namespace App\Http\Controllers;

use App\Models\Mitra;
use App\Models\User; // TAMBAHAN: Memanggil model User
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB; // TAMBAHAN: Memanggil fitur Database Transaction
use Illuminate\Support\Facades\Hash; // TAMBAHAN: Memanggil fitur Enkripsi Password

class MitraController extends Controller
{
    private $posisiOptions = [
        1 => 'Lapangan',
        2 => 'Pengolahan',
        3 => 'Lapangan dan Pengolahan',
    ];

    public function index(Request $request)
    {
        $query = Mitra::with('user'); // TAMBAHAN: Memuat data relasi user agar lebih cepat

        if ($request->has('search') && $request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('nama_petugas', 'like', '%' . $searchTerm . '%')
                  ->orWhere('email', 'like', '%' . $searchTerm . '%');
            });
        }

        $mitras = $query->latest()->paginate(10)->appends($request->query());

        // Menggunakan rute admin.mitra.index (sesuai yang kita perbaiki sebelumnya)
        return view('admin.mitra.index', [
            'mitras' => $mitras,
            'posisiOptions' => $this->posisiOptions
        ]);
    }

    public function store(Request $request)
    {
        // 1. Validasi Input (Termasuk Username & Password)
        $request->validate([
            'nama_petugas' => 'required|string|max:255',
            'email' => 'required|email|unique:mitras,email',
            'sobat_id' => 'required|string|unique:mitras,sobat_id', 
            'posisi_petugas' => ['nullable', Rule::in(array_keys($this->posisiOptions))],
            'telepon' => 'nullable|string',
            'alamat' => 'nullable|string',
            'kode_prov' => 'nullable|string',
            'kode_kab' => 'nullable|string',
            
            // Validasi Akun Login
            'username' => 'required|string|unique:users,username', 
            'password' => 'required|string|min:6',
        ]);

        // 2. Mulai Transaksi (Satu Form, Dua Tabel)
// 2. Mulai Transaksi (Satu Form, Dua Tabel)
        DB::transaction(function () use ($request) {
            
            // LANGKAH A: Buat Akun di tabel users (Cara Manual)
            $userBaru = new User();
            $userBaru->nama = $request->nama_petugas; 
            $userBaru->username = $request->username;
            $userBaru->password = Hash::make($request->password);
            $userBaru->role = 'mitra';
            $userBaru->save(); // Simpan ke database

            // LANGKAH B: Buat Profil di tabel mitras
            $dataMitra = $request->except(['username', 'password']); 
            
            // Tambahkan id_user dari akun yang baru dibuat ke dalam data mitra
            // Catatan: Pastikan menggunakan ->id_user jika primary key di tabel users Anda adalah id_user
            $dataMitra['id_user'] = $userBaru->id_user ?? $userBaru->id; 

            // Simpan ke tabel mitras
            Mitra::create($dataMitra);
        });

        // Ingat, route redirect-nya sesuaikan dengan nama route Anda di web.php
        // Pastikan nama route-nya benar-benar 'mitra.index' atau 'admin.mitra.index'
        return redirect()->route('mitra.index')
                         ->with('success', 'Data mitra dan akun login berhasil ditambahkan.');
    }

    public function update(Request $request, Mitra $mitra)
    {
        // 1. Validasi Input Update
        $request->validate([
            'nama_petugas' => 'required|string|max:255',
            'email' => 'required|email|unique:mitras,email,' . $mitra->sobat_id . ',sobat_id', 
            'sobat_id' => 'required|string|unique:mitras,sobat_id,' . $mitra->sobat_id . ',sobat_id',
            'posisi_petugas' => ['nullable', Rule::in(array_keys($this->posisiOptions))],
            'telepon' => 'nullable|string',
            'alamat' => 'nullable|string',
            'kode_prov' => 'nullable|string',
            'kode_kab' => 'nullable|string',
            
            // Validasi Akun Login (Kecualikan ID user yang sedang diubah)
            'username' => 'required|string|unique:users,username,' . $mitra->id_user . ',id_user', 
            'password' => 'nullable|string|min:6', // Password boleh kosong saat edit
        ]);

        // 2. Mulai Transaksi Update
        DB::transaction(function () use ($request, $mitra) {
            
            // LANGKAH A: Update Akun di tabel users
            $user = User::where('id_user', $mitra->id_user)->first();
            if ($user) {
                $user->nama = $request->nama_petugas;
                $user->username = $request->username;
                
                // Jika admin mengisi password baru, update password-nya
                if ($request->filled('password')) {
                    $user->password = Hash::make($request->password);
                }
                $user->save();
            }

            // LANGKAH B: Update Profil di tabel mitras
            $dataMitra = $request->except(['username', 'password']);
            $mitra->update($dataMitra);
        });

        return redirect()->route('mitra.index')
                         ->with('success', 'Data mitra berhasil diperbarui.');
    }
    
    public function destroy(Mitra $mitra)
    {
        // Gunakan transaksi agar penghapusan aman
        DB::transaction(function () use ($mitra) {
            // Hapus akun user-nya (ini otomatis akan menghapus mitras juga jika migration Anda menggunakan onDelete Cascade,
            // tapi kita lakukan manual untuk berjaga-jaga)
            $user = User::where('id_user', $mitra->id_user)->first();
            
            $mitra->delete(); // Hapus profil mitranya dulu
            
            if ($user) {
                $user->delete(); // Lalu hapus akun login-nya
            }
        });
        
        return redirect()->route('mitra.index')
                         ->with('success', 'Data mitra beserta akunnya berhasil dihapus.');
    }
}