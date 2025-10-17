@extends('layouts.master')

@section('title', 'Data Mitra')

@section('content')
<div class="container-fluid">
    <h3 class="mb-4 text-center">Data Mitra</h3>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <button class="btn btn-success mb-3 btnTambah" data-bs-toggle="modal" data-bs-target="#modalMitra">
        <i class="bi bi-plus-circle"></i> Tambah Mitra
    </button>

    <div class="row mb-3">
        <div class="col-md-5 ms-auto">
            <form action="{{ route('mitra.index') }}" method="GET">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Cari mitra..." name="search" value="{{ request('search') }}">
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
                    <th>Nama Petugas</th>
                    <th>Posisi</th>
                    <th>Email</th>
                    <th>Nomor Telepon</th>
                    <th>Alamat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody class="align-middle">
                {{-- HAPUS DATA CONTOH @php --}}

                @forelse ($mitras as $mitra)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $mitra->nama_petugas }}</td>
                        <td>{{ $mitra->posisi_petugas }}</td>
                        <td>{{ $mitra->email }}</td>
                        <td>{{ $mitra->telepon }}</td>
                        <td>{{ $mitra->alamat }}</td>
                        <td class="text-center">
                            <button class="btn btn-warning btn-sm btnEdit"
                                    data-bs-toggle="modal" data-bs-target="#modalMitra"
                                    data-id="{{ $mitra->id }}"
                                    data-kodeprov="{{ $mitra->kode_prov }}"
                                    data-kodekab="{{ $mitra->kode_kab }}"
                                    data-email="{{ $mitra->email }}"
                                    data-sobatid="{{ $mitra->sobat_id }}"
                                    data-nama="{{ $mitra->nama_petugas }}"
                                    data-posisi="{{ $mitra->posisi_petugas }}"
                                    data-telepon="{{ $mitra->telepon }}"
                                    data-alamat="{{ $mitra->alamat }}">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <form action="{{ route('mitra.destroy', $mitra->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
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
                        <td colspan="7" class="text-center">Belum ada data mitra.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- PAGINATION DINAMIS --}}
    <div class="d-flex justify-content-end mt-3">
        {{ $mitras->links() }}
    </div>
</div>

{{-- Memanggil modal dari file terpisah --}}
@include('mitra.modal')

@endsection

@push('scripts')
{{-- Kode JavaScript untuk mengelola modal --}}
<script>
document.addEventListener("DOMContentLoaded", function () {
    const modal = new bootstrap.Modal(document.getElementById('modalMitra'));
    const modalTitle = document.getElementById("modalTitle");
    const form = document.getElementById("formMitra");
    const formMethodInput = document.getElementById("formMethod");
    const mitraIdInput = document.getElementById("mitraId");

    // Event saat tombol TAMBAH di-klik
    document.querySelector(".btnTambah").addEventListener("click", function () {
        modalTitle.textContent = "Tambah Data Mitra";
        form.reset();
        mitraIdInput.value = "";
        form.action = "{{ route('mitra.store') }}"; // <-- DIPERBAIKI
        formMethodInput.value = "POST";
    });

    // Event saat tombol EDIT di-klik
    document.querySelectorAll(".btnEdit").forEach(btn => {
        btn.addEventListener("click", function () {
            modalTitle.textContent = "Edit Data Mitra";
            form.reset();

            const mitraId = this.dataset.id;
            const updateUrl = `{{ url('mitra') }}/${mitraId}`; // <-- DIPERBAIKI

            form.action = updateUrl;
            formMethodInput.value = "PUT";

            mitraIdInput.value = mitraId;
            document.getElementById("kodeProvinsi").value = this.dataset.kodeprov;
            document.getElementById("kodeKabupaten").value = this.dataset.kodekab;
            document.getElementById("emailMitra").value = this.dataset.email;
            document.getElementById("sobatId").value = this.dataset.sobatid;
            document.getElementById("namaPetugas").value = this.dataset.nama;
            document.getElementById("posisiPetugas").value = this.dataset.posisi;
            document.getElementById("telepon").value = this.dataset.telepon;
            document.getElementById("alamat").value = this.dataset.alamat;
        });
    });
});
</script>
@endpush