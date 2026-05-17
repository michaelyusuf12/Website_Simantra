@extends('layouts.master')
 
@section('title', 'Beranda Admin')
 
@section('content')
<div class="container-fluid py-4">
 
    {{-- HEADER & FILTER BULAN DINAMIS --}}
    @php
        $bulanIndo = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        $bulanPilih = $bulanDipilih ?? request('month', date('n')); 
        $tahunSaatIni = date('Y');
    @endphp
 
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-0">Beranda Admin</h2>
        </div>
        
        <div class="dropdown shadow-sm">
            <button class="btn btn-white dropdown-toggle px-4 border bg-white" type="button" data-bs-toggle="dropdown" style="border-radius: 10px; font-weight: 500;">
                <i class="bi bi-calendar3 me-2 text-primary"></i> 
                {{ $bulanIndo[(int)$bulanPilih] }} {{ $tahunSaatIni }}
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow border-0" style="border-radius: 12px; max-height: 250px; overflow-y: auto;">
                <li><h6 class="dropdown-header">Pilih Periode</h6></li>
                @foreach($bulanIndo as $angka => $nama)
                    <li>
                        <a class="dropdown-item {{ $bulanPilih == $angka ? 'active bg-primary text-white' : '' }}" href="?month={{ $angka }}">
                            {{ $nama }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
 
    {{-- KOTAK STATISTIK (5 CARDS DENGAN GRADIEN & DESAIN SERAGAM) --}}
    <div class="row g-3 mb-4">
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
 
    {{-- AREA GRAFIK (CHART.JS) --}}
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
        <div class="card-header bg-white border-bottom py-3" style="border-radius: 15px 15px 0 0;">
            <h6 class="fw-bold mb-0 text-dark">Top 5 Mitra (Honor Tertinggi Keseluruhan)</h6>
        </div>
        <div class="card-body">
            <div style="height: 350px;">
                <canvas id="topMitraChart"></canvas>
            </div>
        </div>
    </div>
 
    {{-- TABEL DAFTAR PENUGASAN --}}
    <div class="card border-0 shadow-sm" style="border-radius: 15px;">
        <div class="card-header bg-white border-bottom py-3" style="border-radius: 15px 15px 0 0;">
            <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-table text-primary me-2"></i> Daftar Penugasan - Bulan {{ $bulanIndo[(int)$bulanPilih] }}</h6>
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
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td class="fw-medium text-dark">{{ $penugasan->mitra->nama_petugas ?? 'N/A' }}</td>
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
                                Tidak ada data penugasan untuk bulan ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
 
@push('scripts')
{{-- Panggil Library Chart.js melalui CDN --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
 
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Ambil Data Array dari PHP/Laravel (dikirim oleh Controller)
    const labelMitra = {!! json_encode($topMitraLabels ?? []) !!};
    const dataHonor = {!! json_encode($topMitraHonor ?? []) !!};
 
    const ctx = document.getElementById('topMitraChart').getContext('2d');
    
    // Periksa apakah datanya kosong
    if(labelMitra.length === 0) {
        document.getElementById('topMitraChart').parentElement.innerHTML = '<div class="h-100 d-flex align-items-center justify-content-center text-muted"><p><i class="bi bi-bar-chart me-2"></i>Belum ada data penugasan.</p></div>';
        return;
    }
 
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labelMitra,
            datasets: [{
                label: 'Total Honor (Rp)',
                data: dataHonor,
                backgroundColor: [
                    'rgba(0, 123, 255, 0.7)',
                    'rgba(40, 167, 69, 0.7)',
                    'rgba(255, 193, 7, 0.7)',
                    'rgba(23, 162, 184, 0.7)',
                    'rgba(111, 66, 193, 0.7)'
                ],
                borderColor: [
                    'rgba(0, 123, 255, 1)',
                    'rgba(40, 167, 69, 1)',
                    'rgba(255, 193, 7, 1)',
                    'rgba(23, 162, 184, 1)',
                    'rgba(111, 66, 193, 1)'
                ],
                borderWidth: 1,
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value, index, values) {
                            return 'Rp ' + value.toLocaleString('id-ID'); // Format rupiah
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    display: false // Sembunyikan tulisan legend agar bersih
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            if (context.parsed.y !== null) {
                                label += 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                            }
                            return label;
                        }
                    }
                }
            }
        }
    });
});
</script>
@endpush