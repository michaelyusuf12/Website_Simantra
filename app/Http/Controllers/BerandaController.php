<?php
 
namespace App\Http\Controllers;
 
use App\Models\Mitra;
use App\Models\Kegiatan;
use App\Models\Penugasan;
use App\Models\DetailPenugasan; 
use App\Models\User; 
use Illuminate\Http\Request; 
use Carbon\Carbon; 
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\DB; 
 
class BerandaController extends Controller
{
    private $bulanMap = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    ];
 
    public function index(Request $request) 
    {
        $data = []; 
        $role = Auth::user()->role; 
        
        // 1. LOGIKA FILTER BULAN
        $bulanAngka = $request->input('month', Carbon::now()->month); 
        $data['bulanDipilih'] = (int)$bulanAngka; 
        $bulanNama = $this->bulanMap[$bulanAngka] ?? Carbon::now()->monthName; 
 
        // 2. QUERY DAFTAR PENUGASAN (Untuk tabel jika ada)
        $data['daftarPenugasan'] = Penugasan::with(['mitra', 'details.kegiatan'])
            ->where('bulan_kegiatan', $bulanNama)
            ->latest()
            ->get();
 
        if ($role == 'admin') {
            // --- LOGIKA KHUSUS ADMIN ---
            $data['totalMitra'] = Mitra::count();
            $data['totalKegiatan'] = Kegiatan::count();
            $data['totalPegawai'] = User::where('role', 'pegawai')->count();
 
            $hariIni = Carbon::now()->toDateString();
 
            $data['surveyAktif'] = Kegiatan::where('tgl_mulai', '<=', $hariIni)
                ->where('tgl_selesai', '>=', $hariIni)
                ->count();
 
            $data['surveySelesai'] = Kegiatan::where('tgl_selesai', '<', $hariIni)
                ->count();
 
            $topMitraData = Penugasan::select('mitra_id', DB::raw('SUM(total_nilai_perjanjian) as total_honor'))
                ->with('mitra') 
                ->groupBy('mitra_id')
                ->orderByDesc('total_honor')
                ->limit(5)
                ->get();
 
            $data['topMitraLabels'] = $topMitraData->map(fn($item) => $item->mitra->nama_petugas ?? 'N/A')->toArray();
            $data['topMitraHonor'] = $topMitraData->map(fn($item) => $item->total_honor)->toArray();
            
        } elseif ($role == 'kepala') {
            // --- LOGIKA KHUSUS KEPALA BPS ---
            $data['menunggu'] = Penugasan::where('bulan_kegiatan', $bulanNama)->where('status_kontrak', 'menunggu approval')->count();
            $data['disetujui'] = Penugasan::where('bulan_kegiatan', $bulanNama)->where('status_kontrak', 'Disetujui')->count();
            $data['mitraAktif'] = Penugasan::where('bulan_kegiatan', $bulanNama)->distinct('mitra_id')->count('mitra_id');
            $data['totalMitra'] = User::where('role', 'mitra')->count();
            
            // TAMBAHAN 1: Total Anggaran / Honorarium bulan ini
            $data['totalHonor'] = Penugasan::where('bulan_kegiatan', $bulanNama)->sum('total_nilai_perjanjian');

            // TAMBAHAN 2: Data Shortcut SPK Menunggu Persetujuan (Maksimal 5 terbaru)
            $data['shortcutApproval'] = Penugasan::with('mitra')
                                        ->where('bulan_kegiatan', $bulanNama)
                                        ->where('status_kontrak', 'menunggu approval') 
                                        ->latest()
                                        ->take(5)
                                        ->get();
            
            // Ambil semua data SPK bulan ini beserta rincian kegiatannya
            $semuaPenugasanBulanIni = Penugasan::with('details.kegiatan')
                                        ->where('bulan_kegiatan', $bulanNama)
                                        ->get();
            $rekapKegiatan = []; // Array kosong untuk menampung hitungan
            
            foreach ($semuaPenugasanBulanIni as $spk) {
                if ($spk->details) {
                    foreach ($spk->details as $detail) {
                        // Ambil nama kegiatan
                        $namaKeg = $detail->kegiatan ? ($detail->kegiatan->nama_kegiatan ?? $detail->kegiatan->Nama_kegiatan) : 'Kegiatan Tidak Diketahui';
                        
                        // Hitung uangnya (harga x volume)
                        $subtotal = $detail->harga_satuan * $detail->volume;
                        
                        // Masukkan ke dalam "keranjang" kegiatan masing-masing
                        if (!isset($rekapKegiatan[$namaKeg])) {
                            $rekapKegiatan[$namaKeg] = 0;
                        }
                        $rekapKegiatan[$namaKeg] += $subtotal;
                    }
                }
            }
            arsort($rekapKegiatan);
            $data['honorPerKegiatan'] = $rekapKegiatan;

        } elseif ($role == 'mitra') {
            // --- LOGIKA KHUSUS BERANDA MITRA ---
            $user = Auth::user();
            $mitra = \App\Models\Mitra::where('id_user', $user->id_user ?? $user->id)->first();
 
            $data['totalHonor'] = 0;
            $data['jumlahKegiatan'] = 0;
            $data['paguMaksimum'] = 0;
            
            // Siapkan wadah (array) kosong agar tidak error jika data mitra kosong
            $data['labelBulanChart'] = [];
            $data['dataHonorChart'] = [];
 
            if ($mitra) {
                $queryPenugasan = Penugasan::where('mitra_id', $mitra->sobat_id)
                    ->where('bulan_kegiatan', $bulanNama)
                    ->where('status_kontrak', '!=', 'Ditolak');
 
                $data['totalHonor'] = $queryPenugasan->sum('total_nilai_perjanjian');
                $data['jumlahKegiatan'] = $queryPenugasan->count();
 
                if ($mitra->posisi_petugas == 3) {
                    $setting = DB::table('settings')->orderByDesc('batas_honor')->first();
                } else {
                    $setting = DB::table('settings')->where('posisi_kode', $mitra->posisi_petugas)->first();
                }
                
                $data['paguMaksimum'] = $setting ? $setting->batas_honor : 0;

                // ========================================================
                // TAMBAHAN BARU: LOGIKA GRAFIK 6 BULAN TERAKHIR UNTUK MITRA
                // ========================================================
                $bulanSekarang = date('n'); 
                
                for ($i = 5; $i >= 0; $i--) {
                    $angkaBulanLoop = $bulanSekarang - $i;
                    
                    if ($angkaBulanLoop <= 0) {
                        $angkaBulanLoop += 12; 
                    }
                    
                    $namaBulanLoop = $this->bulanMap[$angkaBulanLoop];
                    
                    $data['labelBulanChart'][] = $namaBulanLoop;

                    $totalPerBulan = Penugasan::where('mitra_id', $mitra->sobat_id)
                        ->where('bulan_kegiatan', $namaBulanLoop)
                        ->where('status_kontrak', '!=', 'Ditolak')
                        ->sum('total_nilai_perjanjian');

                    $data['dataHonorChart'][] = $totalPerBulan;
                }
            }

        } elseif ($role == 'pegawai') {
            // --- LOGIKA KHUSUS PEGAWAI ---
            
            // 1. Hitung Statistik Card (Sekarang ada 4 data)
            $data['totalSpk'] = Penugasan::where('bulan_kegiatan', $bulanNama)->count();
            $data['totalHonor'] = Penugasan::where('bulan_kegiatan', $bulanNama)->sum('total_nilai_perjanjian');
            $data['mitraAktif'] = Penugasan::where('bulan_kegiatan', $bulanNama)->distinct('mitra_id')->count('mitra_id');
            
            // Data untuk Card ke-4: Menunggu Persetujuan
            $data['menungguApproval'] = Penugasan::where('bulan_kegiatan', $bulanNama)
                                          ->where('status_kontrak', 'menunggu approval')
                                          ->count();

            // 2. Data Tabel Shortcut: 5 SPK Terakhir Dibuat
            $data['spkTerbaru'] = Penugasan::with(['mitra'])
                                    ->where('bulan_kegiatan', $bulanNama)
                                    ->latest()
                                    ->take(5) // Ambil 5 data teratas
                                    ->get();

            // 3. Logika Monitoring Top 5 Limit Honor (Tetap sama seperti sebelumnya)
            $monitoring = Penugasan::select('mitra_id', DB::raw('SUM(total_nilai_perjanjian) as used_honor'))
                ->where('bulan_kegiatan', $bulanNama)
                ->with(['mitra'])
                ->groupBy('mitra_id')
                ->get();

            $settings = DB::table('settings')->get();

            $data['topMitraLimit'] = $monitoring->map(function($item) use ($settings) {
                $mitra = $item->mitra;
                $limit = 0;

                if ($mitra) {
                    if ($mitra->posisi_petugas == 3) {
                        $s = $settings->sortByDesc('batas_honor')->first();
                    } else {
                        $s = $settings->where('posisi_kode', $mitra->posisi_petugas)->first();
                    }
                    $limit = $s ? $s->batas_honor : 0;
                }

                $percentage = $limit > 0 ? ($item->used_honor / $limit) * 100 : 0;
                
                return [
                    'id_mitra' => $mitra->sobat_id ?? null, // Tambahan ID untuk tombol aksi
                    'nama' => $mitra->nama_petugas ?? 'N/A',
                    'used' => $item->used_honor,
                    'limit' => $limit,
                    'percentage' => round($percentage, 0),
                    'status' => $percentage >= 90 ? 'Kritis' : 'Aman',
                    'color' => $percentage >= 90 ? 'danger' : 'success'
                ];
            })->sortByDesc('percentage')->take(5);
        }
 
        // --- PENGARAHAN VIEW ---
        return view(($role == 'kepala' ? 'kepala_bps' : $role) . '.beranda', $data);
    }
}