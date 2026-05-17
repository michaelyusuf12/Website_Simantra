<?php

namespace App\Http\Controllers;

use App\Models\User; // <-- Model User (Pegawai)
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; 
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class PegawaiController extends Controller
{
    /**
     * Menampilkan daftar semua pegawai.
     */
    public function index(Request $request)
    {
        // PERBAIKAN 1: Filter HANYA untuk role admin dan pegawai
        $query = User::whereIn('role', ['admin', 'pegawai']); 

        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            // PERBAIKAN 2: Kelompokkan pencarian di dalam kurung agar filter role tidak tertembus
            $query->where(function($q) use ($searchTerm) {
                $q->where('username', 'like', '%' . $searchTerm . '%')
                  ->orWhere('nip', 'like', '%' . $searchTerm . '%')
                  ->orWhere('nama', 'like', '%' . $searchTerm . '%'); // Sekalian bisa cari berdasarkan nama
            });
        }

        $pegawais = $query->latest()->paginate(10)->appends($request->query());

        return view('admin.pegawai.index', compact('pegawais'));
    }

    /**
     * Menyimpan pegawai baru ke database.
     */
    public function store(Request $request)
    {
        $rules = [
            'nama'     => ['required', 'string', 'max:255'], // <-- TAMBAHKAN VALIDASI NAMA
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6'], 
            'nip'      => ['nullable', 'string', 'max:255', 'unique:users'],
            'fungsi'   => ['nullable', 'string', 'max:255'],
            'role'     => ['required', Rule::in(['admin', 'pegawai'])], 
        ];

        $messages = [
            'nama.required'     => 'Nama pegawai wajib diisi.', // <-- TAMBAHKAN PESAN NAMA
            'username.required' => 'Username wajib diisi.',
            'username.unique'   => 'Username ini sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min'      => 'Password minimal harus 6 karakter.',
            'nip.unique'        => 'NIP ini sudah terdaftar.',
            'role.required'     => 'Role wajib dipilih.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->route('pegawai.index') 
                ->withErrors($validator) 
                ->withInput() 
                ->with('show_modal_tambah', true); 
        }

        User::create([
            'nama'     => $request->nama, // <-- TAMBAHKAN PENYIMPANAN NAMA
            'username' => $request->username,
            'password' => Hash::make($request->password), // Pastikan password di-hash manual jika tidak otomatis di Model
            'nip'      => $request->nip,
            'fungsi'   => $request->fungsi,
            'role'     => $request->role, 
        ]);

        return redirect()->route('pegawai.index')
                         ->with('success', 'Data pegawai berhasil ditambahkan.');
    }

    /**
     * Memperbarui data pegawai.
     */
    public function update(Request $request, User $pegawai) 
    {
        $request->validate([
            'nama'     => ['required', 'string', 'max:255'], // <-- TAMBAHKAN VALIDASI NAMA
            'username' => [
                'required', 
                'string', 
                'max:255', 
                Rule::unique('users', 'username')->ignore($pegawai->id_user, 'id_user')
            ],
            'nip'      => [
                'nullable', 
                'string', 
                'max:255', 
                Rule::unique('users', 'nip')->ignore($pegawai->id_user, 'id_user')
            ],
            'fungsi'   => ['nullable', 'string', 'max:255'],
            'role'     => ['required', Rule::in(['admin', 'pegawai'])], 
        ]);

        $updateData = [
            'nama'     => $request->nama, // <-- TAMBAHKAN NAMA
            'username' => $request->username,
            'nip'      => $request->nip,
            'fungsi'   => $request->fungsi,
            'role'     => $request->role, 
        ];

        if ($request->filled('password')) {
             $request->validate(['password' => ['string', 'min:6']]);
             $updateData['password'] = Hash::make($request->password); 
        }

        $pegawai->update($updateData);

        return redirect()->route('pegawai.index')
                         ->with('success', 'Data pegawai berhasil diperbarui.');
    }

    /**
     * Menghapus data pegawai.
     */
    public function destroy(User $pegawai)
    {
        $pegawai->delete();

        return redirect()->route('pegawai.index')
                         ->with('success', 'Data pegawai berhasil dihapus.');
    }
}