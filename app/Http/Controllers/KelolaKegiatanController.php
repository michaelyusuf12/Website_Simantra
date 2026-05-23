<?php

namespace App\Http\Controllers;

use App\Models\Penugasan;
use App\Models\DetailPenugasan;
use App\Models\Mitra;
use App\Models\Kegiatan;
use App\Models\Setting; 
use App\Models\User; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth; // [REVISI] Tambahkan facade Auth
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class KelolaKegiatanController extends Controller
{
    /**
     * FUNGSI INDEX: Menampilkan halaman utama Kelola Penugasan (Lengkap dengan Filter & Search)
     */
    public function index(Request $request)
    {
        $user = Auth::user(); // Ambil data user yang sedang login
        
        // 1. Mulai Query Data (Eager Loading)
        $query = Penugasan::with(['mitra', 'details.kegiatan']);

        // 2. FILTER BULAN 
        if ($request->filled('bulan')) {
            $query->where('bulan_kegiatan', $request->bulan);
        }

        // --- [REVISI] 3. FILTER FUNGSI & KEGIATAN ---
        if ($request->filled('fungsi')) {
            $query->whereHas('details.kegiatan', function($q) use ($request) {
                $q->where('fungsi', $request->fungsi);
            });
        }
        
        if ($request->filled('kegiatan')) {
            $query->whereHas('details', function($q) use ($request) {
                $q->where('id_kegiatan', $request->kegiatan);
            });
        }
        // ---------------------------------------------

        // 4. FITUR PENCARIAN (Berdasarkan No Surat ATAU Nama Mitra)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('no_surat', 'like', "%{$search}%")
                  ->orWhereHas('mitra', function($qMitra) use ($search) {
                      $qMitra->where('nama_petugas', 'like', "%{$search}%");
                  });
            });
        }

        // 5. Eksekusi Query
        $penugasans = $query->latest()->paginate(10)->appends($request->query());

        $mitras = Mitra::orderBy('nama_petugas')->get();
        
        // --- [REVISI] 6. PEMBATASAN KEGIATAN BERDASARKAN FUNGSI PEGAWAI ---
        if ($user->role == 'pegawai' && !empty($user->fungsi)) {
            // Pegawai hanya bisa melihat kegiatan sesuai fungsinya sendiri
            $kegiatans = Kegiatan::where('fungsi', $user->fungsi)->orderBy('nama_kegiatan')->get();
        } else {
            // Admin/PPK bisa melihat semua
            $kegiatans = Kegiatan::orderBy('nama_kegiatan')->get();
        }
        // -----------------------------------------------------------------
        
        $daftarBulan = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];
        
        // Mengirim data semua fungsi unik yang ada di tabel kegiatan untuk dropdown filter
        $daftarFungsi = Kegiatan::select('fungsi')->distinct()->pluck('fungsi');

        return view('pegawai.kelolakegiatan.index', compact('penugasans', 'mitras', 'kegiatans', 'daftarBulan', 'daftarFungsi'));
    }

    /**
     * FUNGSI STORE: Menyimpan data penugasan baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'no_surat' => 'required|string|max:255', // [REVISI] No Surat sekarang diisi manual
            'mitra_id' => 'required',
            'bulan_penugasan' => 'required',
            'kegiatan_id' => 'required|array',
            'volume' => 'required|array',
            'peran' => 'required|array',
        ]);

        try {
            DB::beginTransaction();

            $mitraId = $request->mitra_id;
            $namaBulan = $request->bulan_penugasan;
            $tahun = Carbon::now()->year;
            $totalNilaiPerjanjian = 0;
            
            // 1. Simpan Data Induk (Master Penugasan)
            $penugasan = new Penugasan();
            $penugasan->no_surat = $request->no_surat; // [REVISI] Simpan inputan manual
            $penugasan->tanggal_surat = Carbon::now();
            $penugasan->mitra_id = $mitraId;
            $penugasan->bulan_kegiatan = $namaBulan;
            $penugasan->tahun_anggaran = $tahun; 
            $penugasan->total_nilai_perjanjian = 0; 
            $penugasan->status_kontrak = 'Menunggu Approval';
            $penugasan->save();

            // 2. Simpan Data Rincian (Detail Penugasan)
            for ($i = 0; $i < count($request->kegiatan_id); $i++) {
                $keg = Kegiatan::where('id_kegiatan', $request->kegiatan_id[$i])->first() ?? Kegiatan::find($request->kegiatan_id[$i]);
                if (!$keg) continue; 

                $peran = $request->peran[$i];
                $harga = 0;
                
                if($peran == 'PCL') {
                    $harga = $keg->honor_pcl_per_dokumen;
                } elseif($peran == 'PML') {
                    $harga = $keg->honor_pml_per_dokumen;
                } else {
                    $harga = $keg->honor_pengolahan_per_dokumen;
                }

                $harga = $harga ?? 0; 
                $subtotal = $harga * $request->volume[$i];
                $totalNilaiPerjanjian += $subtotal; 

                DetailPenugasan::create([
                    'id_penugasan' => $penugasan->id_penugasan ?? $penugasan->id, 
                    'id_kegiatan' => $request->kegiatan_id[$i],
                    'uraian_tugas' => $peran,
                    'volume' => $request->volume[$i],
                    'harga_satuan' => $harga,
                    'satuan' => $request->satuan[$i] ?? 'Dokumen',
                    'tanggal_mulai'   => $request->tanggal_mulai[$i],
                    'tanggal_selesai' => $request->tanggal_selesai[$i],
                ]);
            }

            // 3. Update Total Keseluruhan
            $penugasan->update(['total_nilai_perjanjian' => $totalNilaiPerjanjian]);

            DB::commit();
            return response()->json(['status' => 'success', 'redirect' => route('kelolakegiatan.index')]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
    
    /**
     * FUNGSI DESTROY: Menghapus data penugasan
     */
    public function destroy($id)
    {
        $penugasan = Penugasan::where('id_penugasan', $id)->firstOrFail(); 
        $penugasan->delete(); 
        
        return redirect()->route('kelolakegiatan.index')->with('success', 'Data Surat Tugas berhasil dihapus.');
    }

    /**
     * FUNGSI UPDATE: Menyimpan perubahan data penugasan
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'no_surat' => 'required|string|max:255', // [REVISI] Wajib isi manual no surat
            'mitra_id' => 'required',
            'bulan_penugasan' => 'required',
            'kegiatan_id' => 'required|array',
            'volume' => 'required|array',
            'peran' => 'required|array',
        ]);

        try {
            DB::beginTransaction();

            $penugasan = Penugasan::where('id_penugasan', $id)->firstOrFail();
            
            // 1. Update Master Penugasan
            $penugasan->no_surat = $request->no_surat; // [REVISI]
            $penugasan->mitra_id = $request->mitra_id;
            $penugasan->bulan_kegiatan = $request->bulan_penugasan;
            $penugasan->total_nilai_perjanjian = 0;
            $penugasan->save();

            // 2. Hapus detail lama agar bersih dan tidak duplikat
            DetailPenugasan::where('id_penugasan', $id)->delete();

            $totalNilaiPerjanjian = 0;

            // 3. Masukkan detail yang baru
            for ($i = 0; $i < count($request->kegiatan_id); $i++) {
                $keg = Kegiatan::where('id_kegiatan', $request->kegiatan_id[$i])->first() ?? Kegiatan::find($request->kegiatan_id[$i]);
                if (!$keg) continue;

                $peran = $request->peran[$i];
                $harga = 0;

                if ($peran == 'PCL') {
                    $harga = $keg->honor_pcl_per_dokumen;
                } elseif ($peran == 'PML') {
                    $harga = $keg->honor_pml_per_dokumen;
                } else {
                    $harga = $keg->honor_pengolahan_per_dokumen;
                }

                $harga = $harga ?? 0;
                $subtotal = $harga * $request->volume[$i];
                $totalNilaiPerjanjian += $subtotal;

                DetailPenugasan::create([
                    'id_penugasan' => $penugasan->id_penugasan,
                    'id_kegiatan' => $request->kegiatan_id[$i],
                    'uraian_tugas' => $peran,
                    'volume' => $request->volume[$i],
                    'harga_satuan' => $harga,
                    'satuan' => $request->satuan[$i] ?? 'Dokumen',
                    'tanggal_mulai' => $request->tanggal_mulai[$i],
                    'tanggal_selesai' => $request->tanggal_selesai[$i],
                ]);
            }

            // 4. Update kembali total harganya
            $penugasan->update(['total_nilai_perjanjian' => $totalNilaiPerjanjian]);

            DB::commit();
            return response()->json(['status' => 'success', 'redirect' => route('kelolakegiatan.index')]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * FUNGSI CEK AKUMULASI (VALIDASI BUSINESS LOGIC BPS)
     */
