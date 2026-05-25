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
    {{-- GRAFIK ADMIN DITAMPILKAN DI PPK            --}}
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
                        <small class="d-block text-muted mt-1" style="font-size: 0.75rem; font-weight: normal;">(Klik pada grafik untuk melihat rincian kegiatan)</small>
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

{{-- MODAL DAFTAR MITRA RASIO CHART --}}
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

    {{-- TABEL DAFTAR PENUGASAN (REVISI #3) --}}
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



{{-- ========================================== --}}
{{-- MODAL POP-UP TABEL UNTUK KLIK FUNGSI (REVISI #8 & #2) --}}
{{-- ========================================== --}}
<div class="modal fade" id="modalDetailFungsi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 12px;">
            <div class="modal-header bg-info text-white py-3" style="border-radius: 12px 12px 0 0;">
                <h6 class="modal-title fw-bold" id="titleModalFungsi"><i class="bi bi-table me-2"></i>Rincian Kegiatan di Fungsi</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="bg-light p-3 text-center border-bottom">
                    <span class="text-muted small">Total Anggaran Fungsi Ini:</span>
                    <h4 class="fw-bold text-dark mb-0" id="totalHonorFungsi">Rp 0</h4>
                </div>
                <div class="table-responsive" style="max-height: 400px;">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th class="ps-4">Nama Kegiatan</th>
                                <th class="text-end pe-4">Subtotal Honor</th>
                            </tr>
                        </thead>
                        <tbody id="tbodyDetailFungsi">
                            {{-- Diisi via JS --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL POP-UP NAMA MITRA UNTUK KLIK KEGIATAN --}}
<div class="modal fade" id="modalMitraChart" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 12px;">
            <div class="modal-header bg-success text-white py-3" style="border-radius: 12px 12px 0 0;">
                <h6 class="modal-title fw-bold" id="titleModalMitra"><i class="bi bi-people me-2"></i>Daftar Mitra Terlibat</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="bg-light p-3 text-center border-bottom">
                    <span class="text-muted small">Total Anggaran Kegiatan:</span>
                    <h4 class="fw-bold text-dark mb-0" id="totalHonorModal">Rp 0</h4>
                </div>
                <div style="max-height: 300px; overflow-y: auto;">
                    <ul class="list-group list-group-flush" id="listMitraModal">
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- MODAL KE-3: DETAIL RINCIAN PER KEGIATAN --}}
<div class="modal fade" id="modalRincianKegiatanFungsi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 12px;">
            <div class="modal-header bg-secondary text-white py-3" style="border-radius: 12px 12px 0 0;">
                <h6 class="modal-title fw-bold" id="titleModalRincianKeg"><i class="bi bi-list-check me-2"></i>Rincian Kegiatan</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3">
                <div class="table-responsive" style="max-height: 400px;">
                    <table class="table table-bordered table-striped align-middle mb-0 small">
                        <thead class="table-light text-center">
                            <tr>
                                <th>Nama Mitra</th>
                                <th>Harga (Rp)</th>
                                <th>Vol</th>
                                <th>Satuan</th>
                                <th>Subtotal (Rp)</th>
                            </tr>
                        </thead>
                        <tbody id="tbodyRincianKegiatanFungsi"></tbody>
                    </table>
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
    const formatRp = (angka) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(angka);
    
    // --- Variabel Rasio Mitra ---
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

    // --- FUNGSI BUKA RINCIAN KEGIATAN GLOBAL ---
    window.bukaRincianKegiatan = function(namaKeg, rincianJSON) {
        const rincianArray = JSON.parse(decodeURIComponent(rincianJSON));
        document.getElementById('titleModalRincianKeg').innerHTML = `<i class="bi bi-list-check me-2"></i>Rincian: ${namaKeg}`;
        
        const tbodyRincian = document.getElementById('tbodyRincianKegiatanFungsi');
        tbodyRincian.innerHTML = '';

        if(rincianArray && rincianArray.length > 0) {
            rincianArray.forEach(item => {
                tbodyRincian.innerHTML += `
                    <tr>
                        <td class="fw-bold text-dark">${item.mitra}</td>
                        <td class="text-end">${formatRp(item.harga)}</td>
                        <td class="text-center">${item.volume}</td>
                        <td class="text-center">${item.satuan}</td>
                        <td class="text-end fw-bold text-success">${formatRp(item.subtotal)}</td>
                    </tr>
                `;
            });
        }
        
        new bootstrap.Modal(document.getElementById('modalRincianKegiatanFungsi')).show();
    };

document.addEventListener("DOMContentLoaded", function() {
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
    // INIT MODAL & WARNA
    // ==========================================
    const modalKegiatanElement = document.getElementById('modalMitraChart');
    const modalKegiatan = modalKegiatanElement ? new bootstrap.Modal(modalKegiatanElement) : null;
    
    const modalFungsiElement = document.getElementById('modalDetailFungsi');
    const modalFungsi = modalFungsiElement ? new bootstrap.Modal(modalFungsiElement) : null;

    const chartColors = ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796', '#fd7e14', '#20c997', '#6f42c1', '#d63384'];

    // ==========================================
    // 3. RENDER CHART FUNGSI & FUNGSI KLIK TABEL
    // ==========================================
    @if(!empty($honorPerFungsi))
        const dataRawFungsi = @json($honorPerFungsi);
        const labelFungsi = Object.keys(dataRawFungsi);
        const honorFungsi = Object.values(dataRawFungsi).map(d => d.total);
        
        const dataRawKegiatan = @json($honorPerKegiatan ?? []);
        
        const ctxFungsi = document.getElementById('chartHonorFungsi').getContext('2d');
        new Chart(ctxFungsi, {
            type: 'doughnut',
            data: {
                labels: labelFungsi,
                datasets: [{
                    data: honorFungsi,
                    backgroundColor: chartColors,
                    borderWidth: 2, hoverOffset: 10
                }]
            },
            options: {
                maintainAspectRatio: false, cutout: '65%',
                plugins: {
                    legend: { position: 'bottom', labels: { padding: 20, boxWidth: 12, font: { size: 11 } } },
                    datalabels: { display: false },
                    tooltip: {
                        callbacks: { label: function(context) { return ' ' + formatRp(context.raw); } }
                    }
                },
                onClick: (e, activeElements) => {
                    if (activeElements.length > 0) {
                        const index = activeElements[0].index;
                        const namaFungsiTerpilih = labelFungsi[index];
                        const dataFungsi = dataRawFungsi[namaFungsiTerpilih];
                        
                        document.getElementById('titleModalFungsi').innerHTML = `<i class="bi bi-table me-2"></i>Rincian Fungsi: ${namaFungsiTerpilih}`;
                        document.getElementById('totalHonorFungsi').innerText = formatRp(honorFungsi[index]);
                        
                        const tbody = document.getElementById('tbodyDetailFungsi');
                        tbody.innerHTML = '';
                        
                        if(dataFungsi.kegiatans && Object.keys(dataFungsi.kegiatans).length > 0) {
                            for(const [namaKeg, detailKeg] of Object.entries(dataFungsi.kegiatans)) {
                                const rincianJSON = encodeURIComponent(JSON.stringify(detailKeg.rincian));
                                tbody.innerHTML += `
                                    <tr>
                                        <td class="ps-4 fw-bold text-dark">${namaKeg}</td>
                                        <td class="text-end text-success fw-bold">${formatRp(detailKeg.total)}</td>
                                        <td class="text-center pe-4" style="width:60px;">
                                            <button type="button" class="btn btn-sm btn-outline-info" onclick="bukaRincianKegiatan('${namaKeg}', '${rincianJSON}')" title="Lihat Detail Rincian"><i class="bi bi-eye-fill"></i></button>
                                        </td>
                                    </tr>
                                `;
                            }
                        } else {
                            tbody.innerHTML = `<tr><td colspan="3" class="text-center py-4 text-muted">Tidak ada rincian kegiatan terdeteksi.</td></tr>`;
                        }
                        modalFungsi.show();
                    }
                }
            }
        });
    @endif

    // ==========================================
    // 4. RENDER CHART KEGIATAN & KLIK DAFTAR MITRA
    // ==========================================
    @if(!empty($honorPerKegiatan))
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
                    borderWidth: 2, hoverOffset: 10
                }]
            },
            options: {
                maintainAspectRatio: false, cutout: '65%',
                plugins: {
                    legend: { display: false }, 
                    datalabels: { display: false },
                    tooltip: {
                        callbacks: { label: function(context) { return ' ' + formatRp(context.raw); } }
                    }
                },
                onClick: (e, activeElements) => {
                    if (activeElements.length > 0) {
                        const index = activeElements[0].index;
                        
                        document.getElementById('titleModalMitra').innerHTML = `<i class="bi bi-diagram-3-fill me-2"></i> Kegiatan: ${labelKegiatan[index]}`;
                        document.getElementById('totalHonorModal').innerText = formatRp(honorKegiatan[index]);
                        
                        const ul = document.getElementById('listMitraModal');
                        ul.innerHTML = '';
                        const mitraArray = mitraKegiatan[index];
                        
                        if(mitraArray && mitraArray.length > 0) {
                            mitraArray.forEach((nama, i) => {
                                ul.innerHTML += `<li class="list-group-item px-4 py-2"><span class="badge bg-secondary me-2">${i+1}</span> <span class="fw-bold text-dark">${nama}</span></li>`;
                            });
                        } else {
                            ul.innerHTML = `<li class="list-group-item text-center text-muted py-4">Tidak ada data mitra</li>`;
                        }
                        
                        modalKegiatan.show();
                    }
                }
            }
        });
    @endif
});
</script>
@endpush