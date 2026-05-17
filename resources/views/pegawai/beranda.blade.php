@extends('layouts.master')

@section('title', 'Beranda Pegawai')

@section('content')
<div class="container-fluid py-4">
    
    {{-- Header & Filter Bulan --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark mb-0">Beranda Pegawai</h2>

        @php
            $bulanIndo = [
                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
            ];
            $bulanDipilih = request('month', date('n')); 
            $tahunSaatIni = date('Y');
        @endphp

        <div class="dropdown shadow-sm">
            <button class="btn btn-white dropdown-toggle px-4 border bg-white" type="button" data-bs-toggle="dropdown" style="border-radius: 10px; font-weight: 500;">
                <i class="bi bi-calendar3 me-2 text-primary"></i> 
                {{ $bulanIndo[(int)$bulanDipilih] }} {{ $tahunSaatIni }}
            </button>
            
            <ul class="dropdown-menu dropdown-menu-end shadow border-0" style="border-radius: 12px; max-height: 300px; overflow-y: auto;">
                <li><h6 class="dropdown-header">Pilih Periode</h6></li>
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

    {{-- ========================================== --}}
    {{-- EMPAT KOTAK STATISTIK BERWARNA             --}}
    {{-- ========================================== --}}
    <div class="row g-4 mb-4">
        {{-- Card 1: Total Surat Tugas --}}
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 text-white shadow-sm h-100" style="background: linear-gradient(135deg, #00d2ff 0%, #007bff 100%); border-radius: 12px;">
                <div class="card-body pb-2">
                    <h6 class="fw-bold mb-3 text-uppercase" style="font-size: 0.75rem; opacity: 0.9; letter-spacing: 0.5px;">TOTAL SURAT TUGAS</h6>
                    <div class="d-flex align-items-baseline">
                        <h2 class="fw-bold mb-0 me-2" style="font-size: 2rem;">{{ $totalSpk ?? 0 }}</h2>
                        <span class="fw-medium" style="font-size: 0.9rem; opacity: 0.9;">Surat</span>
                    </div>
                </div>
                <div class="card-footer border-0 bg-transparent pt-0 pb-3">
                    <div class="small fw-medium" style="font-size: 0.8rem; opacity: 0.9;">
                        <i class="bi bi-envelope-paper-fill me-1"></i> Diterbitkan bulan ini
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Card 2: Realisasi Honor --}}
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 text-white shadow-sm h-100" style="background: linear-gradient(135deg, #ffcf1b 0%, #ff8c00 100%); border-radius: 12px;">
                <div class="card-body pb-2">
                    <h6 class="fw-bold mb-3 text-uppercase" style="font-size: 0.75rem; opacity: 0.9; letter-spacing: 0.5px;">REALISASI HONOR</h6>
                    <div class="d-flex align-items-baseline">
                        <h2 class="fw-bold mb-0 me-2" style="font-size: 1.8rem;">Rp {{ number_format(($totalHonor ?? 0) / 1000000, 1) }}</h2>
                        <span class="fw-medium" style="font-size: 0.9rem; opacity: 0.9;">Juta</span>
                    </div>
                </div>
                <div class="card-footer border-0 bg-transparent pt-0 pb-3">
                    <div class="small fw-medium" style="font-size: 0.8rem; opacity: 0.9;">
                        <i class="bi bi-wallet2 me-1"></i> Total estimasi honor
                    </div>
                </div>
            </div>
        </div>
 
        {{-- Card 3: Mitra Aktif --}}
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 text-white shadow-sm h-100" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); border-radius: 12px;">
                <div class="card-body pb-2">
                    <h6 class="fw-bold mb-3 text-uppercase" style="font-size: 0.75rem; opacity: 0.9; letter-spacing: 0.5px;">MITRA AKTIF</h6>
                    <div class="d-flex align-items-baseline">
                        <h2 class="fw-bold mb-0 me-2" style="font-size: 2rem;">{{ $mitraAktif ?? 0 }}</h2>
                        <span class="fw-medium" style="font-size: 0.9rem; opacity: 0.9;">Orang</span>
                    </div>
                </div>
                <div class="card-footer border-0 bg-transparent pt-0 pb-3">
                    <div class="small fw-medium" style="font-size: 0.8rem; opacity: 0.9;">
                        <i class="bi bi-person-check-fill me-1"></i> Bekerja bulan ini
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 4: Menunggu Persetujuan (TAMBAHAN BARU) --}}
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 text-white shadow-sm h-100" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 12px;">
                <div class="card-body pb-2">
                    <h6 class="fw-bold mb-3 text-uppercase" style="font-size: 0.75rem; opacity: 0.9; letter-spacing: 0.5px;">MENUNGGU PERSETUJUAN</h6>
                    <div class="d-flex align-items-baseline">
                        <h2 class="fw-bold mb-0 me-2" style="font-size: 2rem;">{{ $menungguApproval ?? 0 }}</h2>
                        <span class="fw-medium" style="font-size: 0.9rem; opacity: 0.9;">Surat</span>
                    </div>
                </div>
                <div class="card-footer border-0 bg-transparent pt-0 pb-3">
                    <div class="small fw-medium" style="font-size: 0.8rem; opacity: 0.9;">
                        <i class="bi bi-clock-history me-1"></i> Menunggu TTD Kepala
                    </div>
                </div>
            </div>
        </div>
    </div>
 
    {{-- ========================================== --}}
    {{-- DUA KOLOM BAWAH (MONITORING & SHORTCUT)    --}}
    {{-- ========================================== --}}
    <div class="row g-4 mb-4">
        
        {{-- KOLOM KIRI: Tabel Monitoring Limit Honor --}}
        <div class="col-lg-7">
            <div class="card shadow-sm h-100" style="border: 1px solid #dee2e6; border-radius: 10px; overflow: hidden;">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-bar-chart-steps text-primary me-2"></i>Monitoring Limit Honor (Top 5)</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="bg-light text-center">
                                <tr>
                                    <th class="py-3" style="width: 25%;">Nama Mitra</th>
                                    <th class="py-3" style="width: 45%;">Penggunaan Batas</th>
                                    <th class="py-3" style="width: 15%;">Status</th>
                                    <th class="py-3" style="width: 15%;">Aksi</th> {{-- KOLOM AKSI BARU --}}
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topMitraLimit ?? [] as $m)
                                <tr>
                                    <td class="fw-bold px-3">{{ $m['nama'] }}</td>
                                    <td class="px-3 py-3">
                                        <div class="d-flex justify-content-between mb-1">
                                            <span class="fw-bold small text-dark">Rp {{ number_format($m['used']/1000000, 1) }} jt <span class="text-muted fw-normal">/ {{ number_format($m['limit']/1000000, 0) }}jt</span></span>
                                            <span class="fw-bold small text-dark">{{ $m['percentage'] }}%</span>
                                        </div>
                                        <div class="progress" style="height: 10px; border-radius: 10px; background-color: #e9ecef;">
                                            <div class="progress-bar bg-{{ $m['color'] }}" role="progressbar" style="width: {{ $m['percentage'] }}%"></div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-{{ $m['color'] }} px-2 py-1 rounded-pill" style="font-weight: 500;">{{ $m['status'] }}</span>
                                    </td>
                                    <td class="text-center px-2">
                                        {{-- Tombol Detail yang mengarah ke pencarian nama Mitra di Kelola Penugasan --}}
                                        <a href="{{ route('kelolakegiatan.index') }}?search={{ urlencode($m['nama']) }}" class="btn btn-sm btn-outline-primary" style="font-size: 0.8rem; border-radius: 6px;">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">Tidak ada data penugasan bulan ini.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: Tabel SPK Terakhir Dibuat --}}
        <div class="col-lg-5">
            <div class="card shadow-sm h-100" style="border: 1px solid #dee2e6; border-radius: 10px; overflow: hidden;">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-clock-history text-warning me-2"></i>SPK Terakhir Dibuat</h6>
                    <a href="{{ route('kelolakegiatan.index') }}" class="btn btn-sm btn-link text-decoration-none p-0">Lihat Semua</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-secondary small">
                                <tr>
                                    <th class="ps-4 py-2">Informasi SPK</th>
                                    <th class="text-center py-2">Preview</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($spkTerbaru ?? [] as $spk)
                                    <tr>
                                        <td class="ps-4 py-3">
                                            <div class="fw-bold text-dark">{{ $spk->mitra->nama_petugas ?? 'Mitra Tidak Ditemukan' }}</div>
                                            <div class="text-muted" style="font-size: 0.85rem;">{{ $spk->no_surat }}</div>
                                            
                                            {{-- Badge status kecil di bawah nama --}}
                                            @php $statusWarn = strtolower($spk->status_kontrak) == 'disetujui' ? 'success' : 'warning'; @endphp
                                            <span class="badge bg-{{ $statusWarn }} text-{{ $statusWarn == 'warning' ? 'dark' : 'white' }} mt-1" style="font-size: 0.7rem;">
                                                {{ $spk->status_kontrak }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            {{-- Tombol Mata (Preview) --}}
                                            <button class="btn btn-sm btn-outline-info px-2 py-1 btn-lihat-kontrak shadow-sm" title="Preview SPK" data-bs-toggle="modal" data-bs-target="#modalDetail" data-url="{{ route('kelolakegiatan.cetak', $spk->id_penugasan) }}?preview=true" style="border-radius: 6px;">
                                                <i class="bi bi-eye-fill"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="2" class="text-center py-5 text-muted">Belum ada SPK yang dibuat bulan ini.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- PANGGIL MODAL PREVIEW AGAR TOMBOL MATA BERFUNGSI --}}
@include('kepala_bps.modal_detail')

@endsection

{{-- SCRIPT UNTUK MODAL PREVIEW --}}
@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    const btnLihatKontrak = document.querySelectorAll('.btn-lihat-kontrak');
    const iframePreview = document.getElementById('iframePreviewKontrak');
 
    if(btnLihatKontrak.length > 0 && iframePreview) {
        btnLihatKontrak.forEach(btn => {
            btn.addEventListener('click', function () {
                const url = this.getAttribute('data-url');
                iframePreview.src = url;
            });
        });
 
        const modalDetailEl = document.getElementById('modalDetail');
        if(modalDetailEl) {
            modalDetailEl.addEventListener('hidden.bs.modal', function () {
                iframePreview.src = ""; // Bersihkan iframe saat modal ditutup
            });
        }
    }
});
</script>
@endpush