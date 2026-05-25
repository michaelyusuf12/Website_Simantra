@extends('layouts.master')
 
@section('title', 'Beranda Mitra')

@section('content')
<div class="container-fluid py-4">
    
{{-- Header & Filter Bulan (Desain Premium Accordion) --}}
    @php
        $bulanIndo = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        $bulanPilih = request('month', date('n')); 
        $tahunPilih = request('year', date('Y'));
        $listTahun = range(2024, date('Y') + 1); // List tahun otomatis
        
        // LOGIKA WARNA & STATUS PROGRESS BAR
        $sisaPagu = $paguMaksimum - $totalHonor;
        $persentase = $paguMaksimum > 0 ? ($totalHonor / $paguMaksimum) * 100 : 0;
        
        if($persentase <= 60) {
            $warnaProg = 'bg-success';
            $textProg = 'text-success';
            $statusTeks = 'AMAN';
            $statusDesc = 'Penggunaan Pagu Normal';
        } elseif($persentase <= 85) {
            $warnaProg = 'bg-warning';
            $textProg = 'text-warning';
            $statusTeks = 'HAMPIR MAKSIMAL';
            $statusDesc = 'Mendekati Batas Pagu';
        } else {
            $warnaProg = 'bg-danger';
            $textProg = 'text-danger';
            $statusTeks = 'BATAS MAKSIMAL LIMIT';
            $statusDesc = 'Hampir Melewati Pagu';
        }
    @endphp

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Beranda Mitra</h2>
        </div>
        
        <div class="dropdown shadow-sm">
            <button class="btn btn-white dropdown-toggle border-primary bg-white fw-bold text-primary shadow-sm px-4" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false" style="border-radius: 8px; height: 40px;">
                <i class="bi bi-calendar3 me-2"></i> {{ $bulanIndo[(int)$bulanPilih] }} {{ $tahunPilih }}
            </button>
            
            <div class="dropdown-menu dropdown-menu-end p-3 shadow-lg border-0" style="width: 320px; border-radius: 12px;">
                <div class="text-center mb-3 pb-2 border-bottom">
                    <span class="fw-bold text-dark" style="font-size: 0.95rem;"><i class="bi bi-funnel-fill me-1 text-primary"></i> Pilih Tahun & Bulan</span>
                </div>
                
                <div class="accordion accordion-flush" id="accordionTahunMitra">
                    @foreach($listTahun as $th)
                    <div class="accordion-item border-0 mb-2">
                        <h2 class="accordion-header" id="heading-{{ $th }}">
                            <button class="accordion-button {{ $tahunPilih == $th ? '' : 'collapsed' }} py-2 px-3 fw-bold rounded bg-light border shadow-sm" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-mitra-{{ $th }}" style="font-size: 0.9rem;">
                                Tahun {{ $th }}
                            </button>
                        </h2>
                        <div id="collapse-mitra-{{ $th }}" class="accordion-collapse collapse {{ $tahunPilih == $th ? 'show' : '' }}" data-bs-parent="#accordionTahunMitra">
                            <div class="accordion-body p-2 border border-top-0 rounded-bottom bg-white">
                                <div class="row g-2">
                                    @foreach($bulanIndo as $angka => $nama)
                                    <div class="col-4">
                                        <a href="?year={{ $th }}&month={{ $angka }}" class="btn btn-sm w-100 {{ ($tahunPilih == $th && $bulanPilih == $angka) ? 'btn-primary text-white fw-bold shadow' : 'btn-outline-primary' }}" style="font-size: 0.75rem; border-radius: 6px;">
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
 
    {{-- Dua Kotak Statistik --}}
    <div class="row g-4 mb-4">
        {{-- Card 1: Total Honor (Biru) --}}
        <div class="col-md-6">
            <div class="card border-0 text-white shadow-sm h-100" style="background: linear-gradient(135deg, #00d2ff 0%, #007bff 100%); border-radius: 12px;">
                <div class="card-body pb-2">
                    <h6 class="fw-bold mb-3 text-uppercase" style="font-size: 0.75rem; opacity: 0.9; letter-spacing: 0.5px;">TOTAL HONOR DITERIMA</h6>
                    <div class="d-flex align-items-baseline">
                        <h2 class="fw-bold mb-0 me-2" style="font-size: 2.5rem;">Rp {{ number_format($totalHonor, 0, ',', '.') }}</h2>
                    </div>
                </div>
                <div class="card-footer border-0 bg-transparent pt-0 pb-3">
                    <div class="small fw-medium" style="font-size: 0.8rem; opacity: 0.9;">
                        <i class="bi bi-wallet2 me-1"></i> Akumulasi honor bulan terpilih
                    </div>
                </div>
            </div>
        </div>
 
        {{-- Card 2: Jumlah Kegiatan (Kuning/Oranye) --}}
        <div class="col-md-6">
            <div class="card border-0 text-white shadow-sm h-100" style="background: linear-gradient(135deg, #ffcf1b 0%, #ff8c00 100%); border-radius: 12px;">
                <div class="card-body pb-2">
                    <h6 class="fw-bold mb-3 text-uppercase" style="font-size: 0.75rem; opacity: 0.9; letter-spacing: 0.5px;">JUMLAH KEGIATAN</h6>
                    <div class="d-flex align-items-baseline">
                        <h2 class="fw-bold mb-0 me-2" style="font-size: 2.5rem;">{{ $jumlahKegiatan }}</h2>
                        <span class="fw-medium" style="font-size: 1rem; opacity: 0.9;">Kegiatan</span>
                    </div>
                </div>
                <div class="card-footer border-0 bg-transparent pt-0 pb-3">
                    <div class="small fw-medium" style="font-size: 0.8rem; opacity: 0.9;">
                        <i class="bi bi-calendar-check-fill me-1"></i> Penugasan aktif bulan ini
                    </div>
                </div>
            </div>
        </div>
    </div>
 
    {{-- Tabel Monitoring Penggunaan Pagu --}}
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px; overflow: hidden;">
        <div class="card-header bg-white border-bottom py-3 px-4">
            <h5 class="fw-bold mb-0 text-dark">Monitoring Penggunaan Pagu</h5>
        </div>
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-borderless mb-0 align-middle">
                    <thead>
                        <tr class="text-muted">
                            <th style="width: 75%; padding-left: 0;">Penggunaan Batas Maksimum</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="py-3" style="padding-left: 0;">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="fw-bold">Rp {{ number_format($totalHonor, 0, ',', '.') }} <span class="text-muted fw-normal">/ Rp {{ number_format($paguMaksimum, 0, ',', '.') }}</span></span>
                                    <span class="fw-bold {{ $textProg }}">{{ number_format($persentase, 1) }}%</span>
                                </div>
                                <div class="progress" style="height: 12px; border-radius: 10px; background-color: #f8f9fa; border: 1px solid #eee;">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated {{ $warnaProg }}" role="progressbar" style="width: {{ $persentase }}%"></div>
                                </div>
                                <small class="text-muted mt-2 d-block">Sisa pagu bulan ini: <strong>Rp {{ number_format($sisaPagu, 0, ',', '.') }}</strong></small>
                            </td>
                            <td class="text-center">
                                <div class="badge {{ $warnaProg }} text-white px-4 py-2 rounded-pill fs-6 fw-bold shadow-sm">{{ $statusTeks }}</div>
                                <div class="small {{ $textProg }} mt-2 fw-bold">{{ $statusDesc }}</div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- TAMBAHAN BARU: Card untuk Visualisasi Tren Pendapatan --}}
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-header bg-white border-bottom py-3 px-4">
                    <h5 class="fw-bold text-dark mb-0">
                        <i class="bi bi-bar-chart-fill me-2 text-primary"></i>Tren Pendapatan 6 Bulan Terakhir
                    </h5>
                </div>
                <div class="card-body p-4">
                    <!-- Elemen <canvas> tempat Chart.js menggambar grafik -->
                    <div style="height: 300px; width: 100%;">
                        <canvas id="grafikPendapatanMitra"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

