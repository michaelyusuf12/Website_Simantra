@extends('layouts.master')
 
@section('content')
<div class="container-fluid py-4">
 
    {{-- Logika PHP untuk Filter Bulan --}}
    @php
        $bulanIndo = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        $bulanDipilih = request('month', date('n')); 
        $tahunSaatIni = date('Y');
    @endphp
 
    {{-- JUDUL HALAMAN --}}
    <div class="mb-4">
        <h2 class="fw-bold text-dark mb-1">Riwayat Penugasan</h2>
    </div>
 
    <div class="card border-0 shadow-sm" style="border-radius: 12px;">
        
        {{-- ========================================== --}}
        {{-- HEADER CARD: JUDUL, FILTER BULAN & SEARCH  --}}
        {{-- ========================================== --}}
        <div class="card-header bg-white border-bottom py-3 d-flex flex-column flex-lg-row justify-content-between align-items-lg-center" style="border-radius: 12px 12px 0 0;">
            <h6 class="fw-bold mb-3 mb-lg-0 text-dark">Seluruh Riwayat Penugasan Anda</h6>
            
            <div class="d-flex flex-column flex-md-row gap-2">
                {{-- 1. Dropdown Filter Bulan --}}
                <div class="dropdown">
                    <button class="btn btn-white border dropdown-toggle px-3 shadow-sm text-dark" type="button" data-bs-toggle="dropdown" style="border-radius: 6px; font-weight: 500; font-size: 0.9rem;">
                        <i class="bi bi-calendar3 me-2 text-primary"></i> 
                        {{ $bulanIndo[(int)$bulanDipilih] }} {{ $tahunSaatIni }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0" style="border-radius: 8px;">
                        <li><h6 class="dropdown-header">Pilih Periode</h6></li>
                        @foreach($bulanIndo as $angka => $nama)
                            <li>
                                <a class="dropdown-item {{ $bulanDipilih == $angka ? 'active bg-primary text-white' : '' }}" 
                                   href="?month={{ $angka }}{{ request('search') ? '&search='.urlencode(request('search')) : '' }}">
                                    {{ $nama }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                {{-- 2. Form Pencarian dengan Tombol Biru --}}
                <form action="" method="GET" class="d-flex m-0">
                    @if(request('month'))
                        <input type="hidden" name="month" value="{{ request('month') }}">
                    @endif
                    <div class="input-group shadow-sm" style="border-radius: 6px; overflow: hidden;">
                        <input type="text" name="search" class="form-control" placeholder="Cari No. Surat atau Kegiatan..." value="{{ request('search') }}" style="font-size: 0.9rem; min-width: 250px;">
                        <button class="btn btn-primary px-3" type="submit" style="font-weight: 500; font-size: 0.9rem;">
                            <i class="bi bi-search me-1"></i> Cari
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        {{-- ========================================== --}}
        {{-- TABEL DATA (DESAIN BIRU MUDA & BORDER)     --}}
        {{-- ========================================== --}}
        <div class="card-body p-0">
            <div class="table-responsive">
                {{-- Penambahan class table-bordered untuk memunculkan garis vertikal --}}
                <table class="table table-bordered table-hover align-middle mb-0">
                    {{-- Warna background biru muda presisi disesuaikan dengan Gambar 1 --}}
                    <thead class="text-center align-middle" style="background-color: #e6f0ff; color: #212529; font-size: 0.9rem;">
                        <tr>
                            <th class="py-3" style="width: 50px;">No</th>
                            <th class="py-3">Nomor Surat</th>
                            <th class="py-3">Bulan</th>
                            <th class="py-3">Rincian Tugas</th>
                            <th class="py-3">Total Honor</th>
                            <th class="py-3">Status</th>
                            <th class="py-3" style="width: 120px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-center" style="font-size: 0.9rem;">
                        @forelse($riwayatPenugasan as $index => $p)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td><span class="text-primary fw-medium">{{ $p->no_surat }}</span></td>
                            <td>
                                <span class="badge bg-light text-dark border">{{ $p->bulan_kegiatan }}</span>
                            </td>
                            <td class="text-start">
                                @if($p->details && $p->details->count() > 0)
                                    {{ $p->details->first()->kegiatan->Nama_kegiatan ?? $p->details->first()->kegiatan->nama_kegiatan ?? '-' }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="fw-bold text-primary">Rp {{ number_format($p->total_nilai_perjanjian, 0, ',', '.') }}</td>
                            
                            <td>
                                @php
                                    $status = strtolower($p->status_kontrak ?? 'menunggu approval');
                                    if($status == 'disetujui') {
                                        $badgeClass = 'bg-success';
                                    } elseif($status == 'menunggu approval') {
                                        $badgeClass = 'bg-warning text-dark';
                                    } else {
                                        $badgeClass = 'bg-secondary';
                                    }
                                @endphp
                                <span class="badge {{ $badgeClass }} px-2 py-1" style="border-radius: 4px; font-weight: 500;">
                                    {{ ucwords($p->status_kontrak ?? 'Menunggu Approval') }}
                                </span>
                            </td>

                            <td>
                                <div class="d-flex justify-content-center gap-1">
                                    {{-- Tombol Mata --}}
                                    <button class="btn btn-sm btn-outline-info px-2 py-1 btn-lihat-kontrak" title="Preview SPK" data-bs-toggle="modal" data-bs-target="#modalDetail" data-url="{{ route('kelolakegiatan.cetak', $p->id_penugasan) }}?preview=true">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    {{-- Tombol Cetak --}}
                                    <a href="{{ route('kelolakegiatan.cetak', $p->id_penugasan) }}" target="_blank" class="btn btn-sm btn-outline-secondary px-2 py-1" title="Cetak SPK">
                                        <i class="bi bi-printer"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-search fs-1 d-block mb-2 opacity-50"></i>
                                Belum ada penugasan yang ditemukan pada bulan ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="card-footer bg-white py-3 text-center" style="border-radius: 0 0 12px 12px; border-top: 1px solid #dee2e6;">
            <small class="text-muted">Menampilkan penugasan periode {{ $bulanIndo[(int)$bulanDipilih] }} {{ $tahunSaatIni }}</small>
        </div>
    </div>
</div>
 
{{-- PANGGIL MODAL PREVIEW DI SINI --}}
@include('kepala_bps.modal_detail')
 
@endsection
 
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
                iframePreview.src = "";
            });
        }
    }
});
</script>
@endpush