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
        
        // ==========================================
        // 1. LOGIKA FILTER PERIODE (TAHUN & BULAN)
        // ==========================================
        $tahunSekarang = Carbon::now()->year;
        $tahunDipilih = $request->input('year', $tahunSekarang);
        $data['tahunDipilih'] = (int)$tahunDipilih;
        $tahunMulaiAplikasi = 2024; 
        $data['daftarTahun'] = range($tahunMulaiAplikasi, $tahunSekarang + 1); 
        $bulanAngka = $request->input('month', Carbon::now()->month); 
        $data['bulanDipilih'] = (int)$bulanAngka; 
        $bulanNama = $this->bulanMap[$bulanAngka] ?? Carbon::now()->monthName; 

        // Query Dasar (Digunakan oleh banyak role)
        $penugasanBulanIni = Penugasan::with(['mitra', 'details.kegiatan'])
            ->where('bulan_kegiatan', $bulanNama)
            ->whereYear('tanggal_surat', $tahunDipilih);
            
        // ==========================================
        // [REVISI BPS] FILTER FUNGSI DAN KEGIATAN
        // ==========================================
        if ($request->filled('fungsi')) {
            $penugasanBulanIni->whereHas('details.kegiatan', function ($q) use ($request) {
                $q->where('fungsi', $request->fungsi);
            });
        }

        if ($request->filled('kegiatan')) {
            $penugasanBulanIni->whereHas('details', function ($q) use ($request) {
                $q->where('id_kegiatan', $request->kegiatan);
            });
        }
        

        // Siapkan data dropdown untuk dikirim ke tampilan (View)
        $data['listFungsi'] = Kegiatan::select('fungsi')->distinct()->whereNotNull('fungsi')->pluck('fungsi');
        $data['listKegiatan'] = Kegiatan::select('id_kegiatan', 'nama_kegiatan', 'Nama_kegiatan')->get();
        // ==========================================

        $data['daftarPenugasan'] = (clone $penugasanBulanIni)->latest()->get();

        // ==========================================
        // 2. LOGIKA KHUSUS ADMIN
        // ==========================================
        if ($role == 'admin') {
            $data['totalMitra'] = Mitra::count();
            $data['totalKegiatan'] = Kegiatan::count();
            $data['totalPegawai'] = User::where('role', 'pegawai')->count();

            $hariIni = Carbon::now()->toDateString();
            $data['surveyAktif'] = Kegiatan::where('tgl_mulai', '<=', $hariIni)->where('tgl_selesai', '>=', $hariIni)->count();
            $data['surveySelesai'] = Kegiatan::where('tgl_selesai', '<', $hariIni)->count();

            // TOP 5 Mitra (Bar Chart)
            $topMitraData = (clone $penugasanBulanIni)->select('mitra_id', DB::raw('SUM(total_nilai_perjanjian) as total_honor'))
                ->groupBy('mitra_id')
                ->orderByDesc('total_honor')
                ->limit(5)
                ->get();
            $data['topMitraLabels'] = $topMitraData->map(fn($item) => $item->mitra->nama_petugas ?? 'N/A')->toArray();
            $data['topMitraHonor'] = $topMitraData->map(fn($item) => $item->total_honor)->toArray();

            // [REVISI] PIE CHART (Mitra Berhonor vs Belum Berhonor)
            $semuaMitraIds = Mitra::pluck('sobat_id')->toArray();
            $mitraBerhonorIds = (clone $penugasanBulanIni)->where('total_nilai_perjanjian', '>', 0)->pluck('mitra_id')->unique()->toArray();
            $mitraBelumBerhonorIds = array_diff($semuaMitraIds, $mitraBerhonorIds);

            // 👇 TAMBAHKAN 2 BARIS INI UNTUK DIKIRIM KE VIEW ADMIN 👇
            $data['mitraBerhonor'] = count($mitraBerhonorIds);
            $data['mitraTanpaHonor'] = count($mitraBelumBerhonorIds);

            // Kita kirimkan array berisi "Nama-nama Mitra" agar nanti JS bisa menampilkannya di Pop-up Modal
            $data['chartRasio'] = [
                'berhonor' => Mitra::whereIn('sobat_id', $mitraBerhonorIds)->pluck('nama_petugas')->toArray(),
                'belum_berhonor' => Mitra::whereIn('sobat_id', $mitraBelumBerhonorIds)->pluck('nama_petugas')->toArray()
            ];
        

        // ==========================================
        // 3. LOGIKA KHUSUS PPK & KEPALA BPS (Dashboard Sama)
        // ==========================================
        } elseif ($role == 'ppk' || $role == 'kepala_bps') {
            
            $data['menunggu'] = (clone $penugasanBulanIni)->where('status_kontrak', 'menunggu approval')->count();
            $data['disetujui'] = (clone $penugasanBulanIni)->where('status_kontrak', 'Disetujui')->count();
            
            $totalMitra = Mitra::count();
            $mitraBerhonor = (clone $penugasanBulanIni)->where('total_nilai_perjanjian', '>', 0)->distinct('mitra_id')->count('mitra_id');
            
            // [REVISI] Card 3: Persentase Mitra Memiliki Honor
            $persen = $totalMitra > 0 ? ($mitraBerhonor / $totalMitra) * 100 : 0;
            $data['persentaseMitraBerhonor'] = round($persen, 1);
            $data['totalMitra'] = $totalMitra;

            $data['totalHonor'] = (clone $penugasanBulanIni)->sum('total_nilai_perjanjian');

            $data['shortcutApproval'] = (clone $penugasanBulanIni)
                ->where('status_kontrak', 'menunggu approval')
                ->latest()->take(5)->get();

            // [REVISI] PIE CHART: Honor per Kegiatan & Honor per Fungsi
            $semuaPenugasanBulanIni = (clone $penugasanBulanIni)->get();
            $rekapKegiatan = []; 
            $rekapFungsi = [];

            foreach ($semuaPenugasanBulanIni as $spk) {
                if ($spk->details) {
                    foreach ($spk->details as $detail) {
                        $namaKeg = $detail->kegiatan ? ($detail->kegiatan->nama_kegiatan ?? $detail->kegiatan->Nama_kegiatan) : 'Kegiatan Tidak Diketahui';
                        $namaFungsi = $detail->kegiatan ? $detail->kegiatan->fungsi : 'Tidak Terdefinisi';
                        $namaMitra = $spk->mitra ? $spk->mitra->nama_petugas : 'N/A';
                        $subtotal = $detail->harga_satuan * $detail->volume;

                        // Rekap Kegiatan
                        if (!isset($rekapKegiatan[$namaKeg])) {
                            $rekapKegiatan[$namaKeg] = ['total' => 0, 'mitra' => []];
                        }
                        $rekapKegiatan[$namaKeg]['total'] += $subtotal;
                        if (!in_array($namaMitra, $rekapKegiatan[$namaKeg]['mitra'])) {
                            $rekapKegiatan[$namaKeg]['mitra'][] = $namaMitra; // Simpan nama mitra untuk pop-up
                        }

                        // Rekap Fungsi
                        if (!isset($rekapFungsi[$namaFungsi])) {
                            $rekapFungsi[$namaFungsi] = ['total' => 0, 'mitra' => []];
                        }
                        $rekapFungsi[$namaFungsi]['total'] += $subtotal;
                        if (!in_array($namaMitra, $rekapFungsi[$namaFungsi]['mitra'])) {
                            $rekapFungsi[$namaFungsi]['mitra'][] = $namaMitra;
                        }
                    }
                }
            }
            
            // Urutkan dari honor terbesar
            uasort($rekapKegiatan, fn($a, $b) => $b['total'] <=> $a['total']);
            uasort($rekapFungsi, fn($a, $b) => $b['total'] <=> $a['total']);

            $data['honorPerKegiatan'] = $rekapKegiatan;
            $data['honorPerFungsi'] = $rekapFungsi;

        // ==========================================
        // 4. LOGIKA KHUSUS MITRA
        // ==========================================
        } elseif ($role == 'mitra') {
            $mitra = Mitra::where('id_user', Auth::user()->id_user ?? Auth::user()->id)->first();

            $data['totalHonor'] = 0;
            $data['jumlahKegiatan'] = 0;
            $data['paguMaksimum'] = 0;
            $data['labelBulanChart'] = [];
            $data['dataHonorChart'] = [];

            if ($mitra) {
                $queryPenugasan = Penugasan::where('mitra_id', $mitra->sobat_id)
                    ->where('bulan_kegiatan', $bulanNama)
                    ->whereYear('tanggal_surat', $tahunDipilih)
                    ->where('status_kontrak', '!=', 'Ditolak');

                $data['totalHonor'] = $queryPenugasan->sum('total_nilai_perjanjian');
                $data['jumlahKegiatan'] = $queryPenugasan->count();

                $mitraPos = $mitra->posisi_petugas ? strtolower((string)$mitra->posisi_petugas) : '';
                $setting = null;

                if ($mitraPos == '3' || str_contains($mitraPos, 'pengolahan') && str_contains($mitraPos, 'lapangan')) {
                    $setting = DB::table('settings')->orderByDesc('batas_honor')->first();
                } else {
                    $setting = DB::table('settings')->where('posisi_kode', $mitra->posisi_petugas)->first();
                }
                
                $data['paguMaksimum'] = $setting ? $setting->batas_honor : 0;

                // Chart 6 Bulan Terakhir
                $bulanSekarang = date('n'); 
                for ($i = 5; $i >= 0; $i--) {
                    $angkaBulanLoop = $bulanSekarang - $i;
                    if ($angkaBulanLoop <= 0) $angkaBulanLoop += 12; 
                    
                    $namaBulanLoop = $this->bulanMap[$angkaBulanLoop];
                    $data['labelBulanChart'][] = $namaBulanLoop;

                    $totalPerBulan = Penugasan::where('mitra_id', $mitra->sobat_id)
                        ->where('bulan_kegiatan', $namaBulanLoop)
                        ->where('status_kontrak', '!=', 'Ditolak')
                        ->sum('total_nilai_perjanjian');

                    $data['dataHonorChart'][] = $totalPerBulan;
                }
            }

        // ==========================================
        // 5. LOGIKA KHUSUS PEGAWAI
        // ==========================================
        } elseif ($role == 'pegawai') {
            $data['totalSpk'] = (clone $penugasanBulanIni)->count();
            $data['totalHonor'] = (clone $penugasanBulanIni)->sum('total_nilai_perjanjian');
            $data['mitraAktif'] = (clone $penugasanBulanIni)->distinct('mitra_id')->count('mitra_id');
            $data['menungguApproval'] = (clone $penugasanBulanIni)->where('status_kontrak', 'menunggu approval')->count();
            $data['spkTerbaru'] = (clone $penugasanBulanIni)->latest()->take(5)->get();

            // Monitoring Limit Honor Mitra
            $monitoring = (clone $penugasanBulanIni)->select('mitra_id', DB::raw('SUM(total_nilai_perjanjian) as used_honor'))
                ->groupBy('mitra_id')
                ->get();

            $settings = DB::table('settings')->get();

            $data['topMitraLimit'] = $monitoring->map(function($item) use ($settings) {
                $mitra = $item->mitra;
                $limit = 0;

                if ($mitra) {
                    $pos = strtolower((string)$mitra->posisi_petugas);
                    if ($pos == '3' || (str_contains($pos, 'lapangan') && str_contains($pos, 'pengolahan'))) {
                        $s = $settings->sortByDesc('batas_honor')->first();
                    } else {
                        $s = $settings->where('posisi_kode', $mitra->posisi_petugas)->first();
                    }
                    $limit = $s ? $s->batas_honor : 0;
                }

                $percentage = $limit > 0 ? ($item->used_honor / $limit) * 100 : 0;
                
                return [
                    'id_mitra' => $mitra->sobat_id ?? null,
                    'nama' => $mitra->nama_petugas ?? 'N/A',
                    'used' => $item->used_honor,
                    'limit' => $limit,
                    'percentage' => round($percentage, 0),
                    // [REVISI] Mengubah Label Warning Mitra
                    'status' => $percentage >= 90 ? 'Hampir Maksimal' : 'Aman', 
                    'color' => $percentage >= 90 ? 'warning' : 'success'
                ];
            })->sortByDesc('percentage')->take(5);
        }
 
        // --- PENGARAHAN VIEW ---
        // Jika kepala_bps login, arahkan ke view ppk.beranda agar tampilannya sama
       if ($role == 'kepala') {
            $role = 'ppk';
        }

        // Arahkan ke folder masing-masing sesuai role yang sedang login
        return view($role . '.beranda', $data);
    }
}