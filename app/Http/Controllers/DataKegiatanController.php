<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DataKegiatanController extends Controller
{
    public function index(Request $request)
    {
        $query = Kegiatan::query();

        if ($request->has('search') && $request->filled('search')) {
            $searchTerm = $request->search;
            $query->where('nama_kegiatan', 'like', '%' . $searchTerm . '%')
                  ->orWhere('kode_kegiatan', 'like', '%' . $searchTerm . '%');
        }

        $kegiatans = $query->latest()->paginate(10)->appends($request->query());
        
        // Pastikan nama folder view-nya benar (admin/datakegiatan/index atau admin/kegiatan/index)
        // Jika file Anda ada di resources/views/admin/kegiatan/index.blade.php, ubah ini menjadi 'admin.kegiatan.index'
        return view('admin.datakegiatan.index', compact('kegiatans')); 
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'kode_kegiatan' => 'required|unique:kegiatans,kode_kegiatan',
            'nama_kegiatan' => 'required|string|max:255',
            'penanggung_jawab' => 'nullable|string|max:255',
            'nama_tim' => 'nullable|string|max:255',
            'target_dokumen' => 'nullable|integer|min:0',
            'fungsi' => 'required|string',
            'jenis_kegiatan' => 'required|in:Lapangan,Pengolahan', 
            'tgl_mulai' => 'required|date',
            'tgl_selesai' => 'required|date|after_or_equal:tgl_mulai',
            'honor_pml_per_dokumen' => ['required_if:jenis_kegiatan,Lapangan', 'nullable', 'numeric', 'min:0'],
            'honor_pcl_per_dokumen' => ['required_if:jenis_kegiatan,Lapangan', 'nullable', 'numeric', 'min:0'],
            'honor_pengolahan_per_dokumen' => ['required_if:jenis_kegiatan,Pengolahan', 'nullable', 'numeric', 'min:0'],
        ]);

        if ($validatedData['jenis_kegiatan'] === 'Lapangan') {
            $validatedData['honor_pengolahan_per_dokumen'] = 0;
        } elseif ($validatedData['jenis_kegiatan'] === 'Pengolahan') {
            $validatedData['honor_pml_per_dokumen'] = 0;
            $validatedData['honor_pcl_per_dokumen'] = 0;
        }

        Kegiatan::create($validatedData);

        // PERBAIKAN 1: Samakan nama rute dengan yang ada di web.php
        return redirect()->route('datakegiatan.index')
                         ->with('success', 'Data kegiatan berhasil ditambahkan.');
    }

    public function update(Request $request, Kegiatan $datakegiatan)
    {
        $validatedData = $request->validate([
            // PERBAIKAN 2: Ubah $datakegiatan->id menjadi id_kegiatan dan tambahkan nama kolomnya
            'kode_kegiatan' => ['required', Rule::unique('kegiatans', 'kode_kegiatan')->ignore($datakegiatan->id_kegiatan, 'id_kegiatan')],
            'nama_kegiatan' => 'required|string|max:255',
            'penanggung_jawab' => 'nullable|string|max:255',
            'nama_tim' => 'nullable|string|max:255',
            'target_dokumen' => 'nullable|integer|min:0',
            'fungsi' => 'required|string',
            'jenis_kegiatan' => 'required|in:Lapangan,Pengolahan',
            'tgl_mulai' => 'required|date',
            'tgl_selesai' => 'required|date|after_or_equal:tgl_mulai',
            'honor_pml_per_dokumen' => ['required_if:jenis_kegiatan,Lapangan', 'nullable', 'numeric', 'min:0'],
            'honor_pcl_per_dokumen' => ['required_if:jenis_kegiatan,Lapangan', 'nullable', 'numeric', 'min:0'],
            'honor_pengolahan_per_dokumen' => ['required_if:jenis_kegiatan,Pengolahan', 'nullable', 'numeric', 'min:0'],
        ]);

        if ($validatedData['jenis_kegiatan'] === 'Lapangan') {
            $validatedData['honor_pengolahan_per_dokumen'] = 0;
        } elseif ($validatedData['jenis_kegiatan'] === 'Pengolahan') {
            $validatedData['honor_pml_per_dokumen'] = 0;
            $validatedData['honor_pcl_per_dokumen'] = 0;
        }

        $datakegiatan->update($validatedData);

        // PERBAIKAN 1: Samakan nama rute
        return redirect()->route('datakegiatan.index')
                         ->with('success', 'Data kegiatan berhasil diperbarui.');
    }

    public function destroy(Kegiatan $datakegiatan)
    {
        $datakegiatan->delete();
        // PERBAIKAN 1: Samakan nama rute
        return redirect()->route('datakegiatan.index')
                         ->with('success', 'Data kegiatan berhasil dihapus.');
    }
}