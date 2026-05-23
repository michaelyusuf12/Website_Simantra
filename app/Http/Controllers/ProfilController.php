<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Models\User;

class ProfilController extends Controller
{
    /**
     * Tampilkan halaman profil
     */
    public function index()
    {
        // Panggil User beserta relasi data mitra-nya sekaligus
        $user = Auth::user()->load('dataMitra');
        
        return view('profil.index', compact('user'));
    }

    /**
     * Update data profil dan Foto
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // 1. VALIDASI DASAR (Nama dan Foto)
        $rules = [
            'nama' => ['required', 'string', 'max:255'],
            'foto' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'], // Maksimal 2MB
        ];

        // PERBAIKAN: Validasi NIP yang lebih fleksibel
        // Hanya cek NIP jika yang login admin, ATAU jika form mengirimkan data NIP
        if ($user->role == 'admin' || ($request->has('nip') && $request->nip != null)) {
            $rules['nip'] = [
                'nullable', 
                'string', 
                'max:255', 
                Rule::unique('users', 'nip')->ignore($user->id_user, 'id_user')
            ];
        }

        $request->validate($rules, [
            'nip.unique' => 'NIP ini sudah terdaftar.',
            'foto.image' => 'File yang diupload harus berupa gambar.',
            'foto.mimes' => 'Format gambar harus jpeg, png, atau jpg.',
            'foto.max'   => 'Ukuran foto maksimal 2 MB.',
        ]);

        // 2. PROSES UPDATE DATA TEKS
        $user->nama = $request->nama;

        if ($user->role == 'admin' && $request->has('nip')) {
            $user->nip = $request->nip;
        }

        // 3. PROSES UPLOAD FOTO PROFIL
        if ($request->hasFile('foto')) {
            
            // Hapus foto lama jika ada (menggunakan disk public)
            if ($user->foto && Storage::disk('public')->exists('profiles/' . $user->foto)) {
                Storage::disk('public')->delete('profiles/' . $user->foto);
            }

            // Ambil file gambar yang baru
            $file = $request->file('foto');
            
            // Buat nama file yang unik berdasarkan waktu saat ini
            $nama_file = 'profil_' . time() . '.' . $file->getClientOriginalExtension();
            
            // Simpan file ke dalam disk public
            $file->storeAs('profiles', $nama_file, 'public');
            
            // Catat nama filenya ke data user
            $user->foto = $nama_file;
        }
        
        // Simpan langsung perubahan ke database
        $user->save();

        return back()->with('success', 'Profil dan foto berhasil diperbarui.');
    }

    /**
     * Ganti Password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:6|confirmed', 
        ], [
            'current_password.required' => 'Password lama wajib diisi.',
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password baru minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        $user = auth()->user();
        
        // Cek apakah password lama yang diketik sama dengan di database
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password lama salah.']);
        }

        // Update password baru
        $user->password = Hash::make($request->password); 
        $user->save();

        return back()->with('success', 'Password berhasil diganti.');
    }

    
}