@extends('layouts.master')
@section('title', 'Kelola Kegiatan')
@section('content')
<div class="container-fluid">
    <h3 class="mb-4 text-center">Kelola Kegiatan Mitra</h3>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <button class="btn btn-success mb-3 btnTambah" data-bs-toggle="modal" data-bs-target="#modalKelolaKegiatan">
        <i class="bi bi-plus-circle"></i> Tambah Penugasan
    </button>
    
    {{-- Form Pencarian Diperbaiki --}}
    <div class="row mb-3">
        <div class="col-md-5 ms-auto">
            <form action="{{ route('kelolakegiatan.index') }}" method="GET">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Cari berdasarkan nama mitra atau kegiatan..." name="search" value="{{ request('search') }}">
                    <button class="btn btn-primary" type="submit">
                        <i class="bi bi-search"></i> Cari
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="table-primary text-center align-middle">
                <tr>
                    <th>No</th>
                    <th>Nama Mitra</th>
                    <th>Nama Kegiatan</th>
                    <th>Bulan Kegiatan</th>
                    <th>Honor</th>
                    <th>Jumlah Dokumen</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody class="align-middle">
                @forelse($penugasans as $row)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $row->mitra->nama_petugas ?? 'N/A' }}</td>
                        <td>{{ $row->kegiatan->nama_kegiatan ?? 'N/A' }}</td>
                        <td class="text-center">{{ $row->bulan_kegiatan }}</td>
                        <td class="text-end">Rp {{ number_format($row->honor, 0, ',', '.') }}</td>
                        <td class="text-center">{{ $row->jumlah_dokumen }}</td>
                        <td class="text-center">
                            <button class="btn btn-warning btn-sm btnEdit"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#modalKelolaKegiatan"
                                    data-id="{{ $row->id }}"
                                    data-mitra_id="{{ $row->mitra_id }}"
                                    data-kegiatan_id="{{ $row->kegiatan_id }}"
                                    data-honor="{{ $row->honor }}"
                                    data-bulan="{{ $row->bulan_kegiatan }}"
                                    data-dokumen="{{ $row->jumlah_dokumen }}">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <form action="{{ route('kelolakegiatan.destroy', $row->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">Belum ada data penugasan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-end mt-3">
        {{ $penugasans->links() }}
    </div>
</div>

@include('kelola_kegiatan.modal')
@endsection

@push('scripts')
{{-- Kode JavaScript (tidak berubah, tapi disertakan untuk kelengkapan) --}}
<script>
document.addEventListener("DOMContentLoaded", function () {
    const modal = new bootstrap.Modal(document.getElementById('modalKelolaKegiatan'));
    const modalTitle = document.getElementById("modalTitle");
    const form = document.getElementById("formKelolaKegiatan");
    const formMethodInput = document.getElementById("formMethod");
    const penugasanIdInput = document.getElementById("penugasanId");

    document.querySelector(".btnTambah").addEventListener("click", function () {
        modalTitle.textContent = "Tambah Penugasan";
        form.reset();
        penugasanIdInput.value = "";
        form.action = "{{ route('kelolakegiatan.store') }}"; 
        formMethodInput.value = "POST";
    });

    document.querySelectorAll(".btnEdit").forEach(btn => {
        btn.addEventListener("click", function () {
            modalTitle.textContent = "Edit Penugasan";
            
            const penugasanId = this.dataset.id;
            const updateUrl = `{{ url('kelolakegiatan') }}/${penugasanId}`;
            
            form.action = updateUrl;
            formMethodInput.value = "PUT";

            penugasanIdInput.value = penugasanId;
            document.getElementById("idMitra").value = this.dataset.mitra_id;
            document.getElementById("idKegiatan").value = this.dataset.kegiatan_id;
            document.getElementById("honor").value = this.dataset.honor;
            document.getElementById("jumlahDokumen").value = this.dataset.dokumen;
            document.getElementById("bulanKegiatan").value = this.dataset.bulan;
        });
    });
});
</script>
@endpush