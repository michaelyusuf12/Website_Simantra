<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penugasan;
use App\Models\Mitra;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MitraPanelController extends Controller
{
    // ARRAY NAMA BULAN
    private $bulanIndo = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    ];

// 1. FUNGSI HALAMAN BERANDA MITRA
    public function beranda(Request $request)
    {
        $bulanFilter = $request->input('month', date('n')); 
        $namaBulan = $this->bulanIndo[(int)$bulanFilter];
        $tahunSaatIni = date('Y');

        // Cari data profil Mitra berdasarkan User yang sedang login
        $user = \Illuminate\Support\Facades\Auth::user();
        $mitra = \App\Models\Mitra::where('id_user', $user->id_user ?? $user->id)->first();

        // Variabel Default jika data kosong
        $totalHonor = 0;
        $jumlahKegiatan = 0;
        $paguMaksimum = 0;
        
        // Variabel penampung untuk Grafik Chart.js
        $labelBulanChart = [];
        $dataHonorChart = [];

        if ($mitra) {
            // A. Hitung Honor & Kegiatan bulan terpilih
            $queryPenugasan = \App\Models\Penugasan::where('mitra_id', $mitra->sobat_id)
                                        ->where('bulan_kegiatan', $namaBulan)
                                        ->where('status_kontrak', '!=', 'Ditolak');

            $totalHonor = $queryPenugasan->sum('total_nilai_perjanjian');
            $jumlahKegiatan = $queryPenugasan->count();

            // B. Ambil Batas Pagu
            if ($mitra->posisi_petugas == 3) {
                $setting = \Illuminate\Support\Facades\DB::table('settings')->orderByDesc('batas_honor')->first();
            } else {
                $setting = \Illuminate\Support\Facades\DB::table('settings')->where('posisi_kode', $mitra->posisi_petugas)->first();
            }
            $paguMaksimum = $setting ? $setting->batas_honor : 0;

            // ========================================================
            // C. LOGIKA GRAFIK: Ambil Data 6 Bulan Terakhir
            // ========================================================
            $bulanSekarang = date('n'); // Angka bulan saat ini (misal: Mei = 5)
            
            // Looping mundur dari 5 sampai 0 (untuk dapat 6 bulan ke belakang)
            for ($i = 5; $i >= 0; $i--) {
                $angkaBulanLoop = $bulanSekarang - $i;
                
                // Jika hasil pengurangan nol atau minus, berarti mundur ke tahun lalu (Desember, dst)
                if ($angkaBulanLoop <= 0) {
                    $angkaBulanLoop += 12; 
                }
                
                $namaBulanLoop = $this->bulanIndo[$angkaBulanLoop];
                
                // 1. Masukkan nama bulan ke dalam array Label
                $labelBulanChart[] = $namaBulanLoop;

                // 2. Hitung total uang di bulan tersebut lalu masukkan ke array Data
                $totalPerBulan = \App\Models\Penugasan::where('mitra_id', $mitra->sobat_id)
                    ->where('bulan_kegiatan', $namaBulanLoop)
                    ->where('status_kontrak', '!=', 'Ditolak')
                    ->sum('total_nilai_perjanjian');

                $dataHonorChart[] = $totalPerBulan;
            }
        }

        // Tambahkan $labelBulanChart dan $dataHonorChart ke dalam compact()
        return view('mitra.beranda', compact(
            'bulanFilter', 'namaBulan', 'tahunSaatIni', 
            'totalHonor', 'jumlahKegiatan', 'paguMaksimum', 
            'labelBulanChart', 'dataHonorChart'
        ));
    }

 // 2. FUNGSI HALAMAN RIWAYAT MITRA
    public function riwayat(Request $request)
    {
        $bulanFilter = $request->input('month', date('n')); 
        $namaBulan = $this->bulanIndo[(int)$bulanFilter];
        $tahunSaatIni = date('Y');

        $user = \Illuminate\Support\Facades\Auth::user();
        $mitra = \App\Models\Mitra::where('id_user', $user->id_user ?? $user->id)->first();

        $riwayatPenugasan = collect();

        if ($mitra) {
            // A. Mulai Query dasar (Tanpa dibatasi status 'Disetujui' agar badge status berfungsi)
            $query = \App\Models\Penugasan::with('details.kegiatan')
                ->where('mitra_id', $mitra->sobat_id)
                ->where('bulan_kegiatan', $namaBulan);

            // B. FITUR PENCARIAN SAKTI (Berdasarkan No Surat ATAU Nama Kegiatan)
            if ($request->has('search') && $request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    // 1. Cari kecocokan di Nomor Surat (Tabel Penugasan)
                    $q->where('no_surat', 'like', "%{$search}%")
                      // 2. ATAU Cari kecocokan di Nama Kegiatan (Menembus relasi ke Tabel Kegiatan)
                      ->orWhereHas('details.kegiatan', function($qKegiatan) use ($search) {
                          // Mengecek dua penulisan kolom untuk berjaga-jaga
                          $qKegiatan->where('Nama_kegiatan', 'like', "%{$search}%")
                                    ->orWhere('nama_kegiatan', 'like', "%{$search}%");
                      });
                });
            }

            // C. Eksekusi Query
            $riwayatPenugasan = $query->latest()->get();
        }

        return view('mitra.riwayat', compact('bulanFilter', 'namaBulan', 'tahunSaatIni', 'riwayatPenugasan'));
    }
}