@extends('layouts.master')

@section('title', 'Beranda PPK')

@section('content')
<div class="container-fluid py-4">
    
    {{-- ========================================== --}}
    {{-- HEADER & FILTER PERIODE ACCORDION --}}
    {{-- ========================================== --}}
    @php
        $bulanIndo = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        $bulanPilih = $bulanDipilih ?? request('month', date('n')); 
        $tahunPilih = $tahunDipilih ?? request('year', date('Y'));
        
        $listTahun = $daftarTahun ?? range(2024, date('Y') + 1);
    @endphp

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-0">Beranda PPK</h2>
            <p class="text-muted small mb-0">Selamat Datang, <b>Pejabat Pembuat Komitmen</b>.</p>
        </div>
        
        <div class="dropdown shadow-sm">
            <button class="btn btn-white dropdown-toggle border-primary bg-white fw-bold text-primary shadow-sm px-4" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false" style="border-radius: 8px;">
                <i class="bi bi-calendar3 me-2"></i> {{ $bulanIndo[(int)$bulanPilih] }} {{ $tahunPilih }}
            </button>
            
            <div class="dropdown-menu dropdown-menu-end p-3 shadow-lg border-0" style="width: 320px; border-radius: 12px;">
                <div class="text-center mb-3 pb-2 border-bottom">
                    <span class="fw-bold text-dark" style="font-size: 0.95rem;"><i class="bi bi-funnel-fill me-1 text-primary"></i> Pilih Tahun & Bulan</span>
                </div>
                
                <div class="accordion accordion-flush" id="accordionTahunPPK">
                    @foreach($listTahun as $th)
                    <div class="accordion-item border-0 mb-2">
                        <h2 class="accordion-header" id="heading-{{ $th }}">
                            <button class="accordion-button {{ $tahunPilih == $th ? '' : 'collapsed' }} py-2 px-3 fw-bold rounded bg-light border shadow-sm" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $th }}" style="font-size: 0.9rem;">
                                Tahun {{ $th }}
                            </button>
                        </h2>
                        <div id="collapse-{{ $th }}" class="accordion-collapse collapse {{ $tahunPilih == $th ? 'show' : '' }}" data-bs-parent="#accordionTahunPPK">
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
    {{-- EMPAT KOTAK STATISTIK UTAMA --}}
    {{-- ========================================== --}}
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4 mb-4 flex-stretch"> 
        <div class="col">
            <div class="card border-0 text-white shadow-sm h-100" style="background: linear-gradient(135deg, #00d2ff 0%, #007bff 100%); border-radius: 12px;">
                <div class="card-body pb-2">
                    <h6 class="fw-bold mb-3 text-uppercase" style="font-size: 0.75rem; opacity: 0.9; letter-spacing: 0.5px;">RASIO MITRA BERHONOR</h6>
                    <div class="d-flex align-items-baseline">
                        <h2 class="fw-bold mb-0 me-2" style="font-size: 2.5rem;">{{ $persentaseMitraBerhonor ?? 0 }}%</h2>
                    </div>
                </div>
                <div class="card-footer border-0 bg-transparent pt-0 pb-3">
                    <div class="small fw-medium" style="font-size: 0.8rem; opacity: 0.9;">
                        <i class="bi bi-pie-chart-fill me-1"></i> Dari total {{ $totalMitra ?? 0 }} Mitra Terdaftar
                    </div>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card border-0 text-white shadow-sm h-100" style="background: linear-gradient(135deg, #ffcf1b 0%, #ff8c00 100%); border-radius: 12px;">
                <div class="card-body pb-2">
                    <h6 class="fw-bold mb-3 text-uppercase" style="font-size: 0.75rem; opacity: 0.9; letter-spacing: 0.5px;">MENUNGGU APPROVAL</h6>
                    <div class="d-flex align-items-baseline">
                        <h2 class="fw-bold mb-0 me-2" style="font-size: 2.5rem;">{{ $menunggu ?? 0 }}</h2>
                        <span class="fw-medium" style="font-size: 1rem; opacity: 0.9;">Kontrak</span>
                    </div>
                </div>
                <div class="card-footer border-0 bg-transparent pt-0 pb-3">
                    <div class="small fw-medium" style="font-size: 0.8rem; opacity: 0.9;">
                        <i class="bi bi-pen-fill me-1"></i> Butuh review & persetujuan Anda
                    </div>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card border-0 text-white shadow-sm h-100" style="background: linear-gradient(135deg, #20c997 0%, #11998e 100%); border-radius: 12px;">
                <div class="card-body pb-2">
                    <h6 class="fw-bold mb-3 text-uppercase" style="font-size: 0.75rem; opacity: 0.9; letter-spacing: 0.5px;">KONTRAK DISETUJUI</h6>
                    <div class="d-flex align-items-baseline">
                        <h2 class="fw-bold mb-0 me-2" style="font-size: 2.5rem;">{{ $disetujui ?? 0 }}</h2>
                        <span class="fw-medium" style="font-size: 1rem; opacity: 0.9;">Kontrak</span>
                    </div>
                </div>
                <div class="card-footer border-0 bg-transparent pt-0 pb-3">
                    <div class="small fw-medium" style="font-size: 0.8rem; opacity: 0.9;">
                        <i class="bi bi-check-circle-fill me-1"></i> Siap dicetak oleh pegawai
                    </div>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card border-0 text-white shadow-sm h-100" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 12px;">
                <div class="card-body pb-2">
                    <h6 class="fw-bold mb-3 text-uppercase" style="font-size: 0.75rem; opacity: 0.9; letter-spacing: 0.5px;">TOTAL HONORARIUM</h6>
                    <div class="d-flex align-items-baseline">
                        <h3 class="fw-bold mb-0 me-2" style="font-size: 1.8rem;">Rp {{ number_format($totalHonor ?? 0, 0, ',', '.') }}</h3>
                    </div>
                </div>
                <div class="card-footer border-0 bg-transparent pt-0 pb-3">
                    <div class="small fw-medium" style="font-size: 0.8rem; opacity: 0.9;">
                        <i class="bi bi-cash-stack me-1"></i> Estimasi pengeluaran bulan ini
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ========================================== --}}
    {{-- TABEL SHORTCUT APPROVAL --}}
    {{-- ========================================== --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold text-dark mb-0"><i class="bi bi-clock-history me-2 text-warning"></i>5 Surat Tugas Menunggu Persetujuan Anda</h6>
                    <a href="{{ route('ppk.approval') }}" class="btn btn-sm btn-outline-primary px-3 shadow-sm" style="border-radius: 6px;">Lihat Semua & Setujui</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light small text-uppercase">
                                <tr>
                                    <th class="ps-4">Nama Mitra</th>
                                    <th>No. Surat Tugas</th>
                                    <th class="text-end">Total Honor</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($shortcutApproval ?? [] as $spk)
                                    <tr>
                                        <td class="ps-4 fw-bold text-dark">{{ $spk->mitra->nama_petugas ?? 'Data Dihapus' }}</td>
                                        <td class="text-muted small">{{ $spk->no_surat }}</td>
                                        <td class="text-end fw-bold text-success pe-4">Rp {{ number_format($spk->total_nilai_perjanjian, 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="text-center py-5 text-muted"><i class="bi bi-check-circle fs-2 d-block mb-2 text-success"></i> Semua surat tugas sudah Anda setujui.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ========================================== --}}
    {{-- DUA CHART INTERAKTIF (BAWAH) --}}
    {{-- ========================================== --}}
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="fw-bold text-dark mb-0">
                        <i class="bi bi-pie-chart-fill me-2 text-info"></i>Honorarium Berdasarkan Fungsi
                        <small class="d-block text-muted mt-1" style="font-size: 0.75rem; font-weight: normal;">(Klik pada grafik untuk melihat daftar nama mitra)</small>
                    </h6>
                </div>
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                    @if(!empty($honorPerFungsi))
                        <div style="width: 100%; max-width: 300px; height: 300px;">
                            <canvas id="chartHonorFungsi"></canvas>
                        </div>
                    @else
                        <div class="text-center text-muted py-5">
                            <i class="bi bi-pie-chart fs-1 d-block mb-2 opacity-50"></i> Belum ada data.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="fw-bold text-dark mb-0">
                        <i class="bi bi-pie-chart-fill me-2 text-success"></i>Honorarium Berdasarkan Kegiatan
                        <small class="d-block text-muted mt-1" style="font-size: 0.75rem; font-weight: normal;">(Klik pada grafik untuk melihat daftar nama mitra)</small>
                    </h6>
                </div>
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                    @if(!empty($honorPerKegiatan))
                        <div style="width: 100%; max-width: 300px; height: 300px;">
                            <canvas id="chartHonorKegiatan"></canvas>
                        </div>
                    @else
                        <div class="text-center text-muted py-5">
                            <i class="bi bi-pie-chart fs-1 d-block mb-2 opacity-50"></i> Belum ada data.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ========================================== --}}
{{-- MODAL POP-UP UNTUK DAFTAR MITRA DARI CHART --}}
{{-- ========================================== --}}
<div class="modal fade" id="modalMitraChart" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 12px;">
            <div class="modal-header bg-primary text-white py-3" style="border-radius: 12px 12px 0 0;">
                <h6 class="modal-title fw-bold" id="titleModalMitra"><i class="bi bi-people me-2"></i>Daftar Mitra Terlibat</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="bg-light p-3 text-center border-bottom">
                    <span class="text-muted small">Total Anggaran (Estimasi):</span>
                    <h4 class="fw-bold text-success mb-0" id="totalHonorModal">Rp 0</h4>
                </div>
                <div style="max-height: 300px; overflow-y: auto;">
                    <ul class="list-group list-group-flush" id="listMitraModal">
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
document.addEventListener("DOMContentLoaded", function() {
    
    // Inisialisasi Modal Bootstrap
    const modalMitraElement = document.getElementById('modalMitraChart');
    const modalMitra = modalMitraElement ? new bootstrap.Modal(modalMitraElement) : null;

    function openMitraModal(title, honor, mitraArray) {
        document.getElementById('titleModalMitra').innerHTML = `<i class="bi bi-diagram-3-fill me-2"></i> ${title}`;
        document.getElementById('totalHonorModal').innerText = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(honor);
        
        const ul = document.getElementById('listMitraModal');
        ul.innerHTML = '';
        
        if(mitraArray && mitraArray.length > 0) {
            mitraArray.forEach((nama, i) => {
                ul.innerHTML += `<li class="list-group-item px-4 py-2"><span class="badge bg-secondary me-2">${i+1}</span> <span class="fw-bold text-dark">${nama}</span></li>`;
            });
        } else {
            ul.innerHTML = `<li class="list-group-item text-center text-muted py-4">Tidak ada data mitra</li>`;
        }
        
        if(modalMitra) modalMitra.show();
    }

    const chartColors = ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796', '#fd7e14', '#20c997', '#6f42c1', '#d63384'];

    // 1. RENDER CHART FUNGSI
    @if(!empty($honorPerFungsi))
        const dataRawFungsi = @json($honorPerFungsi);
        const labelFungsi = Object.keys(dataRawFungsi);
        const honorFungsi = Object.values(dataRawFungsi).map(d => d.total);
        const mitraFungsi = Object.values(dataRawFungsi).map(d => d.mitra);

        const ctxFungsi = document.getElementById('chartHonorFungsi').getContext('2d');
        new Chart(ctxFungsi, {
            type: 'doughnut',
            data: {
                labels: labelFungsi,
                datasets: [{
                    data: honorFungsi,
                    backgroundColor: chartColors,
                    borderWidth: 2,
                    hoverOffset: 10
                }]
            },
            options: {
                maintainAspectRatio: false,
                cutout: '65%',
                plugins: {
                    legend: { position: 'bottom', labels: { padding: 20, font: { size: 11 } } },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return ' ' + new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(context.raw);
                            }
                        }
                    }
                },
                onClick: (e, activeElements) => {
                    if (activeElements.length > 0) {
                        const index = activeElements[0].index;
                        openMitraModal('Fungsi: ' + labelFungsi[index], honorFungsi[index], mitraFungsi[index]);
                    }
                }
            }
        });
    @endif

    // 2. RENDER CHART KEGIATAN
    @if(!empty($honorPerKegiatan))
        const dataRawKegiatan = @json($honorPerKegiatan);
        const labelKegiatan = Object.keys(dataRawKegiatan);
        const honorKegiatan = Object.values(dataRawKegiatan).map(d => d.total);
        const mitraKegiatan = Object.values(dataRawKegiatan).map(d => d.mitra);

        const ctxKegiatan = document.getElementById('chartHonorKegiatan').getContext('2d');
        new Chart(ctxKegiatan, {
            type: 'doughnut',
            data: {
                labels: labelKegiatan,
                datasets: [{
                    data: honorKegiatan,
                    backgroundColor: chartColors.slice().reverse(), 
                    borderWidth: 2,
                    hoverOffset: 10
                }]
            },
            options: {
                maintainAspectRatio: false,
                cutout: '65%',
                plugins: {
                    legend: { display: false }, 
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return ' ' + new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(context.raw);
                            }
                        }
                    }
                },
                onClick: (e, activeElements) => {
                    if (activeElements.length > 0) {
                        const index = activeElements[0].index;
                        openMitraModal('Kegiatan: ' + labelKegiatan[index], honorKegiatan[index], mitraKegiatan[index]);
                    }
                }
            }
        });
    @endif
});
</script>
@endpush