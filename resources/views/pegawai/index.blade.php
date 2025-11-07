@extends('layouts.master')
@section('title', 'Data Pegawai')
@section('content')
<div class="container-fluid">
    <h3 class="mb-4 text-center">Data Pegawai</h3>

     @if (session('error')) {{-- Tambahkan ini untuk pesan error --}}
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <button class="btn btn-success mb-3 btnTambah" data-bs-toggle="modal" data-bs-target="#modalPegawai">
        <i class="bi bi-plus-circle"></i> Tambah Pegawai
    </button>

    <div class="row mb-3">
        <div class="col-md-5 ms-auto">
            <form action="{{ route('pegawai.index') }}" method="GET">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Cari username atau NIP..." name="search" value="{{ request('search') }}">
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
                    <th>Username</th>
                    <th>NIP</th>
                    <th>Fungsi/Seksi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody class="align-middle">
                @forelse ($pegawais as $pegawai)
                    <tr>
                        <td class="text-center">{{ $loop->iteration + $pegawais->firstItem() - 1 }}</td>
                        <td>{{ $pegawai->username }}</td>
                        <td class="text-center">{{ $pegawai->nip ?? '-' }}</td>
                        <td class="text-center">{{ $pegawai->fungsi ?? '-' }}</td>
                        <td class="text-center">
                            <button class="btn btn-warning btn-sm btnEdit"
                                    data-bs-toggle="modal" data-bs-target="#modalPegawai"
                                    data-id="{{ $pegawai->id }}"
                                    data-username="{{ $pegawai->username }}"
                                    data-nip="{{ $pegawai->nip }}"
                                    data-fungsi="{{ $pegawai->fungsi }}">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <form action="{{ route('pegawai.destroy', $pegawai->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pegawai ini?');">
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
                        <td colspan="5" class="text-center">Belum ada data pegawai.</td>
                    </tr>
                @endforelse </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-end mt-3">
        {{ $pegawais->links() }}
    </div>
</div>

@include('pegawai.modal') {{-- Panggil modal --}}
@endsection

@push('scripts')
{{-- Kode JavaScript untuk modal pegawai --}}
<script>
document.addEventListener("DOMContentLoaded", function () {
    const modalPegawaiElement = document.getElementById('modalPegawai');
    const modal = new bootstrap.Modal(modalPegawaiElement);
    
    const modalTitle = document.getElementById("modalTitle");
    const form = document.getElementById("formPegawai");
    const formMethodInput = document.getElementById("formMethod");
    const pegawaiIdInput = document.getElementById("pegawaiId");
    const passwordInput = document.getElementById("password");
    const passwordLabel = document.querySelector('label[for="password"]'); 

    // Event saat tombol TAMBAH di-klik
    document.querySelector(".btnTambah").addEventListener("click", function () {
        modalTitle.textContent = "Tambah Data Pegawai";
        form.reset();
        
        form.querySelectorAll('.form-control').forEach(el => el.classList.remove('is-invalid'));
        form.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = ''); 

        pegawaiIdInput.value = "";
        form.action = "{{ route('pegawai.store') }}"; 
        formMethodInput.value = "POST";
        passwordInput.required = true; 
        passwordLabel.textContent = "Password"; 
    });

    // Event saat tombol EDIT di-klik
    document.querySelectorAll(".btnEdit").forEach(btn => {
        btn.addEventListener("click", function () {
            modalTitle.textContent = "Edit Data Pegawai";
            form.reset();

            form.querySelectorAll('.form-control').forEach(el => el.classList.remove('is-invalid'));
            form.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = ''); 

            const pegawaiId = this.dataset.id;
            const updateUrl = `{{ url('pegawai') }}/${pegawaiId}`;
            
            form.action = updateUrl;
            formMethodInput.value = "PUT";

            pegawaiIdInput.value = pegawaiId;
            document.getElementById("username").value = this.dataset.username;
            document.getElementById("nip").value = this.dataset.nip;
            document.getElementById("fungsi").value = this.dataset.fungsi;
            
            passwordInput.required = false; 
            passwordLabel.textContent = "Password (kosongkan jika tidak ingin diubah)"; 
        });
    });

    // Script untuk membuka modal secara otomatis jika ada error validasi
    @if ($errors->any() && session('show_modal_tambah'))
        modalTitle.textContent = "Tambah Data Pegawai";
        form.action = "{{ route('pegawai.store') }}"; 
        formMethodInput.value = "POST";
        passwordInput.required = true; 
        passwordLabel.textContent = "Password";
        modal.show();
    @endif

    // Script untuk mengganti pesan validasi 'minlength' & 'required' 
    // bawaan browser ke Bahasa Indonesia.
    const passwordInputForValidity = document.getElementById("password");
    
    // Saat browser mendeteksi error (sebelum submit)
    passwordInputForValidity.addEventListener("invalid", function(event) {
        // Cek jika errornya adalah karena 'minlength' (terlalu pendek)
        if (passwordInputForValidity.validity.tooShort) {
            passwordInputForValidity.setCustomValidity("Password minimal harus 6 karakter.");
        } 
        // Cek jika errornya adalah karena 'required' (kosong)
        else if (passwordInputForValidity.validity.valueMissing) {
            passwordInputForValidity.setCustomValidity("Password wajib diisi.");
        }
    });

    // Penting: Hapus pesan custom saat pengguna mulai mengetik lagi
    passwordInputForValidity.addEventListener("input", function(event) {
        passwordInputForValidity.setCustomValidity("");
    });

});
</script>
@endpush