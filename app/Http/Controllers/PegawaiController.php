<?php

namespace App\Http\Controllers; 

use App\Models\User; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PegawaiController extends Controller
{
    /**
     * Menampilkan daftar semua pegawai.
     */
    public function index(Request $request)
    {
        // Tampilkan semua kecuali 'mitra' (Agar admin, pegawai, ppk, kepala_bps muncul)
        $query = User::where('role', '!=', 'mitra'); 

        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('username', 'like', '%' . $searchTerm . '%')
                  ->orWhere('nip', 'like', '%' . $searchTerm . '%')
                  ->orWhere('nama', 'like', '%' . $searchTerm . '%'); 
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
            'nama'     => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6'], 
            'nip'      => ['nullable', 'string', 'max:255', 'unique:users'],
            'fungsi'   => ['nullable', 'string', 'max:255'],
            'role'     => ['required', Rule::in(['admin', 'pegawai', 'ppk', 'kepala_bps'])], 
        ];

        $messages = [
            'nama.required'     => 'Nama pegawai wajib diisi.',
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
            'nama'     => $request->nama, 
            'username' => $request->username,
            'password' => Hash::make($request->password), 
            'nip'      => $request->nip,
            'fungsi'   => $request->fungsi,
            'role'     => $request->role, 
        ]);

        return redirect()->route('pegawai.index')->with('success', 'Data pegawai berhasil ditambahkan.');
    }

    /**
     * Memperbarui data pegawai.
     */
    public function update(Request $request, User $pegawai) 
    {
        $request->validate([
            'nama'     => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', Rule::unique('users', 'username')->ignore($pegawai->id_user ?? $pegawai->id, 'id_user')],
            'nip'      => ['nullable', 'string', 'max:255', Rule::unique('users', 'nip')->ignore($pegawai->id_user ?? $pegawai->id, 'id_user')],
            'fungsi'   => ['nullable', 'string', 'max:255'],
            'role'     => ['required', Rule::in(['admin', 'pegawai', 'ppk', 'kepala_bps'])], 
        ]);

        $updateData = [
            'nama'     => $request->nama, 
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

        return redirect()->route('pegawai.index')->with('success', 'Data pegawai berhasil diperbarui.');
    }

    /**
     * Menghapus data pegawai.
     */
    public function destroy(User $pegawai)
    {
        $pegawai->delete();
        return redirect()->route('pegawai.index')->with('success', 'Data pegawai berhasil dihapus.');
    }
}