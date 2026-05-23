@extends('layouts.master')

@section('content')
<div class="container-fluid py-4">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Dashboard Kepala BPS</h3>
            <p class="textb-muted small m-0">Selamat Datang, <b>Kepala BPS</b>. Berikut adalah ringkasan performa & honorarium mitra.</p>
        </div>
        
        <div class="dropdown shadow-sm">
            @php
                $bulanIndo = [
                    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                    5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                    9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                ];
            @endphp
            
            <button class="btn btn-white dropdown-toggle border-primary bg-white fw-bold text-primary shadow-sm px-4" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false" style="border-radius: 8px;">
                <i class="bi bi-calendar3 me-2"></i> {{ $bulanIndo[(int)$bulanDipilih] }} {{ $tahunDipilih }}
            </button>
            
            <div class="dropdown-menu dropdown-menu-end p-3 shadow-lg border-0" style="width: 320px; border-radius: 12px;">
                <div class="text-center mb-3 pb-2 border-bottom">
                    <span class="fw-bold text-dark" style="font-size: 0.95rem;"><i class="bi bi-funnel-fill me-1 text-primary"></i> Pilih Tahun & Bulan</span>
                </div>
                
                <div class="accordion accordion-flush" id="accordionTahun">
                    @foreach($daftarTahun as $th)
                    <div class="accordion-item border-0 mb-2">
                        <h2 class="accordion-header" id="heading-{{ $th }}">
                            <button class="accordion-button {{ $tahunDipilih == $th ? '' : 'collapsed' }} py-2 px-3 fw-bold rounded bg-light border shadow-sm" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $th }}" style="font-size: 0.9rem;">
                                Tahun {{ $th }}
                            </button>
                        </h2>
                        <div id="collapse-{{ $th }}" class="accordion-collapse collapse {{ $tahunDipilih == $th ? 'show' : '' }}" data-bs-parent="#accordionTahun">
                            <div class="accordion-body p-2 border border-top-0 rounded-bottom bg-white">
                                <div class="row g-2">
                                    @foreach($bulanIndo as $angka => $nama)
                                    <div class="col-4">
                                        <a href="?year={{ $th }}&month={{ $angka }}" class="btn btn-sm w-100 {{ ($tahunDipilih == $th && $bulanDipilih == $angka) ? 'btn-primary text-white fw-bold shadow' : 'btn-outline-primary' }}" style="font-size: 0.75rem; border-radius: 6px;">
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

    <div class="row g-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-header bg-white border-0 pt-3 fw-bold text-dark">
                    <i class="bi bi-pie-chart me-2 text-primary"></i>Proporsi Alokasi Honor Per Fungsi
                </div>
                <div class="card-body d-flex justify-content-center align-items-center" style="height: 320px;">
                    <canvas id="chartFungsiHead"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-header bg-white border-0 pt-3 fw-bold text-dark">
                    <i class="bi bi-pie-chart-fill me-2 text-success"></i>Proporsi Alokasi Honor Per Kegiatan
                </div>
                <div class="card-body d-flex justify-content-center align-items-center" style="height: 320px;">
                    <canvas id="chartKegiatanHead"></canvas>
                </div>
            </div>
        </div>
    </div>

</div>

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
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    
    // 1. DATA RAW DARI LARAVEL BACKEND
    const dataFungsiRaw = @json($honorPerFungsi);
    const dataKegiatanRaw = @json($honorPerKegiatan);

    // Parse Data Fungsi
    const labelsFungsi = Object.keys(dataFungsiRaw);
    const valuesFungsi = Object.values(dataFungsiRaw).map(item => item.total);
    const mitrasFungsi = Object.values(dataFungsiRaw).map(item => item.mitra);

    // Parse Data Kegiatan
    const labelsKegiatan = Object.keys(dataKegiatanRaw);
    const valuesKegiatan = Object.values(dataKegiatanRaw).map(item => item.total);
    const mitrasKegiatan = Object.values(dataKegiatanRaw).map(item => item.mitra);

    const bootstrapModal = new bootstrap.Modal(document.getElementById('modalDetailChartHead'));
    const listContainer = document.getElementById('listMitraChartHead');
    const titleModal = document.getElementById('titleModalChartHead');

    // Fungsi Tampilkan Pop-up Nama Mitra
    function showMitraModal(title, mitraList) {
        titleModal.innerHTML = `<i class="bi bi-people me-2"></i> Mitra di Sektor: ${title}`;
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

    // 2. RENDER GRAPH PER-FUNGSI
    const ctxFungsi = document.getElementById('chartFungsiHead').getContext('2d');
    const chartFungsi = new Chart(ctxFungsi, {
        type: 'pie',
        data: {
            labels: labelsFungsi,
            datasets: [{
                data: valuesFungsi,
                backgroundColor: colorsPalette
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } } },
            onClick: (e, activeElements) => {
                if (activeElements && activeElements.length > 0) {
                    const idx = activeElements[0].index;
                    const sectorLabel = labelsFungsi[idx];
                    const listMitra = mitrasFungsi[idx];
                    showMitraModal(sectorLabel, listMitra);
                }
            }
        }
    });

    // 3. RENDER GRAPH PER-KEGIATAN
    const ctxKegiatan = document.getElementById('chartKegiatanHead').getContext('2d');
    const chartKegiatan = new Chart(ctxKegiatan, {
        type: 'pie',
        data: {
            labels: labelsKegiatan,
            datasets: [{
                data: valuesKegiatan,
                backgroundColor: colorsPalette
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } }, 
            onClick: (e, activeElements) => {
                if (activeElements && activeElements.length > 0) {
                    const idx = activeElements[0].index;
                    const sectorLabel = labelsKegiatan[idx];
                    const listMitra = mitrasKegiatan[idx];
                    showMitraModal(sectorLabel, listMitra);
                }
            }
        }
    });
});
</script>
@endpush