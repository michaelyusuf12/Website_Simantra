@extends('layouts.master')
@section('title', 'Realisasi Anggaran')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-dark mb-1">Realisasi Anggaran Honorarium</h3>
        <p class="text-muted mb-0">Tahun Anggaran 2026</p>
    </div>

    {{-- Setup Filter Bulan Sama Seperti Halaman Pegawai --}}
    @php
        $bulanIndo = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        $bulanDipilih = request('month'); // Kosong berarti semua bulan
    @endphp

    <div class="dropdown">
        <button class="btn btn-white shadow-sm dropdown-toggle px-4 border bg-white" type="button" data-bs-toggle="dropdown" style="border-radius: 10px;">
            <i class="bi bi-calendar3 me-2 text-primary"></i> 
            {{ $bulanDipilih ? $bulanIndo[(int)$bulanDipilih] : 'Semua Bulan' }}
        </button>
        
        <ul class="dropdown-menu dropdown-menu-end shadow border-0" style="border-radius: 12px; max-height: 300px; overflow-y: auto;">
            <li><h6 class="dropdown-header">Pilih Periode</h6></li>
            <li>
                <a class="dropdown-item {{ !$bulanDipilih ? 'active bg-primary text-white' : '' }}" href="?">
                    Semua Bulan
                </a>
            </li>
            <li><hr class="dropdown-divider"></li>
            @foreach($bulanIndo as $angka => $nama)
                <li>
                    <a class="dropdown-item {{ $bulanDipilih == $angka ? 'active bg-primary text-white' : '' }}" 
                       href="?month={{ $angka }}">
                        {{ $nama }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</div>

    {{-- 1. KARTU RINGKASAN (KPI) --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 border-start border-primary border-4 py-2">
                <div class="card-body">
                    <p class="text-muted mb-1 small fw-bold">TOTAL PAGU ANGGARAN</p>
                    <h4 class="fw-bold mb-0">Rp 500.000.000</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 border-start border-success border-4 py-2">
                <div class="card-body">
                    <p class="text-muted mb-1 small fw-bold">TOTAL REALISASI</p>
                    <h4 class="fw-bold mb-0 text-success">Rp 325.500.000</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 border-start border-warning border-4 py-2">
                <div class="card-body">
                    <p class="text-muted mb-1 small fw-bold">SISA ANGGARAN</p>
                    <h4 class="fw-bold mb-0">Rp 174.500.000</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 border-start border-info border-4 py-2">
                <div class="card-body">
                    <p class="text-muted mb-1 small fw-bold">PERSENTASE SERAPAN</p>
                    <h4 class="fw-bold mb-0 text-info">65.1%</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- 2. GRAFİK APEXCHARTS --}}
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white pt-4 pb-2 border-0">
                    <h6 class="fw-bold mb-0"><i class="bi bi-bar-chart-line-fill me-2 text-primary"></i>Grafik Serapan Bulanan</h6>
                </div>
                <div class="card-body">
                    {{-- Tempat Grafik Muncul --}}
                    <div id="chartRealisasi"></div>
                </div>
            </div>
        </div>

        {{-- 3. TABEL KEGIATAN TERBESAR --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white pt-4 pb-2 border-0">
                    <h6 class="fw-bold mb-0"><i class="bi bi-list-stars me-2 text-warning"></i>Top 3 Kegiatan Terbesar</h6>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush mt-2">
                        <li class="list-group-item d-flex justify-content-between align-items-center py-3 border-0">
                            <div>
                                <h6 class="mb-0 fw-bold">Sensus Ekonomi</h6>
                                <small class="text-muted">120 Mitra</small>
                            </div>
                            <span class="badge bg-primary rounded-pill px-3 py-2">Rp 150 Jt</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center py-3 border-0">
                            <div>
                                <h6 class="mb-0 fw-bold">Survei Susenas</h6>
                                <small class="text-muted">85 Mitra</small>
                            </div>
                            <span class="badge bg-success rounded-pill px-3 py-2">Rp 95 Jt</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center py-3 border-0">
                            <div>
                                <h6 class="mb-0 fw-bold">Survei Angkatan Kerja</h6>
                                <small class="text-muted">45 Mitra</small>
                            </div>
                            <span class="badge bg-warning text-dark rounded-pill px-3 py-2">Rp 60 Jt</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- Memanggil Library ApexCharts --}}
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Konfigurasi Grafik ApexCharts
        var options = {
            series: [{
                name: 'Realisasi Honor (Rp)',
                data: [15000000, 25000000, 40000000, 80000000, 60000000, 45000000, 60500000] // Data Dummy Jan-Jul
            }],
            chart: {
                type: 'area', // Tipe grafik area (garis dengan arsiran bawah)
                height: 350,
                toolbar: { show: false },
                fontFamily: 'inherit'
            },
            colors: ['#0d6efd'], // Warna Biru Primary Bootstrap
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth', width: 3 }, // Garis melengkung halus
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.4,
                    opacityTo: 0.05,
                    stops: [0, 90, 100]
                }
            },
            xaxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul'],
                tooltip: { enabled: false }
            },
            yaxis: {
                labels: {
                    formatter: function (value) {
                        return "Rp " + (value / 1000000) + " Jt"; // Format angka sumbu Y jadi jutaan
                    }
                }
            }
        };

        // Render Grafik ke dalam div #chartRealisasi
        var chart = new ApexCharts(document.querySelector("#chartRealisasi"), options);
        chart.render();
    });
</script>
@endpush