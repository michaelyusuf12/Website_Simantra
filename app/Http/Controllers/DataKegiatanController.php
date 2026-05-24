<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth; 

class DataKegiatanController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Kegiatan::query();

        // ========================================================
        // PEMBATASAN DATA BERDASARKAN FUNGSI PEGAWAI
        // ========================================================
        if ($user->role === 'pegawai' && !empty($user->fungsi)) {
            // Pegawai HANYA bisa melihat kegiatan milik fungsinya sendiri
            $query->where('fungsi', $user->fungsi);
        }

        // Fitur Pencarian
        if ($request->has('search') && $request->filled('search')) {
            $searchTerm = $request->search;
            
            // Bungkus search query dengan fungsi agar tidak menabrak filter fungsi di atas
            $query->where(function($q) use ($searchTerm) {
                $q->where('nama_kegiatan', 'like', '%' . $searchTerm . '%')
                  ->orWhere('kode_kegiatan', 'like', '%' . $searchTerm . '%');
            });
        }

        $kegiatans = $query->latest()->paginate(10)->appends($request->query());
        
        return view('admin.datakegiatan.index', compact('kegiatans')); 
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        // ========================================================
        // VALIDASI FUNGSI SAAT INPUT DATA
        // ========================================================
        $fungsiYangDisimpan = $request->fungsi;
        if ($user->role === 'pegawai') {
            $fungsiYangDisimpan = $user->fungsi; // Paksa simpan sesuai fungsi milik pegawai
        }

        $validatedData = $request->validate([
            'kode_kegiatan' => 'required|unique:kegiatans,kode_kegiatan',
            'nama_kegiatan' => 'required|string|max:255',
            'penanggung_jawab' => 'nullable|string|max:255',
            'nama_tim' => 'nullable|string|max:255',
            'target_dokumen' => 'nullable|integer|min:0',
            'fungsi' => $user->role === 'admin' ? 'required|string' : 'nullable', 
            // [PERBAIKAN] Diubah menjadi nullable karena formnya sudah dihapus
            'jenis_kegiatan' => 'nullable|in:Lapangan,Pengolahan,Lapangan & Pengolahan', 
            'tgl_mulai' => 'nullable|date',
            'tgl_selesai' => 'nullable|date|after_or_equal:tgl_mulai',
            'honor_pml_per_dokumen' => 'required|numeric|min:0',
            'honor_pcl_per_dokumen' => 'required|numeric|min:0',
            'honor_pengolahan_per_dokumen' => 'required|numeric|min:0',
        ]);

        // Timpa nilai fungsinya
        $validatedData['fungsi'] = $fungsiYangDisimpan;

        Kegiatan::create($validatedData);

        return redirect()->route('datakegiatan.index')
                         ->with('success', 'Data kegiatan berhasil ditambahkan.');
    }

    public function update(Request $request, Kegiatan $datakegiatan)
    {
        $user = Auth::user();

        // ========================================================
        // VALIDASI KEAMANAN (Cegah Pegawai Edit Fungsi Lain)
        // ========================================================
        if ($user->role === 'pegawai' && $datakegiatan->fungsi !== $user->fungsi) {
            return redirect()->route('datakegiatan.index')->with('error', 'Akses Ditolak! Anda tidak dapat mengedit kegiatan dari fungsi lain.');
        }

        $fungsiYangDisimpan = $request->fungsi;
        if ($user->role === 'pegawai') {
            $fungsiYangDisimpan = $user->fungsi; 
        }

        $validatedData = $request->validate([
            'kode_kegiatan' => ['required', Rule::unique('kegiatans', 'kode_kegiatan')->ignore($datakegiatan->id_kegiatan, 'id_kegiatan')],
            'nama_kegiatan' => 'required|string|max:255',
            'penanggung_jawab' => 'nullable|string|max:255',
            'nama_tim' => 'nullable|string|max:255',
            'target_dokumen' => 'nullable|integer|min:0',
            'fungsi' => $user->role === 'admin' ? 'required|string' : 'nullable',
            // [PERBAIKAN] Diubah menjadi nullable karena formnya sudah dihapus
            'jenis_kegiatan' => 'nullable|in:Lapangan,Pengolahan,Lapangan & Pengolahan',
            'tgl_mulai' => 'nullable|date',
            'tgl_selesai' => 'nullable|date|after_or_equal:tgl_mulai',
            'honor_pml_per_dokumen' => 'required|numeric|min:0',
            'honor_pcl_per_dokumen' => 'required|numeric|min:0',
            'honor_pengolahan_per_dokumen' => 'required|numeric|min:0',
        ]);

        $validatedData['fungsi'] = $fungsiYangDisimpan;

        $datakegiatan->update($validatedData);

        return redirect()->route('datakegiatan.index')
                         ->with('success', 'Data kegiatan berhasil diperbarui.');
    }

    public function destroy(Kegiatan $datakegiatan)
    {
        $user = Auth::user();

        // ========================================================
        // VALIDASI KEAMANAN HAPUS DATA
        // ========================================================
        if ($user->role === 'pegawai' && $datakegiatan->fungsi !== $user->fungsi) {
            return redirect()->route('datakegiatan.index')->with('error', 'Akses Ditolak! Anda tidak berhak menghapus kegiatan ini.');
        }

        $datakegiatan->delete();
        
        return redirect()->route('datakegiatan.index')
                         ->with('success', 'Data kegiatan berhasil dihapus.');
    }
}