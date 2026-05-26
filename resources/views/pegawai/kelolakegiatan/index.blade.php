@extends('layouts.master')

{{-- [PERBAIKAN] Panggil CSS Select2 di sini --}}
@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    <style>
        .select2-container--bootstrap-5 .select2-dropdown {
            z-index: 9999 !important;
        }
    </style>
@endpush

@section('content')

@section('content')
<div class="container-fluid py-4">

    <h3 class="text-center fw-bold text-dark mb-4" style="letter-spacing: 0.5px;">Kelola Penugasan Mitra</h3>

    <div class="mb-4 d-flex justify-content-between align-items-center">
        <button type="button" class="btn btn-success px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalKelolaKegiatan" style="border-radius: 6px; font-weight: 500;">
            <i class="bi bi-plus-circle me-1"></i> Tambah Penugasan
        </button>

        <button type="button" class="btn btn-outline-success px-4 shadow-sm" id="btnExportExcel" style="border-radius: 6px; font-weight: 500;">
            <i class="bi bi-file-earmark-excel me-1"></i> Export Excel
        </button>
    </div>

    <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px;">
        <div class="card-body bg-light" style="border-radius: 12px;">
            <form action="{{ route('kelolakegiatan.index') }}" method="GET" class="row g-2 align-items-center">
                
                <div class="col-md-2">
                    <select name="bulan" class="form-select border-0 shadow-sm" style="border-radius: 8px;">
                        <option value="">-- Semua Bulan --</option>
                        @foreach($daftarBulan as $bulan)
                            <option value="{{ $bulan }}" {{ request('bulan') == $bulan ? 'selected' : '' }}>{{ $bulan }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <select name="fungsi" class="form-select border-0 shadow-sm" style="border-radius: 8px;">
                        <option value="">-- Semua Fungsi --</option>
                        @foreach($daftarFungsi as $f)
                            <option value="{{ $f }}" {{ request('fungsi') == $f ? 'selected' : '' }}>{{ $f }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <select name="kegiatan" class="form-select border-0 shadow-sm" style="border-radius: 8px;">
                        <option value="">-- Semua Kegiatan --</option>
                        @foreach($kegiatans as $keg)
                            <option value="{{ $keg->id_kegiatan }}" {{ request('kegiatan') == $keg->id_kegiatan ? 'selected' : '' }}>
                                {{ $keg->fungsi }} - {{ $keg->jenis_kegiatan }} - {{ $keg->nama_kegiatan }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <input type="text" class="form-control border-0 shadow-sm" name="search" placeholder="Cari Surat/Mitra..." value="{{ request('search') }}" style="border-radius: 8px;">
                </div>

                <div class="col-md-1 text-end">
                    <button class="btn btn-primary w-100 shadow-sm" type="submit" style="border-radius: 8px;">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <form id="formExport" action="{{ route('kelolakegiatan.export') }}" method="POST">
        @csrf
        <div class="table-responsive shadow-sm" style="border-radius: 10px;">
            <table class="table table-striped table-bordered table-hover bg-white mb-0 align-middle" style="border-radius: 10px; overflow: hidden;">
                <thead class="table-primary text-center">
                    <tr>
                        <th class="py-3" style="width: 40px;">
                            <input class="form-check-input" type="checkbox" id="checkAll">
                        </th>
                        <th class="py-3" style="width: 50px;">No</th>
                        <th class="py-3">Nama Mitra</th>
                        <th class="py-3">Nomor Surat Tugas</th>
                        <th class="py-3">Bulan Penugasan</th>
                        <th class="py-3">Kegiatan</th>
                        <th class="py-3">Total Honor</th>
                        <th class="py-3" style="width: 170px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($penugasans as $index => $p)
                    <tr>
                        <td class="text-center">
                            <input class="form-check-input check-item" type="checkbox" name="ids[]" value="{{ $p->id_penugasan ?? $p->id }}">
                        </td>
                        <td class="text-center text-muted fw-bold">{{ $penugasans->firstItem() + $index }}</td>
                        <td><div class="fw-bold text-dark">{{ $p->mitra->nama_petugas ?? '-' }}</div></td>
                        <td class="text-center">
                            <span class="text-primary fw-bold" style="font-size: 0.9rem;">
                                {{ $p->no_surat ?? 'Belum ada nomor' }}
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-white text-dark border px-3 py-2 fw-normal shadow-sm" style="border-radius: 6px;">{{ $p->bulan_kegiatan }}</span>
                        </td>
                        <td class="text-center fw-bold text-dark">{{ $p->details ? $p->details->count() : 0 }}</td>
                        <td class="text-end px-3 fw-bold text-primary">Rp {{ number_format($p->total_nilai_perjanjian, 0, ',', '.') }}</td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                <button type="button" class="btn btn-sm btn-outline-info shadow-sm btn-lihat-detail" data-id="{{ $p->id_penugasan ?? $p->id }}" title="Lihat Detail">
                                    <i class="bi bi-eye-fill"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-warning shadow-sm btn-edit-penugasan" data-id="{{ $p->id_penugasan ?? $p->id }}" title="Edit">
                                    <i class="bi bi-pencil-fill"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger shadow-sm btn-delete-custom" data-id="{{ $p->id_penugasan ?? $p->id }}" title="Hapus Data">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                                <a href="{{ route('kelolakegiatan.cetak', $p->id_penugasan ?? $p->id) }}" target="_blank" class="btn btn-sm btn-outline-secondary shadow-sm" title="Cetak Surat Tugas">
                                    <i class="bi bi-printer-fill"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-3 text-secondary" style="opacity: 0.5;"></i>
                            Belum ada data penugasan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- [PERBAIKAN] MENAMPILKAN TOMBOL PAGINATION / HALAMAN --}}
        <div class="d-flex justify-content-end mt-3"> 
            @if ($penugasans->hasPages())
                {{ $penugasans->links() }}
            @endif
        </div>
    </form>

    <form id="formDeleteMaster" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    @include('pegawai.kelolakegiatan.modal')
    @include('pegawai.kelolakegiatan.modal-detail')

</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    
    let alerts = document.querySelectorAll('.alert');
    if (alerts.length > 0) {
        setTimeout(function() {
            alerts.forEach(function(alert) {
                let bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 4000); 
    }
    
    window.AppRoutes = {
        cekAkumulasi: "{{ route('kelolakegiatan.cekAkumulasi') }}",
        store: "{{ route('kelolakegiatan.store') }}",
        csrfToken: "{{ csrf_token() }}"
    };
});
</script>
<script src="{{ asset('js/kelolakegiatan.js') }}?v={{ time() }}"></script>
@endpush