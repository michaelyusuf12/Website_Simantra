@extends('layouts.master')

@section('title', 'Data Kegiatan')

@section('content')
<div class="container-fluid">
    <h3 class="mb-4 text-center">Data Kegiatan</h3>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <button class="btn btn-success mb-3 btnTambah" data-bs-toggle="modal" data-bs-target="#modalKegiatan">
        <i class="bi bi-plus-circle"></i> Tambah Kegiatan
    </button>

    <div class="row mb-3">
        <div class="col-md-5 ms-auto">
            {{-- Form Pencarian Diperbaiki --}}
            <form action="{{ route('datakegiatan.index') }}" method="GET">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Cari kegiatan..." name="search" value="{{ request('search') }}">
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
                    <th>No.</th>
                    <th>Nama Kegiatan</th>
                    <th>Penanggung Jawab</th>
                    <th>Fungsi</th>
                    <th>Periode</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody class="align-middle">
                @forelse ($kegiatans as $kegiatan)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $kegiatan->nama_kegiatan }}</td>
                        <td>{{ $kegiatan->penanggung_jawab }}</td>
                        <td class="text-center">{{ $kegiatan->fungsi }}</td>
                        <td class="text-center">{{ \Carbon\Carbon::parse($kegiatan->tgl_mulai)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($kegiatan->tgl_selesai)->format('d/m/Y') }}</td>
                        <td class="text-center">
                            <button class="btn btn-warning btn-sm btnEdit"
                                    data-bs-toggle="modal" data-bs-target="#modalKegiatan"
                                    data-id="{{ $kegiatan->id }}"
                                    data-kode="{{ $kegiatan->kode_kegiatan }}"
                                    data-nama="{{ $kegiatan->nama_kegiatan }}"
                                    data-penanggung="{{ $kegiatan->penanggung_jawab }}"
                                    data-tim="{{ $kegiatan->nama_tim }}"
                                    data-fungsi="{{ $kegiatan->fungsi }}"
                                    data-jenis="{{ $kegiatan->jenis_kegiatan }}"
                                    data-mulai="{{ $kegiatan->tgl_mulai }}"
                                    data-selesai="{{ $kegiatan->tgl_selesai }}">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <form action="{{ route('datakegiatan.destroy', $kegiatan->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus data ini?');">
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
                        <td colspan="6" class="text-center">Data kegiatan tidak ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-end mt-3">
        {{ $kegiatans->links() }}
    </div>
</div>

@include('datakegiatan.modal')
@endsection

@push('scripts')
{{-- Kode JavaScript (tidak berubah, tapi disertakan untuk kelengkapan) --}}
<script>
document.addEventListener("DOMContentLoaded", function () {
    const modal = new bootstrap.Modal(document.getElementById('modalKegiatan'));
    const modalTitle = document.getElementById("modalTitle");
    const form = document.getElementById("formKegiatan");
    const formMethodInput = document.getElementById("formMethod");
    const kegiatanIdInput = document.getElementById("kegiatanId");

    document.querySelector(".btnTambah").addEventListener("click", function () {
        modalTitle.textContent = "Tambah Data Kegiatan";
        form.reset();
        kegiatanIdInput.value = "";
        form.action = "{{ route('datakegiatan.store') }}";
        formMethodInput.value = "POST";
    });

    document.querySelectorAll(".btnEdit").forEach(btn => {
        btn.addEventListener("click", function () {
            modalTitle.textContent = "Edit Data Kegiatan";
            form.reset();

            const kegiatanId = this.dataset.id;
            const updateUrl = `/datakegiatan/${kegiatanId}`;

            form.action = updateUrl;
            formMethodInput.value = "PUT";

            kegiatanIdInput.value = kegiatanId;
            document.getElementById("kodeKegiatan").value = this.dataset.kode;
            document.getElementById("namaKegiatan").value = this.dataset.nama;
            document.getElementById("penanggungJawab").value = this.dataset.penanggung;
            document.getElementById("namaTim").value = this.dataset.tim;
            document.getElementById("fungsi").value = this.dataset.fungsi;
            document.getElementById("jenisKegiatan").value = this.dataset.jenis;
            document.getElementById("tanggalMulai").value = this.dataset.mulai;
            document.getElementById("tanggalSelesai").value = this.dataset.selesai;
        });
    });
});
</script>
@endpush