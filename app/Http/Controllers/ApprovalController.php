<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penugasan;
use Barryvdh\DomPDF\Facade\Pdf; // 🚨 Tambahkan ini untuk fitur PDF

class ApprovalController extends Controller
{
    // 1. Fungsi Menampilkan Halaman Approval
    public function index(Request $request)
    {
        $bulanFilter = $request->input('month', date('n')); 
        $statusFilter = $request->input('status', 'menunggu');
        $search = $request->input('search');

        $bulanIndo = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        $namaBulan = $bulanIndo[(int)$bulanFilter];

        $query = Penugasan::with(['mitra', 'details.kegiatan'])
                          ->where('bulan_kegiatan', $namaBulan);

        // Menyamakan ejaan persis dengan Database
        if ($statusFilter === 'menunggu') {
            $query->where('status_kontrak', 'Menunggu Approval'); 
        } elseif ($statusFilter === 'disetujui') {
            $query->where('status_kontrak', 'Disetujui');
        } elseif ($statusFilter === 'ditolak') {
            $query->where('status_kontrak', 'Ditolak');
        }

        // Filter Pencarian
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->whereHas('mitra', function($q2) use ($search) {
                    $q2->where('nama_petugas', 'like', '%' . $search . '%');
                })
                ->orWhereHas('details.kegiatan', function($q3) use ($search) {
                    $q3->where('nama_kegiatan', 'like', '%' . $search . '%')
                       ->orWhere('Nama_kegiatan', 'like', '%' . $search . '%');
                });
            });
        }

        $penugasans = $query->latest()->get();

        return view('kepala_bps.approval', compact('penugasans', 'bulanFilter', 'statusFilter', 'search'));
    }

    // 2. Fungsi Approve
    public function approve($id)
    {
        Penugasan::where('id_penugasan', $id)->update(['status_kontrak' => 'Disetujui']);
        return back()->with('success', 'Dokumen SPK berhasil disetujui!');
    }

    // 3. Fungsi Reject
    public function reject($id)
    {
        Penugasan::where('id_penugasan', $id)->update(['status_kontrak' => 'Ditolak']);
        return back()->with('success', 'Dokumen SPK telah ditolak.');
    }

    // 4. Fungsi Bulk Approve
    public function bulkApprove(Request $request)
    {
        $ids = $request->input('ids'); 

        if (!$ids || count($ids) === 0) {
            return back()->with('error', 'Mohon pilih setidaknya satu dokumen.');
        }

        Penugasan::whereIn('id_penugasan', $ids)->update(['status_kontrak' => 'Disetujui']);

        return back()->with('success', count($ids) . ' dokumen berhasil disetujui sekaligus!');
    }

    // 5. 🚨 FUNGSI BARU: CETAK LAPORAN PDF
    public function cetakLaporan(Request $request)
    {
        $bulanFilter = $request->input('month', date('n')); 
        $statusFilter = $request->input('status', 'menunggu');
        $search = $request->input('search');

        $bulanIndo = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        $namaBulan = $bulanIndo[(int)$bulanFilter];

        // Query persis sama seperti di index agar data yang dicetak = data yang dilihat di layar
        $query = Penugasan::with(['mitra', 'details.kegiatan'])
                          ->where('bulan_kegiatan', $namaBulan);

        if ($statusFilter === 'menunggu') {
            $query->where('status_kontrak', 'Menunggu Approval'); 
        } elseif ($statusFilter === 'disetujui') {
            $query->where('status_kontrak', 'Disetujui');
        } elseif ($statusFilter === 'ditolak') {
            $query->where('status_kontrak', 'Ditolak');
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->whereHas('mitra', function($q2) use ($search) {
                    $q2->where('nama_petugas', 'like', '%' . $search . '%');
                })
                ->orWhereHas('details.kegiatan', function($q3) use ($search) {
                    $q3->where('nama_kegiatan', 'like', '%' . $search . '%')
                       ->orWhere('Nama_kegiatan', 'like', '%' . $search . '%');
                });
            });
        }

        $penugasans = $query->latest()->get();
        $totalHonor = $penugasans->sum('total_nilai_perjanjian');

        // Memanggil View PDF
        $pdf = Pdf::loadView('kepala_bps.cetak_laporan', compact('penugasans', 'namaBulan', 'statusFilter', 'totalHonor'))
                  ->setPaper('a4', 'landscape'); // Format landscape agar tabel muat banyak

        // Stream akan membuka PDF di tab baru, bukan langsung download
        return $pdf->stream('Laporan_Mitra_'.$namaBulan.'_'.ucfirst($statusFilter).'.pdf');
    }
}