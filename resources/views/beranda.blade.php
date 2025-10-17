@extends('layouts.master')
@section('title', 'Beranda')
@section('content')

    {{-- Header: Judul dan Dropdown Bulan --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">Beranda</h3>
        <form action="{{ route('beranda') }}" method="GET" id="monthFilterForm">
            <select class="form-select w-auto" name="month" onchange="this.form.submit()">
                <option value="1" {{ request('month', date('n')) == 1 ? 'selected' : '' }}>Januari</option>
                <option value="2" {{ request('month', date('n')) == 2 ? 'selected' : '' }}>Februari</option>
                <option value="3" {{ request('month', date('n')) == 3 ? 'selected' : '' }}>Maret</option>
                <option value="4" {{ request('month', date('n')) == 4 ? 'selected' : '' }}>April</option>
                <option value="5" {{ request('month', date('n')) == 5 ? 'selected' : '' }}>Mei</option>
                <option value="6" {{ request('month', date('n')) == 6 ? 'selected' : '' }}>Juni</option>
                <option value="7" {{ request('month', date('n')) == 7 ? 'selected' : '' }}>Juli</option>
                <option value="8" {{ request('month', date('n')) == 8 ? 'selected' : '' }}>Agustus</option>
                <option value="9" {{ request('month', date('n')) == 9 ? 'selected' : '' }}>September</option>
                <option value="10" {{ request('month', date('n')) == 10 ? 'selected' : '' }}>Oktober</option>
                <option value="11" {{ request('month', date('n')) == 11 ? 'selected' : '' }}>November</option>
                <option value="12" {{ request('month', date('n')) == 12 ? 'selected' : '' }}>Desember</option>
            </select>
        </form>
    </div>

    {{-- Cards Ringkasan --}}
    <div class="row"> 
        <div class="col-md-4 mb-4">
            <div class="card text-center shadow-sm border-0 h-100" style="background:#0d6efd; color:white;">
                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                    <i class="bi bi-people fs-1 mb-2"></i>
                    <h4 class="mt-auto">{{ $totalMitra }} Mitra</h4>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card text-center shadow-sm border-0 h-100" style="background:#fd7e14; color:white;">
                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                    <i class="bi bi-pie-chart fs-1 mb-2"></i>
                    <h4 class="mt-auto">{{ $surveyAktif }} Survey Aktif</h4>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card text-center shadow-sm border-0 h-100" style="background:#198754; color:white;">
                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                    <i class="bi bi-check-circle fs-1 mb-2"></i>
                    <h4 class="mt-auto">{{ $surveySelesai }} Survey Selesai</h4>
                </div>
            </div>
        </div>
    </div>

    {{-- Menghitung tinggi grafik secara dinamis --}}
    @php
        $barCount = count($progressSurveys);
        $chartHeight = ($barCount > 0) ? ($barCount * 70) + 80 : 200;
    @endphp

    {{-- Grafik Progress Survey (Menggunakan Chart.js) --}}
    <div class="card shadow-sm border-0 mt-4">
        <div class="card-header bg-white">
            <h5 class="mb-0">Progres Survei Bulan Ini</h5>
        </div>
        <div class="card-body" style="height: {{ $chartHeight }}px;">
            <canvas id="surveyProgressChart"></canvas>
        </div>
    </div>
@endsection

@push('scripts')
{{-- Memuat library Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const surveyData = @json($progressSurveys ?? []); 

    const surveyLabels = surveyData.map(survey => survey.nama);
    const surveyProgress = surveyData.map(survey => survey.progress);
    
    const ctx = document.getElementById('surveyProgressChart').getContext('2d');
    
    // Hancurkan chart lama sebelum membuat yang baru (penting untuk filter bulan)
    let chartStatus = Chart.getChart("surveyProgressChart"); 
    if (chartStatus != undefined) {
      chartStatus.destroy();
    }
    
    // Buat grafik baru
    new Chart(ctx, {
        type: 'bar', 
        data: {
            labels: surveyLabels, 
            datasets: [{
                label: 'Progres Selesai (%)', 
                data: surveyProgress, 
                backgroundColor: [ 
                    'rgba(54, 162, 235, 0.6)', 'rgba(255, 159, 64, 0.6)', 
                    'rgba(75, 192, 192, 0.6)', 'rgba(255, 99, 132, 0.6)', 
                    'rgba(153, 102, 255, 0.6)', 'rgba(255, 205, 86, 0.6)' 
                ],
                borderColor: [
                    'rgba(54, 162, 235, 1)', 'rgba(255, 159, 64, 1)', 
                    'rgba(75, 192, 192, 1)', 'rgba(255, 99, 132, 1)', 
                    'rgba(153, 102, 255, 1)', 'rgba(255, 205, 86, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            indexAxis: 'y', // Membuat grafik menjadi horizontal
            responsive: true,
            maintainAspectRatio: false, // Penting agar grafik mengisi div
            scales: {
                x: {
                    beginAtZero: true, 
                    max: 100, 
                    ticks: {
                        callback: function(value) {
                            return value + '%'; 
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    display: false 
                },
                 tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            if (context.parsed.x !== null) {
                                label += context.parsed.x + '%';
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