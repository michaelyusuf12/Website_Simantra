@extends('layouts.master')

@section('title', 'Approval Kontrak')

@section('content')
<div class="container-fluid py-4">
    
    {{-- Header Halaman --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Approval Kontrak Mitra</h3>
            <p class="text-muted mb-0">Tinjau dan sahkan draf Surat Perjanjian Kerja (SPK) yang diajukan.</p>
        </div>
    </div>

    {{-- FILTER DAN PENCARIAN --}}
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
        <div class="card-body p-4">
            <div class="row g-3">
                
                {{-- Filter Bulan (Desain Modern Custom) --}}
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted">Periode Bulan</label>
                    @php
                        $bulanIndo = [
                            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                        ];
                        // Ambil bulan dari URL (?month=...), jika kosong pakai bulan ini
                        $bulanDipilih = request('month', date('n')); 
                        $tahunSaatIni = date('Y');
                    @endphp

                    <div class="dropdown d-grid">
                        <button class="btn bg-light border-0 text-start d-flex justify-content-between align-items-center shadow-none py-2" type="button" data-bs-toggle="dropdown" style="border-radius: 6px;">
                            <span><i class="bi bi-calendar3 me-2 text-primary"></i> {{ $bulanIndo[(int)$bulanDipilih] }} {{ $tahunSaatIni }}</span>
                            <i class="bi bi-chevron-down small text-muted"></i>
                        </button>
                        
                        <ul class="dropdown-menu shadow border-0 w-100" style="border-radius: 12px; max-height: 250px; overflow-y: auto;">
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
                
                {{-- Filter Status --}}
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted">Status Dokumen</label>
                    <select class="form-select bg-light border-0" onchange="window.location.href='?status=' + this.value">
                        <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu Persetujuan</option>
                        <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Sudah Disetujui</option>
                        <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>

                {{-- Pencarian --}}
                <div class="col-md-4 offset-md-2">
                    <label class="form-label small fw-bold text-muted">Cari Dokumen</label>
                    <form action="{{ route('kepala.approval') }}" method="GET">
                        {{-- Menyimpan status filter bulan & dokumen saat ini agar tidak tereset saat mencari --}}
                        <input type="hidden" name="month" value="{{ request('month', date('n')) }}">
                        <input type="hidden" name="status" value="{{ request('status', 'menunggu') }}">
                        
                        <div class="input-group">
                            <input type="text" name="search" class="form-control bg-light border-0" placeholder="Cari nama mitra atau kegiatan..." value="{{ request('search') }}">
                            <button class="btn btn-primary px-3" type="submit">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    {{-- TABEL ANTREAN PERSETUJUAN --}}
    <div class="card border-0 shadow-sm" style="border-radius: 12px;">
        
        {{-- Header Tabel & Tombol Aksi --}}
        <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center" style="border-radius: 12px 12px 0 0;">
            <h6 class="fw-bold mb-0"><i class="bi bi-list-task text-primary me-2"></i>Daftar Antrean Dokumen</h6>
            
            {{-- Tombol Cetak Laporan & Persetujuan Massal --}}
            <div class="d-flex gap-2">
                {{-- Tombol Cetak PDF --}}
                <a href="{{ route('kepala.approval.cetak', ['month' => request('month', date('n')), 'status' => request('status', 'menunggu'), 'search' => request('search')]) }}" 
                   target="_blank" 
                   class="btn btn-outline-secondary shadow-sm rounded-pill px-3 fw-bold">
                    <i class="bi bi-printer-fill me-1"></i> Cetak PDF
                </a>

                {{-- Tombol Persetujuan Massal --}}
                <button type="button" class="btn btn-success shadow-sm rounded-pill px-3 fw-bold" id="btnBulkApprove" onclick="confirmBulkApprove()">
                    <i class="bi bi-check-all me-1"></i> Setujui Terpilih
                </button>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center py-3" style="width: 50px;">
                                <input class="form-check-input border-secondary" type="checkbox" id="checkAll">
                            </th>
                            <th class="py-3" style="width: 50px;">No.</th>
                            <th class="py-3">Tgl Pengajuan</th>
                            <th class="py-3">No. Draf</th>
                            <th class="py-3">Nama Mitra</th>
                            <th class="py-3">Nama Kegiatan</th>
                            <th class="py-3 text-end">Nominal Honor</th>
                            <th class="py-3 text-center" style="width: 200px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($penugasans as $index => $p)
                        <tr>
                            <td class="text-center">
                                <input class="form-check-input border-secondary check-item" type="checkbox" value="{{ $p->id_penugasan ?? $p->id }}">
                            </td>
                            <td class="text-center text-muted fw-bold">{{ $index + 1 }}</td>
                            <td>{{ \Carbon\Carbon::parse($p->created_at)->locale('id')->translatedFormat('d F Y') }}</td>
                            <td><span class="badge bg-light text-dark border">{{ $p->no_surat ?? 'Belum ada nomor' }}</span></td>
                            <td class="fw-bold">{{ $p->mitra->nama_petugas ?? '-' }}</td>
                            
                            <td>
                                @if($p->details && $p->details->count() > 0)
                                    {{ $p->details->first()->kegiatan->Nama_kegiatan ?? $p->details->first()->kegiatan->nama_kegiatan ?? '-' }}
                                    @if($p->details->count() > 1)
                                        <span class="badge bg-secondary ms-1">+{{ $p->details->count() - 1 }} lainnya</span>
                                    @endif
                                @else
                                    -
                                @endif
                            </td>
                            
                            <td class="text-end fw-bold text-primary">Rp {{ number_format($p->total_nilai_perjanjian, 0, ',', '.') }}</td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1">
                                    <button class="btn btn-sm btn-outline-info border-0 shadow-sm btn-lihat-kontrak" 
                                        title="Preview Dokumen" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#modalDetail"
                                        data-url="{{ route('kelolakegiatan.cetak', $p->id_penugasan ?? $p->id) }}?preview=true">
                                        <i class="bi bi-eye-fill"></i>
                                    </button>

                                    <form action="{{ route('kepala.approval.approve', $p->id_penugasan ?? $p->id) }}" method="POST" class="d-inline form-setuju-dokumen">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success border-0 shadow-sm text-white" title="Setujui">
                                            <i class="bi bi-check-lg"></i>
                                        </button>
                                    </form>

                                    <form action="{{ route('kepala.approval.reject', $p->id_penugasan ?? $p->id) }}" method="POST" class="d-inline form-tolak-dokumen">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger border-0 shadow-sm text-white" title="Tolak">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                Tidak ada dokumen SPK yang {{ request('status', 'menunggu') == 'menunggu' ? 'menunggu persetujuan' : 'ditemukan' }} pada bulan ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

{{-- Form Tersembunyi untuk Bulk Approve --}}
<form id="formBulkApprove" action="{{ route('kepala.approval.bulkApprove') }}" method="POST" style="display: none;">
    @csrf
    <div id="bulkIdsInputContainer"></div>
</form>

{{-- PANGGIL FILE MODAL DI SINI --}}
@include('kepala_bps.modal_detail')

@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {

    // --- 1. SCRIPT UNTUK CHECKBOX PILIH SEMUA ---
    const checkAll = document.getElementById('checkAll');
    const checkItems = document.querySelectorAll('.check-item');

    if(checkAll) {
        checkAll.addEventListener('change', function() {
            checkItems.forEach(item => {
                item.checked = checkAll.checked;
            });
        });
    }

    checkItems.forEach(item => {
        item.addEventListener('change', function() {
            if (!this.checked) {
                checkAll.checked = false;
            } else {
                const allChecked = Array.from(checkItems).every(i => i.checked);
                checkAll.checked = allChecked;
            }
        });
    });

    // --- 2. FUNGSI PREVIEW KONTRAK IFRAME ---
    const btnLihatKontrak = document.querySelectorAll('.btn-lihat-kontrak');
    const iframePreview = document.getElementById('iframePreviewKontrak');

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

    // --- 3. FUNGSI SWEETALERT UNTUK TOMBOL SETUJUI (HIJAU) ---
    const formSetuju = document.querySelectorAll('.form-setuju-dokumen');
    formSetuju.forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault(); 
            
            Swal.fire({
                title: 'Setujui Dokumen?',
                text: "Dokumen SPK ini akan disahkan dan diteruskan.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#198754', 
                cancelButtonColor: '#6c757d',  
                confirmButtonText: '<i class="bi bi-check-lg"></i> Ya, Setujui!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); 
                }
            });
        });
    });

    // --- 4. FUNGSI SWEETALERT UNTUK TOMBOL TOLAK (MERAH) ---
    const formTolak = document.querySelectorAll('.form-tolak-dokumen');
    formTolak.forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault(); 
            
            Swal.fire({
                title: 'Tolak Dokumen?',
                text: "Dokumen SPK ini akan dikembalikan dan tidak disahkan.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545', 
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="bi bi-x-lg"></i> Ya, Tolak!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); 
                }
            });
        });
    });

});

// --- 5. FUNGSI UNTUK TOMBOL SETUJUI DOKUMEN TERPILIH (BULK APPROVE) ---
function confirmBulkApprove() {
    const checkedBoxes = document.querySelectorAll('.check-item:checked');
    
    if (checkedBoxes.length === 0) {
        Swal.fire({
            title: 'Pilih Dokumen',
            text: 'Silakan centang setidaknya satu dokumen yang ingin disetujui.',
            icon: 'warning',
            confirmButtonColor: '#0d6efd'
        });
        return;
    }
        
    Swal.fire({
        title: 'Setujui Semua Terpilih?',
        text: `Anda akan menyetujui ${checkedBoxes.length} dokumen sekaligus. Lanjutkan?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#198754',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="bi bi-check-all"></i> Ya, Setujui Semua!',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            const container = document.getElementById('bulkIdsInputContainer');
            container.innerHTML = ''; 
            
            checkedBoxes.forEach(checkbox => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'ids[]';
                input.value = checkbox.value;
                container.appendChild(input);
            });

            document.getElementById('formBulkApprove').submit();
        }
    });
}
</script>
@endpush