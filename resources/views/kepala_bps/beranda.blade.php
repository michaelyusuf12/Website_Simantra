@extends('layouts.master')

@section('title', 'Beranda Kepala BPS')

@section('content')
<div class="container-fluid py-4">
    
    {{-- ========================================== --}}
    {{-- HEADER & LOGIKA TANGGAL --}}
    {{-- ========================================== --}}
    @php
        $bulanIndo = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        $bulanDipilih = request('month', date('n'));
        $tahunSaatIni = date('Y');
    @endphp

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark mb-0">Beranda Kepala BPS</h2>
        
        <div class="dropdown shadow-sm">
            <button class="btn btn-white dropdown-toggle px-4 border bg-white" type="button" data-bs-toggle="dropdown" style="border-radius: 10px; font-weight: 500;">
                <i class="bi bi-calendar3 me-2 text-primary"></i> 
                {{ $bulanIndo[(int)$bulanDipilih] }} {{ $tahunSaatIni }}
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow border-0" style="border-radius: 12px; max-height: 250px; overflow-y: auto;">
                <li><h6 class="dropdown-header">Pilih Periode</h6></li>
                @foreach($bulanIndo as $angka => $nama)
                    <li>
                        <a class="dropdown-item {{ $bulanDipilih == $angka ? 'active bg-primary text-white' : '' }}" href="?month={{ $angka }}">
                            {{ $nama }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

{{-- ========================================== --}}
    {{-- EMPAT KOTAK STATISTIK UTAMA (DIKEMBALIKAN KE DESAIN ASLI) --}}
    {{-- ========================================== --}}
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4 mb-4"> 
        {{-- Card 1: Mitra Aktif (Biru) --}}
        <div class="col">
            <div class="card border-0 text-white shadow-sm h-100" style="background: linear-gradient(135deg, #00d2ff 0%, #007bff 100%); border-radius: 12px;">
                <div class="card-body pb-2">
                    <h6 class="fw-bold mb-3 text-uppercase" style="font-size: 0.75rem; opacity: 0.9; letter-spacing: 0.5px;">TOTAL MITRA AKTIF</h6>
                    <div class="d-flex align-items-baseline">
                        <h2 class="fw-bold mb-0 me-2" style="font-size: 2.5rem;">{{ $mitraAktif ?? 0 }}</h2>
                        <span class="fw-medium" style="font-size: 1rem; opacity: 0.9;">/ {{ $totalMitra ?? 0 }} Mitra</span>
                    </div>
                </div>
                <div class="card-footer border-0 bg-transparent pt-0 pb-3">
                    <div class="small fw-medium" style="font-size: 0.8rem; opacity: 0.9;">
                        <i class="bi bi-people-fill me-1"></i> Sedang bekerja bulan ini
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 2: SPK Menunggu Persetujuan (Kuning/Oranye) --}}
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
                        <i class="bi bi-pen-fill me-1"></i> Butuh tanda tangan basah
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 3: Kontrak Disetujui (Hijau) --}}
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
                        <i class="bi bi-check-circle-fill me-1"></i> Siap dicetak oleh mitra
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 4: Total Pengeluaran Honor (Ungu Gelap/Elegan) --}}
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
                        <i class="bi bi-cash-stack me-1"></i> Total estimasi bulan ini
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- ========================================== --}}
        {{-- TABEL SHORTCUT APPROVAL (KIRI) --}}
        {{-- ========================================== --}}
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold text-dark mb-0"><i class="bi bi-clock-history me-2 text-warning"></i>Perlu Persetujuan</h6>
                    <a href="{{ route('kepala.approval') }}" class="btn btn-sm btn-link text-decoration-none">Lihat Semua</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light small text-uppercase">
                                <tr>
                                    <th class="ps-4">Mitra</th>
                                    <th class="text-end">Honor</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($shortcutApproval ?? [] as $spk)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="fw-bold text-dark">{{ $spk->mitra->nama_petugas ?? 'User' }}</div>
                                            <div class="text-muted small">{{ $spk->no_surat }}</div>
                                        </td>
                                        <td class="text-end fw-bold text-success">Rp {{ number_format($spk->total_nilai_perjanjian, 0, ',', '.') }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('kepala.approval') }}" class="btn btn-sm btn-primary px-3 shadow-sm" style="border-radius: 6px;">Review</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="text-center py-5 text-muted">Tidak ada data.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- ========================================== --}}
        {{-- ALTERNATIF 1: DONUT CHART (KANAN) --}}
        {{-- ========================================== --}}
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="fw-bold text-dark mb-0"><i class="bi bi-pie-chart-fill me-2 text-primary"></i>Proporsi Honor per Kegiatan</h6>
                </div>
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                    @if(!empty($honorPerKegiatan))
                        <div style="width: 100%; max-width: 280px;">
                            <canvas id="chartHonorKegiatan"></canvas>
                        </div>
                        <div class="mt-4 w-100" style="max-height: 200px; overflow-y: auto;">
                            <ul class="list-group list-group-flush small">
                                @foreach($honorPerKegiatan as $nama => $nilai)
                                    <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                                        <span><i class="bi bi-circle-fill me-2" id="bullet-{{ $loop->index }}"></i> {{ $nama }}</span>
                                        <span class="fw-bold">Rp {{ number_format($nilai, 0, ',', '.') }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @else
                        <div class="text-center text-muted py-5">
                            <i class="bi bi-pie-chart fs-1 d-block mb-2 opacity-50"></i>
                            Belum ada data kegiatan.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- Library Grafik Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    @if(!empty($honorPerKegiatan))
        const ctx = document.getElementById('chartHonorKegiatan').getContext('2d');
        
        // Data dari PHP (Controller)
        const dataHonor = @json(array_values($honorPerKegiatan));
        const labelKegiatan = @json(array_keys($honorPerKegiatan));
        
        // Palet Warna Professional yang lebih banyak untuk menampung banyak kegiatan
        const colors = ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796', '#5a5c69', '#fd7e14', '#20c997', '#0dcaf0', '#6610f2', '#d63384'];

        // Warnai Bullet Legend secara manual di daftar bawah agar sinkron dengan warna grafik
        labelKegiatan.forEach((label, i) => {
            const bullet = document.getElementById(`bullet-${i}`);
            if(bullet) bullet.style.color = colors[i % colors.length];
        });

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: labelKegiatan,
                datasets: [{
                    data: dataHonor,
                    backgroundColor: colors,
                    hoverBackgroundColor: colors,
                    hoverBorderColor: "rgba(234, 236, 244, 1)",
                    borderWidth: 2
                }],
            },
            options: {
                maintainAspectRatio: false,
                cutout: '70%', // Mengatur seberapa besar lubang donatnya
                plugins: {
                    // INI KUNCI PERBAIKANNYA: Menyembunyikan teks bawaan yang menumpuk di atas grafik
                    legend: {
                        display: false 
                    },
                    // Membuat tooltip (kotak info saat grafik disentuh mouse) menjadi lebih rapi
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed !== null) {
                                    // Format angka ke mata uang Rupiah
                                    label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(context.parsed);
                                }
                                return label;
                            }
                        }
                    }
                },
                animation: {
                    animateScale: true,
                    animateRotate: true
                }
            },
        });
    @endif
});
</script>
@endpush