{{-- TAMBAHAN BARU: Script untuk menggambar Grafik --}}
{{-- TAMBAHAN BARU: Script untuk menggambar Grafik --}}
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // 1. Registrasi Plugin Datalabels secara global
    Chart.register(ChartDataLabels);

    // 2. Menentukan lokasi kanvas di HTML
    const ctx = document.getElementById('grafikPendapatanMitra').getContext('2d');

    // 3. DATA DINAMIS (Dari Database)
    const labelBulan = @json($labelBulanChart);
    const dataPendapatan = @json($dataHonorChart);

    // 4. Konfigurasi Grafik Batang (Bar Chart)
    new Chart(ctx, {
        type: 'bar',
        plugins: [ChartDataLabels], // WAJIB ADA INI
        data: {
            labels: labelBulan,
            datasets: [{
                label: 'Total Honor (Rp)',
                data: dataPendapatan,
                backgroundColor: '#007bff', // Warna biru serasi dengan tema
                borderRadius: 6, // Membuat ujung batang agak melengkung (modern)
                barPercentage: 0.5 // Mengatur ketebalan batang
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            layout: {
                padding: {
                    top: 35 // [REVISI] Memberi ruang atas agar angka tidak terpotong
                }
            },
            plugins: {
                legend: {
                    display: false // Menyembunyikan teks label di atas grafik agar rapi
                },
                // [REVISI] Konfigurasi Teks di atas batang
                datalabels: {
                    anchor: 'end',
                    align: 'top',
                    color: '#444',
                    font: {
                        weight: 'bold',
                        size: 11
                    },
                    formatter: function(value) {
                        if (value == 0) return ''; // Jangan tampilkan label jika 0
                        if (value >= 1000000) return 'Rp ' + (value / 1000000).toLocaleString('id-ID') + ' Jt';
                        if (value >= 1000) return 'Rp ' + (value / 1000).toLocaleString('id-ID') + ' Rb';
                        return 'Rp ' + value.toLocaleString('id-ID');
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let nilai = context.parsed.y;
                            return 'Rp ' + nilai.toLocaleString('id-ID');
                        }
                    }
                }
            },
scales: {
                y: {
                    beginAtZero: true,
                    max: {{ $paguMaksimum }}, // [PERBAIKAN REVISI #7] Set nilai maksimum sumbu Y
                    ticks: {
                        callback: function(value) {
                            if(value >= 1000000) return 'Rp ' + (value/1000000) + ' Jt';
                            if(value >= 1000) return 'Rp ' + (value/1000) + ' Rb';
                            return 'Rp ' + value;
                        }
                    }
                }
            }
        }
    });
});
</script>
@endpush