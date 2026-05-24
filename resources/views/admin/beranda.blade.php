@extends('layouts.master')
 
@section('title', 'Beranda Admin')
 
@section('content')
<div class="container-fluid py-4">
 
    {{-- HEADER & FILTER BULAN DINAMIS (UPDATED KE VERSI ACCORDION + GLOBAL UX FILTER) --}}
    @php
        $bulanIndo = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        $bulanPilih = $bulanDipilih ?? request('month', date('n')); 
        $tahunPilih = $tahunDipilih ?? request('year', date('Y'));
        
        // Fallback jika $daftarTahun belum ter-passing sempurna di Admin
        $listTahun = $daftarTahun ?? range(2024, date('Y') + 1);
    @endphp
 
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-0">Beranda Admin</h2>
            <p class="text-muted small mb-0">Kelola master data dan pantau performa aplikasi keseluruhan.</p>
        </div>
        
        {{-- Area Filter Global (Sejajar di Kanan Atas) --}}
        <div class="d-flex flex-column flex-sm-row gap-2 align-items-sm-center">
            
            {{-- Form Filter Fungsi & Kegiatan --}}
            <form action="" method="GET" class="d-flex gap-2 mb-0">
                {{-- Tetap bawa serta data periode bulan & tahun yang aktif --}}
                <input type="hidden" name="month" value="{{ $bulanPilih }}">
                <input type="hidden" name="year" value="{{ $tahunPilih }}">
                
                {{-- Dropdown Filter Fungsi --}}
                <select name="fungsi" class="form-select form-select-sm bg-white border-primary text-primary shadow-sm px-3 py-2 fw-bold" onchange="this.form.submit()" style="min-width: 140px; border-radius: 8px; height: 40px;"> 
                    <option value="">-- Semua Fungsi --</option>
                    @foreach($listFungsi ?? [] as $f)
                        <option value="{{ $f }}" {{ request('fungsi') == $f ? 'selected' : '' }}>{{ $f }}</option>
                    @endforeach
                </select>

                {{-- Dropdown Filter Kegiatan --}}
                <select name="kegiatan" class="form-select form-select-sm bg-white border-primary text-primary shadow-sm px-3 py-2 fw-bold" onchange="this.form.submit()" style="min-width: 180px; max-width: 250px; border-radius: 8px; height: 40px;">
                    <option value="">-- Semua Kegiatan --</option>
                    @foreach($listKegiatan ?? [] as $keg)
                        <option value="{{ $keg->id_kegiatan ?? $keg->id }}" {{ request('kegiatan') == ($keg->id_kegiatan ?? $keg->id) ? 'selected' : '' }}>
                            {{ $keg->nama_kegiatan ?? $keg->Nama_kegiatan }}
                        </option>
                    @endforeach
                </select>

                {{-- Tombol Reset Filter --}}
                @if(request('fungsi') || request('kegiatan'))
                    <a href="?month={{ $bulanPilih }}&year={{ $tahunPilih }}" class="btn btn-sm btn-danger d-flex align-items-center justify-content-center rounded-3 px-3 shadow-sm" title="Reset Saringan Filter" style="height: 40px; border-radius: 8px;">
                        <i class="bi bi-x-circle"></i>
                    </a>
                @endif
            </form>

            {{-- Dropdown Filter Periode Bulan & Tahun --}}
            <div class="dropdown shadow-sm">
                <button class="btn btn-white dropdown-toggle border-primary bg-white fw-bold text-primary shadow-sm px-4" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false" style="border-radius: 8px; height: 40px;">
                    <i class="bi bi-calendar3 me-2"></i> {{ $bulanIndo[(int)$bulanPilih] }} {{ $tahunPilih }}
                </button>
                
                <div class="dropdown-menu dropdown-menu-end p-3 shadow-lg border-0" style="width: 320px; border-radius: 12px;">
                    <div class="text-center mb-3 pb-2 border-bottom">
                        <span class="fw-bold text-dark" style="font-size: 0.95rem;"><i class="bi bi-funnel-fill me-1 text-primary"></i> Pilih Tahun & Bulan</span>
                    </div>
                    
                    <div class="accordion accordion-flush" id="accordionTahunAdmin">
                        @foreach($listTahun as $th)
                        <div class="accordion-item border-0 mb-2">
                            <h2 class="accordion-header" id="heading-{{ $th }}">
                                <button class="accordion-button {{ $tahunPilih == $th ? '' : 'collapsed' }} py-2 px-3 fw-bold rounded bg-light border shadow-sm" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $th }}" style="font-size: 0.9rem;">
                                    Tahun {{ $th }}
                                </button>
                            </h2>
                            <div id="collapse-{{ $th }}" class="accordion-collapse collapse {{ $tahunPilih == $th ? 'show' : '' }}" data-bs-parent="#accordionTahunAdmin">
                                <div class="accordion-body p-2 border border-top-0 rounded-bottom bg-white">
                                    <div class="row g-2">
                                        @foreach($bulanIndo as $angka => $nama)
                                        <div class="col-4">
                                            {{-- Modifikasi: Tautan kalender otomatis mempertahankan filter fungsi & kegiatan yang sedang aktif --}}
                                            <a href="?year={{ $th }}&month={{ $angka }}{{ request('fungsi') ? '&fungsi='.request('fungsi') : '' }}{{ request('kegiatan') ? '&kegiatan='.request('kegiatan') : '' }}" class="btn btn-sm w-100 {{ ($tahunPilih == $th && $bulanPilih == $angka) ? 'btn-primary text-white fw-bold shadow' : 'btn-outline-primary' }}" style="font-size: 0.75rem; border-radius: 6px;">
                                                {{ substr($nama, 0, 3) }}
                                            </a>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
 
    {{-- KOTAK STATISTIK (5 CARDS DENGAN GRADIEN & DESAIN SERAGAM) --}}
    <div class="row g-3 mb-4 flex-stretch">
        {{-- Card 1: Total Mitra --}}
        <div class="col-xl col-md-4 col-sm-6">
            <div class="card border-0 text-white shadow-sm h-100" style="background: linear-gradient(135deg, #00d2ff 0%, #007bff 100%); border-radius: 12px;">
                <div class="card-body pb-2">
                    <h6 class="fw-bold mb-3 text-uppercase" style="font-size: 0.75rem; opacity: 0.9; letter-spacing: 0.5px;">TOTAL MITRA</h6>
                    <div class="d-flex align-items-baseline">
                        <h2 class="fw-bold mb-0 me-2" style="font-size: 2rem;">{{ number_format($totalMitra ?? 0, 0, ',', '.') }}</h2>
                        <span class="fw-medium" style="font-size: 0.9rem; opacity: 0.9;">Orang</span>
                    </div>
                </div>
                <div class="card-footer border-0 bg-transparent pt-0 pb-3">
                    <div class="small fw-medium" style="font-size: 0.8rem; opacity: 0.9;"><i class="bi bi-people-fill me-1"></i> Terdaftar di sistem</div>
                </div>
            </div>
        </div>
        
        {{-- Card 2: Total Kegiatan --}}
        <div class="col-xl col-md-4 col-sm-6">
            <div class="card border-0 text-white shadow-sm h-100" style="background: linear-gradient(135deg, #ffcf1b 0%, #ff8c00 100%); border-radius: 12px;">
                <div class="card-body pb-2">
                    <h6 class="fw-bold mb-3 text-uppercase" style="font-size: 0.75rem; opacity: 0.9; letter-spacing: 0.5px;">TOTAL KEGIATAN</h6>
                    <div class="d-flex align-items-baseline">
                        <h2 class="fw-bold mb-0 me-2" style="font-size: 2rem;">{{ number_format($totalKegiatan ?? 0, 0, ',', '.') }}</h2>
                        <span class="fw-medium" style="font-size: 0.9rem; opacity: 0.9;">Kegiatan</span>
                    </div>
                </div>
                <div class="card-footer border-0 bg-transparent pt-0 pb-3">
                    <div class="small fw-medium" style="font-size: 0.8rem; opacity: 0.9;"><i class="bi bi-calendar-check-fill me-1"></i> Total di sistem</div>
                </div>
            </div>
        </div>
        
        {{-- Card 3: Total Pegawai --}}
        <div class="col-xl col-md-4 col-sm-6">
            <div class="card border-0 text-white shadow-sm h-100" style="background: linear-gradient(135deg, #8E2DE2 0%, #4A00E0 100%); border-radius: 12px;">
                <div class="card-body pb-2">
                    <h6 class="fw-bold mb-3 text-uppercase" style="font-size: 0.75rem; opacity: 0.9; letter-spacing: 0.5px;">TOTAL PEGAWAI</h6>
                    <div class="d-flex align-items-baseline">
                        <h2 class="fw-bold mb-0 me-2" style="font-size: 2rem;">{{ number_format($totalPegawai ?? 0, 0, ',', '.') }}</h2>
                        <span class="fw-medium" style="font-size: 0.9rem; opacity: 0.9;">Orang</span>
                    </div>
                </div>
                <div class="card-footer border-0 bg-transparent pt-0 pb-3">
                    <div class="small fw-medium" style="font-size: 0.8rem; opacity: 0.9;"><i class="bi bi-person-badge-fill me-1"></i> Terdaftar di sistem</div>
                </div>
            </div>
        </div>
        
        {{-- Card 4: Survey Aktif --}}
        <div class="col-xl col-md-6 col-sm-6">
            <div class="card border-0 text-white shadow-sm h-100" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); border-radius: 12px;">
                <div class="card-body pb-2">
                    <h6 class="fw-bold mb-3 text-uppercase" style="font-size: 0.75rem; opacity: 0.9; letter-spacing: 0.5px;">SURVEY AKTIF</h6>
                    <div class="d-flex align-items-baseline">
                        <h2 class="fw-bold mb-0 me-2" style="font-size: 2rem;">{{ number_format($surveyAktif ?? 0, 0, ',', '.') }}</h2>
                        <span class="fw-medium" style="font-size: 0.9rem; opacity: 0.9;">Survey</span>
                    </div>
                </div>
                <div class="card-footer border-0 bg-transparent pt-0 pb-3">
                    <div class="small fw-medium" style="font-size: 0.8rem; opacity: 0.9;"><i class="bi bi-play-circle-fill me-1"></i> Sedang berlangsung</div>
                </div>
            </div>
        </div>
        
        {{-- Card 5: Survey Selesai --}}
        <div class="col-xl col-md-6 col-sm-12">
            <div class="card border-0 text-white shadow-sm h-100" style="background: linear-gradient(135deg, #606c88 0%, #3f4c6b 100%); border-radius: 12px;">
                <div class="card-body pb-2">
                    <h6 class="fw-bold mb-3 text-uppercase" style="font-size: 0.75rem; opacity: 0.9; letter-spacing: 0.5px;">SURVEY SELESAI</h6>
                    <div class="d-flex align-items-baseline">
                        <h2 class="fw-bold mb-0 me-2" style="font-size: 2rem;">{{ number_format($surveySelesai ?? 0, 0, ',', '.') }}</h2>
                        <span class="fw-medium" style="font-size: 0.9rem; opacity: 0.9;">Survey</span>
                    </div>
                </div>
                <div class="card-footer border-0 bg-transparent pt-0 pb-3">
                    <div class="small fw-medium" style="font-size: 0.8rem; opacity: 0.9;"><i class="bi bi-check-circle-fill me-1"></i> Telah diselesaikan</div>
                </div>
            </div>
        </div>
    </div>
 
    {{-- AREA GRAFIK DIBAGI DUA (BAR CHART & PIE CHART) --}}
    <div class="row g-4 mb-4">
        {{-- Kolom Kiri: Bar Chart --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 15px;">
                <div class="card-header bg-white border-bottom py-3" style="border-radius: 15px 15px 0 0;">
                    <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-bar-chart-fill text-primary me-2"></i> Top 5 Mitra (Honor Tertinggi Berdasarkan Saringan Filter)</h6>
                </div>
                <div class="card-body">
                    <div style="height: 300px;">
                        <canvas id="topMitraChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Pie Chart Rasio Mitra --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 15px;">
                <div class="card-header bg-white border-bottom py-3" style="border-radius: 15px 15px 0 0;">
                    <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-pie-chart-fill text-success me-2"></i> Rasio Keterlibatan Mitra</h6>
                </div>
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                    <div style="height: 220px; width: 100%;">
                        <canvas id="rasioMitraChart"></canvas>
                    </div>
                    <div class="mt-4 w-100">
                        <ul class="list-group list-group-flush small">
                            <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 pb-1">
                                <span><i class="bi bi-circle-fill text-success me-2"></i> Sudah Berhonor</span>
                                <span class="fw-bold fs-6">{{ $mitraBerhonor ?? 0 }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 pt-1">
                                <span><i class="bi bi-circle-fill text-danger me-2"></i> Belum Bekerja</span>
                                <span class="fw-bold fs-6">{{ $mitraTanpaHonor ?? 0 }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
 
    {{-- TABEL DAFTAR PENUGASAN (KEMBALI KE DESAIN STANDAR KARENA FILTER PINDAH KE ATAS) --}}
    <div class="card border-0 shadow-sm" style="border-radius: 15px;">
        <div class="card-header bg-white border-bottom py-3" style="border-radius: 15px 15px 0 0;">
            <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-table text-primary me-2"></i> Daftar Penugasan - Bulan {{ $bulanIndo[(int)$bulanPilih] }} {{ $tahunPilih }}</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="py-3 text-muted text-center" style="width: 50px;">No</th>
                            <th class="py-3 text-muted">Nama Mitra</th>
                            <th class="py-3 text-muted">Nama Kegiatan</th>
                            <th class="py-3 text-muted text-center">Status</th>
                            <th class="py-3 text-muted text-end px-4">Total Honor</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($daftarPenugasan ?? [] as $index => $penugasan)
                        <tr>
                            <td class="text-center">{{ (method_exists($daftarPenugasan, 'firstItem') ? $daftarPenugasan->firstItem() : 1) + $index }}</td>                            <td class="fw-medium text-dark">{{ $penugasan->mitra->nama_petugas ?? 'N/A' }}</td>
                            <td>
                                @if($penugasan->details && $penugasan->details->count() > 0)
                                    {{ $penugasan->details->first()->kegiatan->Nama_kegiatan ?? $penugasan->details->first()->kegiatan->nama_kegiatan ?? '-' }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="text-center">
                                @if(strtolower($penugasan->status_kontrak) == 'disetujui')
                                    <span class="badge bg-success">Disetujui</span>
                                @elseif(strtolower($penugasan->status_kontrak) == 'ditolak')
                                    <span class="badge bg-danger">Ditolak</span>
                                @else
                                    <span class="badge bg-warning text-dark">Menunggu</span>
                                @endif
                            </td>
                            <td class="text-end px-4 fw-bold text-primary">Rp {{ number_format($penugasan->total_nilai_perjanjian, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-3 text-secondary" style="opacity: 0.5;"></i>
                                Tidak ada data penugasan untuk kriteria filter ini.
                            </td>
                        </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div> 
                {{-- Ini penutup dari div class="table-responsive" --}}

                {{-- ========================================== --}}
                {{-- KODE UNTUK MENAMPILKAN TOMBOL PAGINATION   --}}
                {{-- ========================================== --}}
                <div class="d-flex justify-content-end mt-4 px-3">
                    @if(method_exists($daftarPenugasan, 'hasPages') && $daftarPenugasan->hasPages())
                        {{ $daftarPenugasan->links() }}
                    @endif
                </div>
                </div> {{-- Ini penutup dari div class="card-body" --}}
            </div> {{-- Ini penutup dari div class="card" --}}
        </div>
    </div>
</div>
@endsection
 
@push('scripts')
{{-- Panggil Library Chart.js melalui CDN --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
{{-- Plugin Datalabels untuk menampilkan angka di atas bar --}}
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
 
<script>
document.addEventListener("DOMContentLoaded", function() {
    
    // Registrasi plugin secara global
    Chart.register(ChartDataLabels);
    
    // ==========================================
    // 1. RENDER CHART BAR (TOP 5 MITRA)
    // ==========================================
    const labelMitra = {!! json_encode($topMitraLabels ?? []) !!};
    const dataHonor = {!! json_encode($topMitraHonor ?? []) !!};
 
    const ctxBar = document.getElementById('topMitraChart').getContext('2d');
    
    if(labelMitra.length === 0) {
        document.getElementById('topMitraChart').parentElement.innerHTML = '<div class="h-100 d-flex align-items-center justify-content-center text-muted"><p><i class="bi bi-bar-chart me-2"></i>Belum ada data penugasan pada filter ini.</p></div>';
    } else {
        new Chart(ctxBar, {
            type: 'bar',
            plugins: [ChartDataLabels], // Wajib dipanggil di sini
            data: {
                labels: labelMitra,
                datasets: [{
                    label: 'Total Honor (Rp)',
                    data: dataHonor,
                    backgroundColor: ['rgba(0, 123, 255, 0.7)', 'rgba(40, 167, 69, 0.7)', 'rgba(255, 193, 7, 0.7)', 'rgba(23, 162, 184, 0.7)', 'rgba(111, 66, 193, 0.7)'],
                    borderColor: ['rgba(0, 123, 255, 1)', 'rgba(40, 167, 69, 1)', 'rgba(255, 193, 7, 1)', 'rgba(23, 162, 184, 1)', 'rgba(111, 66, 193, 1)'],
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                layout: {
                    padding: {
                        top: 30 // Jarak agar angka tidak terpotong tepi atas grafik
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) { 
                                return 'Rp ' + Number(value).toLocaleString('id-ID', { maximumFractionDigits: 0 }); 
                            }
                        }
                    }
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) label += ': ';
                                if (context.parsed.y !== null) label += 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                                return label;
                            }
                        }
                    },
                    // Konfigurasi Plugin Datalabels (Angka di atas pilar)
                    datalabels: {
                        anchor: 'end',
                        align: 'top',
                        color: '#444',
                        font: {
                            weight: 'bold',
                            size: 11
                        },
                        formatter: function(value) {
                        return 'Rp ' + Number(value).toLocaleString('id-ID', { maximumFractionDigits: 0 });                        }
                    }
                }
            }
        });
    }

    // ==========================================
    // 2. RENDER PIE CHART (RASIO MITRA)
    // ==========================================
    const mitraBerhonor = {{ $mitraBerhonor ?? 0 }};
    const mitraTanpaHonor = {{ $mitraTanpaHonor ?? 0 }};
    
    const ctxPie = document.getElementById('rasioMitraChart');
    if (ctxPie) {
        new Chart(ctxPie.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['Sudah Berhonor', 'Belum Bekerja'],
                datasets: [{
                    data: [mitraBerhonor, mitraTanpaHonor],
                    backgroundColor: ['#1cc88a', '#e74a3b'], // Hijau & Merah
                    borderWidth: 2,
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: { display: false },
                    // Nonaktifkan datalabels untuk Pie Chart agar tidak berantakan
                    datalabels: { display: false }, 
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return ' ' + context.label + ': ' + context.raw + ' Orang';
                            }
                        }
                    }
                }
            }
        });
    }
});
</script>
@endpush