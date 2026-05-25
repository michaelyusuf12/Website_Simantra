@extends('layouts.master')

@section('content')
<div class="container-fluid py-4">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Dashboard Kepala BPS</h3>
            <p class="text-muted small m-0">Selamat Datang, <b>Kepala BPS</b>. Berikut adalah ringkasan performa & honorarium mitra.</p>
        </div>
        
        <div class="dropdown shadow-sm">
            @php
                $bulanIndo = [
                    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                    5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                    9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                ];
                // Menyamakan variabel agar tabel penugasan berfungsi sempurna
                $bulanPilih = $bulanDipilih ?? request('month', date('n')); 
                $tahunPilih = $tahunDipilih ?? request('year', date('Y'));
            @endphp
            
            <button class="btn btn-white dropdown-toggle border-primary bg-white fw-bold text-primary shadow-sm px-4" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false" style="border-radius: 8px;">
                <i class="bi bi-calendar3 me-2"></i> {{ $bulanIndo[(int)$bulanPilih] }} {{ $tahunPilih }}
            </button>
            
            <div class="dropdown-menu dropdown-menu-end p-3 shadow-lg border-0" style="width: 320px; border-radius: 12px;">
                <div class="text-center mb-3 pb-2 border-bottom">
                    <span class="fw-bold text-dark" style="font-size: 0.95rem;"><i class="bi bi-funnel-fill me-1 text-primary"></i> Pilih Tahun & Bulan</span>
                </div>
                
                <div class="accordion accordion-flush" id="accordionTahun">
                    @foreach($daftarTahun as $th)
                    <div class="accordion-item border-0 mb-2">
                        <h2 class="accordion-header" id="heading-{{ $th }}">
                            <button class="accordion-button {{ $tahunPilih == $th ? '' : 'collapsed' }} py-2 px-3 fw-bold rounded bg-light border shadow-sm" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $th }}" style="font-size: 0.9rem;">
                                Tahun {{ $th }}
                            </button>
                        </h2>
                        <div id="collapse-{{ $th }}" class="accordion-collapse collapse {{ $tahunPilih == $th ? 'show' : '' }}" data-bs-parent="#accordionTahun">
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

    {{-- ========================================== --}}
    {{-- KOTAK STATISTIK UTAMA --}}
    {{-- ========================================== --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-primary text-white p-3 h-100" style="border-radius: 12px;">
                <div class="d-flex align-items-center justify-content-between h-100">
                    <div>
                        <span class="small text-white-50 d-block mb-1 fw-bold">TOTAL ANGGARAN BULAN INI</span>
                        <h3 class="fw-bold mb-0">Rp {{ number_format($totalHonor, 0, ',', '.') }}</h3>
                    </div>
                    <div class="fs-1 text-white-50"><i class="bi bi-wallet2"></i></div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-success text-white p-3 h-100" style="border-radius: 12px;">
                <div class="d-flex align-items-center justify-content-between h-100">
                    <div>
                        <span class="small text-white-50 d-block mb-1 fw-bold">RASIO MITRA BERHONOR</span>
                        <h3 class="fw-bold mb-0">{{ $persentaseMitraBerhonor }}%</h3>
                        <span class="small text-white-50" style="font-size: 0.75rem;">Dari total {{ $totalMitra }} mitra terdaftar</span>
                    </div>
                    <div class="fs-1 text-white-50"><i class="bi bi-pie-chart-fill"></i></div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-info text-white p-3 h-100" style="border-radius: 12px;">
                <div class="d-flex align-items-center justify-content-between h-100">
                    <div>
                        <span class="small text-white-50 d-block mb-1 fw-bold">SURAT TUGAS DISETUJUI</span>
                        <h3 class="fw-bold mb-0">{{ $disetujui }} <span class="fs-6 fw-normal text-white-50">Kontrak</span></h3>
                        <span class="small text-white-50" style="font-size: 0.75rem;">{{ $menunggu }} Kontrak menunggu PPK</span>
                    </div>
                    <div class="fs-1 text-white-50"><i class="bi bi-file-earmark-check"></i></div>
                </div>
            </div>
        </div>
    </div>

    {{-- ========================================== --}}
    {{-- TAMBAHAN: BAR CHART & RASIO MITRA --}}
    {{-- ========================================== --}}
    <div class="row g-4 mb-4">
        {{-- Kolom Kiri: Bar Chart --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 15px;">
                <div class="card-header bg-white border-bottom py-3" style="border-radius: 15px 15px 0 0;">
                    <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-bar-chart-fill text-primary me-2"></i> Top 5 Mitra (Honor Tertinggi Bulan Ini)</h6>
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
                            <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 pb-1" style="cursor:pointer;" onclick="showMitraListModal('Sudah Berhonor')">
                                <span><i class="bi bi-circle-fill text-success me-2"></i> Sudah Berhonor</span>
                                <span class="fw-bold fs-6 text-primary text-decoration-underline">{{ $mitraBerhonor ?? 0 }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 pt-1" style="cursor:pointer;" onclick="showMitraListModal('Belum Bekerja')">
                                <span><i class="bi bi-circle-fill text-danger me-2"></i> Belum Bekerja</span>
                                <span class="fw-bold fs-6 text-primary text-decoration-underline">{{ $mitraTanpaHonor ?? 0 }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
       {{-- ========================================== --}}
    {{-- CHART FUNGSI & KEGIATAN --}}
    {{-- ========================================== --}}
    <div class="row g-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                <div class="card-header bg-white border-0 pt-3 fw-bold text-dark">
                    <i class="bi bi-pie-chart me-2 text-primary"></i>Proporsi Alokasi Honor Per Fungsi
                </div>
                <div class="card-body d-flex justify-content-center align-items-center" style="height: 320px;">
                    <canvas id="chartFungsiHead"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                <div class="card-header bg-white border-0 pt-3 fw-bold text-dark">
                    <i class="bi bi-pie-chart-fill me-2 text-success"></i>Proporsi Alokasi Honor Per Kegiatan
                </div>
                <div class="card-body d-flex justify-content-center align-items-center" style="height: 320px;">
                    <canvas id="chartKegiatanHead"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- ========================================== --}}
    {{-- TAMBAHAN: TABEL DAFTAR PENUGASAN --}}
    {{-- ========================================== --}}
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
        <div class="card-header bg-white border-bottom py-3" style="border-radius: 15px 15px 0 0;">
            <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-table text-primary me-2"></i> Daftar Penugasan Keseluruhan - Bulan {{ $bulanIndo[(int)$bulanPilih] }} {{ $tahunPilih }}</h6>
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
                            <td class="text-center">{{ (method_exists($daftarPenugasan, 'firstItem') ? $daftarPenugasan->firstItem() : 1) + $index }}</td>
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
                                Tidak ada data penugasan bulan ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div> 
            
            <div class="d-flex justify-content-end mt-4 px-3">
                @if(method_exists($daftarPenugasan, 'hasPages') && $daftarPenugasan->hasPages())
                    {{ $daftarPenugasan->links() }}
                @endif
            </div>
        </div> 
    </div>

 

</div>

{{-- ========================================== --}}
{{-- SEMUA MODAL POP-UP DITAMPUNG DI SINI --}}
{{-- ========================================== --}}

{{-- Modal Daftar Mitra (Klik Fungsi/Kegiatan Bawaan) --}}
<div class="modal fade" id="modalDetailChartHead" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 12px;">
            <div class="modal-header bg-dark text-white py-2" style="border-radius: 12px 12px 0 0;">
                <h6 class="modal-title fw-bold" id="titleModalChartHead"><i class="bi bi-people me-2"></i>Daftar Nama Mitra</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3">
                <p class="text-muted small mb-2">Daftar nama mitra yang berkontribusi pada sektor ini di periode berjalan:</p>
                <div style="max-height: 250px; overflow-y: auto;">
                    <ul class="list-group list-group-flush small" id="listMitraChartHead">
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Daftar Mitra (Klik Rasio Keterlibatan) --}}
<div class="modal fade" id="modalDaftarMitraRasio" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 12px;">
            <div class="modal-header bg-dark text-white py-3" style="border-radius: 12px 12px 0 0;">
                <h6 class="modal-title fw-bold" id="titleModalDaftarMitra"><i class="bi bi-people me-2"></i>Daftar Mitra</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div style="max-height: 300px; overflow-y: auto;">
                    <ul class="list-group list-group-flush" id="listMitraRasio"></ul>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>

<script>
    // --- Variabel & Fungsi Rasio Mitra ---
    const mitraBerhonorArray = @json($chartRasio['berhonor'] ?? []);
    const mitraBelumBerhonorArray = @json($chartRasio['belum_berhonor'] ?? []);
    let rasioModalInstance = null;

    function showMitraListModal(kategori) {
        if(!rasioModalInstance) rasioModalInstance = new bootstrap.Modal(document.getElementById('modalDaftarMitraRasio'));
        
        document.getElementById('titleModalDaftarMitra').innerHTML = `<i class="bi bi-people me-2"></i>Mitra ${kategori}`;
        const ul = document.getElementById('listMitraRasio');
        ul.innerHTML = '';
        
        const dataList = (kategori === 'Sudah Berhonor') ? mitraBerhonorArray : mitraBelumBerhonorArray;
        const iconClass = (kategori === 'Sudah Berhonor') ? 'bi-check-circle-fill text-success' : 'bi-x-circle-fill text-danger';
        
        if(dataList && dataList.length > 0) {
            dataList.forEach(nama => ul.innerHTML += `<li class="list-group-item px-4 py-2"><i class="bi ${iconClass} me-2"></i> <span class="fw-bold text-dark">${nama}</span></li>`);
        } else {
            ul.innerHTML = `<li class="list-group-item text-center text-muted py-4">Tidak ada data mitra</li>`;
        }
        rasioModalInstance.show();
    }

document.addEventListener("DOMContentLoaded", function () {
    
    // Registrasi plugin datalabels untuk bar chart
    Chart.register(ChartDataLabels);

    // ==========================================
    // 1. RENDER CHART BAR (TOP 5 MITRA)
    // ==========================================
    const labelMitra = {!! json_encode($topMitraLabels ?? []) !!};
    const dataHonor = {!! json_encode($topMitraHonor ?? []) !!};
    const ctxBar = document.getElementById('topMitraChart');
    
    if(ctxBar) {
        if(labelMitra.length === 0) {
            ctxBar.parentElement.innerHTML = '<div class="h-100 d-flex align-items-center justify-content-center text-muted"><p><i class="bi bi-bar-chart me-2"></i>Belum ada data penugasan pada filter ini.</p></div>';
        } else {
            new Chart(ctxBar.getContext('2d'), {
                type: 'bar',
                plugins: [ChartDataLabels],
                data: {
                    labels: labelMitra,
                    datasets: [{
                        label: 'Total Honor (Rp)',
                        data: dataHonor,
                        backgroundColor: ['rgba(0, 123, 255, 0.7)', 'rgba(40, 167, 69, 0.7)', 'rgba(255, 193, 7, 0.7)', 'rgba(23, 162, 184, 0.7)', 'rgba(111, 66, 193, 0.7)'],
                        borderColor: ['rgba(0, 123, 255, 1)', 'rgba(40, 167, 69, 1)', 'rgba(255, 193, 7, 1)', 'rgba(23, 162, 184, 1)', 'rgba(111, 66, 193, 1)'],
                        borderWidth: 1, borderRadius: 4
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    layout: { padding: { top: 30 } },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { callback: function(value) { return 'Rp ' + Number(value).toLocaleString('id-ID', { maximumFractionDigits: 0 }); } }
                        }
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) { return context.dataset.label + ': Rp ' + context.parsed.y.toLocaleString('id-ID'); }
                            }
                        },
                        datalabels: {
                            anchor: 'end', align: 'top', color: '#444',
                            font: { weight: 'bold', size: 11 },
                            formatter: function(value) { return 'Rp ' + Number(value).toLocaleString('id-ID', { maximumFractionDigits: 0 }); }
                        }
                    }
                }
            });
        }
    }

    // ==========================================
    // 2. RENDER PIE CHART (RASIO KETERLIBATAN MITRA)
    // ==========================================
    const mitraBerhonor = {{ $mitraBerhonor ?? 0 }};
    const mitraTanpaHonor = {{ $mitraTanpaHonor ?? 0 }};
    const ctxPieRasio = document.getElementById('rasioMitraChart');
    if (ctxPieRasio) {
        new Chart(ctxPieRasio.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['Sudah Berhonor', 'Belum Bekerja'],
                datasets: [{
                    data: [mitraBerhonor, mitraTanpaHonor],
                    backgroundColor: ['#1cc88a', '#e74a3b'], 
                    borderWidth: 2, hoverOffset: 6
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false, cutout: '70%',
                plugins: {
                    legend: { display: false }, datalabels: { display: false }, 
                    tooltip: { callbacks: { label: function(context) { return ' ' + context.label + ': ' + context.raw + ' Orang'; } } }
                },
                onClick: (e, activeElements) => {
                    if (activeElements.length > 0) {
                        showMitraListModal(activeElements[0].index === 0 ? 'Sudah Berhonor' : 'Belum Bekerja');
                    }
                }
            }
        });
    }

    // ==========================================
    // 3. DATA & LOGIKA CHART FUNGSI DAN KEGIATAN BAWAAN
    // ==========================================
    const dataFungsiRaw = @json($honorPerFungsi ?? []);
    const dataKegiatanRaw = @json($honorPerKegiatan ?? []);

    const labelsFungsi = Object.keys(dataFungsiRaw);
    const valuesFungsi = Object.values(dataFungsiRaw).map(item => item.total);
    const mitrasFungsi = Object.values(dataFungsiRaw).map(item => item.mitra);

    const labelsKegiatan = Object.keys(dataKegiatanRaw);
    const valuesKegiatan = Object.values(dataKegiatanRaw).map(item => item.total);
    const mitrasKegiatan = Object.values(dataKegiatanRaw).map(item => item.mitra);

    const bootstrapModal = new bootstrap.Modal(document.getElementById('modalDetailChartHead'));
    const listContainer = document.getElementById('listMitraChartHead');
    const titleModal = document.getElementById('titleModalChartHead');

    function showMitraModal(title, mitraList) {
        titleModal.innerHTML = `<i class="bi bi-people me-2"></i> Mitra di: ${title}`;
        listContainer.innerHTML = '';
        
        if (mitraList && mitraList.length > 0) {
            mitraList.forEach(nama => {
                const li = document.createElement('li');
                li.className = 'list-group-item d-flex align-items-center py-2';
                li.innerHTML = `<i class="bi bi-person-check-fill text-success me-2"></i> ${nama}`;
                listContainer.appendChild(li);
            });
        } else {
            listContainer.innerHTML = `<li class="list-group-item text-center text-muted">Tidak ada data mitra</li>`;
        }
        bootstrapModal.show();
    }

    const colorsPalette = ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796', '#5a5c69', '#f8f9fc'];

    // --- RENDER GRAPH PER-FUNGSI ---
    if (document.getElementById('chartFungsiHead')) {
        const ctxFungsi = document.getElementById('chartFungsiHead').getContext('2d');
        new Chart(ctxFungsi, {
            type: 'doughnut',
            data: {
                labels: labelsFungsi,
                datasets: [{
                    data: valuesFungsi,
                    backgroundColor: colorsPalette,
                    borderWidth: 2, hoverOffset: 10 
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false, cutout: '65%', 
                plugins: { 
                    legend: { display: true, position: 'bottom', labels: { padding: 20, boxWidth: 12, font: { size: 11 } } },
                    datalabels: { display: false },
                    tooltip: { callbacks: { label: function(context) { return ' ' + new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(context.raw); } } }
                },
                onClick: (e, activeElements) => {
                    if (activeElements && activeElements.length > 0) {
                        const idx = activeElements[0].index;
                        showMitraModal('Fungsi ' + labelsFungsi[idx], mitrasFungsi[idx]);
                    }
                }
            }
        });
    }

    // --- RENDER GRAPH PER-KEGIATAN ---
    if (document.getElementById('chartKegiatanHead')) {
        const ctxKegiatan = document.getElementById('chartKegiatanHead').getContext('2d');
        new Chart(ctxKegiatan, {
            type: 'doughnut',
            data: {
                labels: labelsKegiatan,
                datasets: [{
                    data: valuesKegiatan,
                    backgroundColor: colorsPalette.slice().reverse(), 
                    borderWidth: 2, hoverOffset: 10
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false, cutout: '65%', 
                plugins: { 
                    legend: { display: false }, 
                    datalabels: { display: false },
                    tooltip: { callbacks: { label: function(context) { return ' ' + new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(context.raw); } } }
                }, 
                onClick: (e, activeElements) => {
                    if (activeElements && activeElements.length > 0) {
                        const idx = activeElements[0].index;
                        showMitraModal('Kegiatan ' + labelsKegiatan[idx], mitrasKegiatan[idx]);
                    }
                }
            }
        });
    }
});
</script>
@endpush