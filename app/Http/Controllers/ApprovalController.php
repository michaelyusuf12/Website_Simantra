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

        // --- (PAGINATION) ---
        $penugasans = $query->latest()->paginate(10)->appends($request->query());
        
        return view('ppk.approval', compact('penugasans', 'bulanFilter', 'statusFilter', 'search'));
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

    // 5. EXPORT EXCEL (REVISI BPS)
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

        // LOGIKA PEMBENTUKAN FILE EXCEL (CSV)
        $fileName = 'Laporan_Mitra_' . $namaBulan . '_' . ucfirst($statusFilter) . '.csv';

        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use($penugasans) {
            $file = fopen('php://output', 'w');
            
            // Tambahkan BOM agar karakter terbaca sempurna di Microsoft Excel
            fputs($file, "\xEF\xBB\xBF");
            
            // Judul Kolom (Header Excel)
            fputcsv($file, ['No', 'Tanggal Pengajuan', 'No. Draf / SPK', 'Nama Mitra', 'Nama Kegiatan', 'Nominal Honor', 'Status Dokumen'], ';');

            // Isi Data
            $no = 1;
            foreach ($penugasans as $p) {
                $tgl = \Carbon\Carbon::parse($p->created_at)->locale('id')->translatedFormat('d F Y');
                
                // Ambil nama kegiatan
                $kegiatan = '-';
                if ($p->details && $p->details->count() > 0) {
                    $kegiatan = $p->details->first()->kegiatan->Nama_kegiatan ?? $p->details->first()->kegiatan->nama_kegiatan ?? '-';
                    if ($p->details->count() > 1) {
                        $kegiatan .= ' (+'.($p->details->count() - 1).' lainnya)';
                    }
                }

                // Masukkan baris data ke Excel
                fputcsv($file, [
                    $no++,
                    $tgl,
                    $p->no_surat ?? 'Belum ada nomor',
                    $p->mitra->nama_petugas ?? '-',
                    $kegiatan,
                    $p->total_nilai_perjanjian,
                    $p->status_kontrak
                ], ';');
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // 6. FUNGSI BARU: Menampilkan Detail untuk Modal Preview PPK
    public function show($id)
    {
        try {
            // [REVISI] Hanya mencari berdasarkan id_penugasan agar tidak error SQL
            $penugasan = Penugasan::with(['mitra', 'details.kegiatan'])
                                  ->where('id_penugasan', $id)
                                  ->first();
            
            if (!$penugasan) {
                return response()->json([
                    'status' => 'error',
                    'message' => "Data penugasan tidak ditemukan."
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'data' => $penugasan
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}