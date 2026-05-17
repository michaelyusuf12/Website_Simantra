<?php

namespace App\Http\Controllers;

use App\Models\Penugasan;
use App\Models\DetailPenugasan;
use App\Models\Mitra;
use App\Models\Kegiatan;
use App\Models\Setting; // [RAPIM] Ditambahkan agar tidak perlu panggil \App\Models\Setting di bawah
use App\Models\User;    // [RAPIM] Ditambahkan agar tidak perlu panggil \App\Models\User di bawah
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class KelolaKegiatanController extends Controller
{
    /**
     * FUNGSI INDEX: Menampilkan halaman utama Kelola Penugasan
     * Dosen mungkin bertanya: "Darimana data tabel ini berasal?"
     * Jawaban: "Dari model Penugasan, di-load bersama relasi mitra dan rincian kegiatannya menggunakan teknik Eager Loading (with) agar tidak membebani database (mencegah N+1 Query Problem)."
     */
 /**
     * FUNGSI INDEX: Menampilkan halaman utama Kelola Penugasan (Lengkap dengan Filter & Search)
     */
    public function index(Request $request)
    {
        // 1. Mulai Query Data (Eager Loading)
        $query = Penugasan::with(['mitra', 'details.kegiatan']);

        // 2. FILTER BULAN (Jika user memilih bulan tertentu di dropdown)
        if ($request->filled('bulan')) {
            $query->where('bulan_kegiatan', $request->bulan);
        }

        // 3. FITUR PENCARIAN (Berdasarkan No Surat ATAU Nama Mitra)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                // Cari di tabel penugasans (no_surat)
                $q->where('no_surat', 'like', "%{$search}%")
                  // ATAU cari di tabel mitras (nama_petugas) menggunakan whereHas
                  ->orWhereHas('mitra', function($qMitra) use ($search) {
                      $qMitra->where('nama_petugas', 'like', "%{$search}%");
                  });
            });
        }

        // 4. Eksekusi Query dan Pagination (appends query agar parameter URL tidak hilang saat pindah halaman)
        $penugasans = $query->latest()->paginate(10)->appends($request->query());

        // Data Master untuk Modal
        $mitras = Mitra::orderBy('nama_petugas')->get();
        $kegiatans = Kegiatan::orderBy('nama_kegiatan')->get();
        
        // Array Bulan
        $daftarBulan = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];
        
        return view('pegawai.kelolakegiatan.index', compact('penugasans', 'mitras', 'kegiatans', 'daftarBulan'));
    }

    /**
     * FUNGSI STORE: Menyimpan data penugasan baru (Master & Detail)
     * Dosen mungkin bertanya: "Bagaimana jika saat menyimpan rincian terjadi eror, apakah data suratnya tetap tersimpan?"
     * Jawaban: "Tidak Pak/Bu, saya menggunakan DB::beginTransaction(). Jika ada satu saja yang gagal, semua proses akan di-rollback (dibatalkan) sehingga database tetap konsisten."
     */
    public function store(Request $request)
    {
        // Validasi input dari user
        $request->validate([
            'mitra_id' => 'required',
            'bulan_penugasan' => 'required',
            'kegiatan_id' => 'required|array',
            'volume' => 'required|array',
            'peran' => 'required|array',
        ]);

        try {
            // Memulai transaksi database (Keamanan Data)
            DB::beginTransaction();

            $mitraId = $request->mitra_id;
            $namaBulan = $request->bulan_penugasan;
            $tahun = Carbon::now()->year;
            $totalNilaiPerjanjian = 0;

            // --- GENERATE NOMOR SURAT OTOMATIS ---
            $sobatIdSingkat = substr($mitraId, -5); // Ambil 5 digit terakhir id mitra
            $tanggal = Carbon::now()->format('d'); // Contoh: '06'
            $bulanAngka = Carbon::now()->format('m'); // Contoh: '05'
            $noSuratOtomatis = "SPK/" . $sobatIdSingkat . "/" . $tanggal . "/" . $bulanAngka . "/" . $tahun . "/" . rand(100,999);
            
            // 1. Simpan Data Induk (Master Penugasan)
            $penugasan = new Penugasan();
            $penugasan->no_surat = $noSuratOtomatis;
            $penugasan->tanggal_surat = Carbon::now();
            $penugasan->mitra_id = $mitraId;
            $penugasan->bulan_kegiatan = $namaBulan;
            $penugasan->tahun_anggaran = $tahun; 
            $penugasan->total_nilai_perjanjian = 0; // Diset 0 dulu, nanti diupdate
            $penugasan->status_kontrak = 'Menunggu Approval';
            $penugasan->save();

            // 2. Simpan Data Rincian (Detail Penugasan) - Looping sebanyak kegiatan yang dipilih
            for ($i = 0; $i < count($request->kegiatan_id); $i++) {
                
                $keg = Kegiatan::where('id_kegiatan', $request->kegiatan_id[$i])->first() ?? Kegiatan::find($request->kegiatan_id[$i]);
                if (!$keg) continue; 

                $peran = $request->peran[$i];
                $harga = 0;
                
                // Menentukan harga berdasarkan peran yang dipilih user
                if($peran == 'PCL') {
                    $harga = $keg->honor_pcl_per_dokumen;
                } elseif($peran == 'PML') {
                    $harga = $keg->honor_pml_per_dokumen;
                } else {
                    $harga = $keg->honor_pengolahan_per_dokumen;
                }

                $harga = $harga ?? 0; // Keamanan jika nilai di database kosong/null
                $subtotal = $harga * $request->volume[$i];
                $totalNilaiPerjanjian += $subtotal; // Akumulasi total honor SPK ini

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

            // 3. Update Total Keseluruhan di tabel Master
            $penugasan->update(['total_nilai_perjanjian' => $totalNilaiPerjanjian]);

            // Jika semua lancar, simpan permanen ke database
            DB::commit();
            
            // Kembalikan response JSON karena form dikirim via AJAX (Fetch API di Frontend)
            return response()->json(['status' => 'success', 'redirect' => route('kelolakegiatan.index')]);

        } catch (\Exception $e) {
            // Jika ada eror baris kode di atas, batalkan semua simpanan database
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
        $penugasan->delete(); // Karena sudah ada relasi di Model/DB, detailnya idealnya ikut terhapus (Cascade)
        
        return redirect()->route('kelolakegiatan.index')->with('success', 'Data penugasan berhasil dihapus.');
    }

    /**
     * FUNGSI UPDATE: Menyimpan perubahan data penugasan
     * Konsep update ini menggunakan metode: Hapus semua rincian lama, lalu masukkan rincian baru.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
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
            $penugasan->mitra_id = $request->mitra_id;
            $penugasan->bulan_kegiatan = $request->bulan_penugasan;
            $penugasan->total_nilai_perjanjian = 0;
            $penugasan->save();

            // 2. Hapus detail lama agar bersih dan tidak duplikat
            DetailPenugasan::where('id_penugasan', $id)->delete();

            $totalNilaiPerjanjian = 0;

            // 3. Masukkan detail yang baru (Sama seperti logika di store)
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
     * Dosen mungkin bertanya: "Bagaimana cara sistem tahu batas maksimal honor setiap mitra?"
     * Jawaban: "Sistem mengecek 'posisi_petugas' di tabel Mitra. Lalu mengambil setting batas honor dari database. Untuk posisi Campuran (3), sistem menggunakan fungsi min() untuk mengambil nilai terkecil antara batas Lapangan dan Pengolahan."
     */
    public function cekAkumulasi(Request $request)
    {
        try {
            $mitraId = $request->mitra_id;
            $bulan = $request->bulan;
            $tahun = Carbon::now()->year;

            // 1. Ambil data limit dari tabel Pengaturan (Settings)
            $settingLapangan = Setting::where('tahun', $tahun)->where('posisi_kode', '1')->first();
            $settingPengolahan = Setting::where('tahun', $tahun)->where('posisi_kode', '2')->first();

            // 2. Fallback Limit (Jaring Pengaman jika tabel setting kosong)
            $limitLapanganDB = $settingLapangan ? $settingLapangan->batas_honor : 3258000;
            $limitPengolahanDB = $settingPengolahan ? $settingPengolahan->batas_honor : 3108000;

            $limitMaksimal = $limitLapanganDB; // Default Awal

            // 3. Cari Posisi Mitra
            $mitra = Mitra::where('sobat_id', $mitraId)->first();

            // 4. Terapkan Aturan BPS
            if ($mitra) {
                $nilaiPosisi = (string) $mitra->posisi_petugas; 
                if ($nilaiPosisi === '1') {
                    $limitMaksimal = $limitLapanganDB;
                } elseif ($nilaiPosisi === '2') {
                    $limitMaksimal = $limitPengolahanDB;
                } elseif ($nilaiPosisi === '3') {
                    // Sesuai aturan BPS: Kategori 3 menggunakan nilai batas terkecil
                    $limitMaksimal = min($limitLapanganDB, $limitPengolahanDB);
                }
            }
            
            // 5. Hitung total honor yang sudah dicapai mitra di bulan tersebut
            $query = Penugasan::where('mitra_id', $mitraId)
                              ->where('bulan_kegiatan', $bulan)
                              ->whereYear('tanggal_surat', $tahun);
            
            // Jika sedang dalam mode Edit, total dari SPK yang diedit tidak ikut dihitung (dikecualikan)
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

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * FUNGSI SHOW: Mengambil detail satu SPK (Digunakan untuk Modal Detail & Edit via AJAX)
     */
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

    /**
     * FUNGSI CETAK: Menampilkan halaman PDF/Cetak Surat Perjanjian
     */
    public function cetak($id)
    {
    // 1. Ambil data penugasan lengkap dengan relasinya
    $penugasan = Penugasan::with(['mitra', 'details.kegiatan'])->findOrFail($id);
    
    // 2. Ambil data pejabat (sesuaikan dengan cara kamu mengambil data pejabat)
    $pejabat = \App\Models\User::where('role', 'Kepala')->first(); 

    // 3. Load view dan masukkan datanya
    $pdf = Pdf::loadView('pegawai.kelolakegiatan.cetak', compact('penugasan', 'pejabat'))
              ->setPaper('a4', 'portrait'); // Set ukuran kertas A4 potrait

    // 4. Return PDF (stream agar terbuka di tab baru)
    $nama_file_aman = 'SPK_' . str_replace('/', '_', $penugasan->no_surat) . '.pdf';

    return $pdf->stream($nama_file_aman);
    }
}