public function cekAkumulasi(Request $request)
    {
        try {
            $mitraId = $request->mitra_id;
            $bulan = $request->bulan;
            $tahun = Carbon::now()->year;

            $settingLapangan = Setting::where('tahun', $tahun)->where('posisi_kode', '1')->first();
            $settingPengolahan = Setting::where('tahun', $tahun)->where('posisi_kode', '2')->first();

            $limitLapanganDB = $settingLapangan ? $settingLapangan->batas_honor : 3258000;
            $limitPengolahanDB = $settingPengolahan ? $settingPengolahan->batas_honor : 3108000;

            // Default limit
            $limitMaksimal = $limitLapanganDB; 

            // [PERBAIKAN 1] Cari berdasarkan Primary Key (id_mitra/id) atau sobat_id
            $mitra = Mitra::find($mitraId) ?? Mitra::where('sobat_id', $mitraId)->first();

            if ($mitra) {
                $nilaiPosisi = (string) $mitra->posisi_petugas; 
                if ($nilaiPosisi === '1' || strtolower($nilaiPosisi) == 'lapangan') {
                    $limitMaksimal = $limitLapanganDB;
                } elseif ($nilaiPosisi === '2' || strtolower($nilaiPosisi) == 'pengolahan') {
                    $limitMaksimal = $limitPengolahanDB;
                } elseif ($nilaiPosisi === '3' || strtolower($nilaiPosisi) == 'lapangan & pengolahan') {
                    $limitMaksimal = $limitPengolahanDB; // Kategori campuran wajib pakai limit Pengolahan
                }
            }
            
            $query = Penugasan::where('mitra_id', $mitraId)
                              ->where('bulan_kegiatan', $bulan)
                              ->whereYear('tanggal_surat', $tahun);
            
            if ($request->has('penugasan_id') && $request->penugasan_id != '') {
                $query->where('id_penugasan', '!=', $request->penugasan_id);
            }
            
            $akumulasi = $query->sum('total_nilai_perjanjian');
            $akumulasiAman = $akumulasi ? (int) $akumulasi : 0;

            return response()->json([
                'status' => 'success',
                'akumulasi' => $akumulasiAman,
                'limit_maksimal' => $limitMaksimal
            ]);

        } catch (\Throwable $e) { // [PERBAIKAN 2] Gunakan Throwable agar error fatal PHP juga tertangkap
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function show($id)
    {
        try {
            $penugasan = Penugasan::with(['mitra', 'details.kegiatan'])->find($id);
            
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

    public function cetak($id)
    {
        $penugasan = Penugasan::with(['mitra', 'details.kegiatan'])->findOrFail($id);
        $pejabat = \App\Models\User::where('role', 'ppk')->first(); // [REVISI] Ganti pencarian role ke ppk

        $pdf = Pdf::loadView('pegawai.kelolakegiatan.cetak', compact('penugasan', 'pejabat'))
                  ->setPaper('a4', 'portrait'); 

        $nama_file_aman = 'Surat_Tugas_' . str_replace('/', '_', $penugasan->no_surat) . '.pdf';

        return $pdf->stream($nama_file_aman);
    }

    /**
     * --- [FUNGSI BARU] ---
     * FUNGSI EXPORT EXCEL: Mengekspor data yang di-checklis ke dalam file format Excel/CSV
     */
    public function exportExcel(Request $request)
    {
        $ids = $request->ids; // Array ID penugasan yang dicentang
        
        if (!$ids || empty($ids)) {
            return back()->with('error', 'Silakan pilih minimal satu data untuk diekspor!');
        }

        // Ambil data penugasan berdasarkan ID yang dipilih
        $penugasans = Penugasan::with(['mitra', 'details.kegiatan'])->whereIn('id_penugasan', $ids)->get();

        // Buat nama file
        $fileName = "Export_Data_Penugasan_" . date('Ymd_His') . ".csv";

        // Setup Header untuk memaksa browser mengunduh file
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        // Kolom Header Excel
        $columns = array('No', 'No Surat Tugas', 'Nama Mitra', 'Bulan', 'Rincian Kegiatan', 'Total Honor (Rp)', 'Status');

        // Fungsi Callback untuk menulis data ke file stream
        $callback = function() use($penugasans, $columns) {
            $file = fopen('php://output', 'w');
            
            // Tambahkan UTF-8 BOM agar Excel bisa membaca karakter khusus dengan benar
            fputs($file, $bom =( chr(0xEF) . chr(0xBB) . chr(0xBF) ));
            
            // Tulis Header
            fputcsv($file, $columns, ';'); // Gunakan pemisah Titik Koma (;) agar rapi di Excel bahasa Indonesia

            $rowNo = 1;
            foreach ($penugasans as $task) {
                // Gabungkan semua kegiatan dalam 1 sel
                $kegiatanList = $task->details->map(function($d) {
                    $namaKeg = $d->kegiatan ? $d->kegiatan->nama_kegiatan : 'Kegiatan Dihapus';
                    return $namaKeg . ' (Peran: ' . $d->uraian_tugas . ', Vol: ' . $d->volume . ')';
                })->implode(" | ");

                $row = array(
                    $rowNo++,
                    $task->no_surat,
                    $task->mitra ? $task->mitra->nama_petugas : 'Mitra Dihapus',
                    $task->bulan_kegiatan,
                    $kegiatanList,
                    $task->total_nilai_perjanjian,
                    $task->status_kontrak
                );
                
                fputcsv($file, $row, ';');
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}