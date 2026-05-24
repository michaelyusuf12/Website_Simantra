@extends('layouts.master')
 
@section('title', 'Data Pegawai')
 
@section('content')
<div class="container-fluid py-4">
 
    <div class="text-center mb-5">
        <h3 class="text-dark fw-bold">Data Pegawai</h3>
    </div>

    {{-- Alert untuk pesan error validasi form --}}
    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Gagal Menyimpan Data!</strong> Ada isian yang tidak sesuai:
        <ul class="mb-0 mt-2">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
 
    <div class="row mb-3 align-items-center">
        <div class="col-md-6 mb-2 mb-md-0">
            <button type="button" class="btn btn-success btn-tambah" data-bs-toggle="modal" data-bs-target="#modalPegawai">
                <i class="bi bi-plus-circle me-1"></i> Tambah Pegawai
            </button>
        </div>
        
        <div class="col-md-6 d-flex justify-content-md-end">
            <form action="{{ route('pegawai.index') }}" method="GET" style="width: 100%; max-width: 400px;">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Cari nama atau NIP pegawai..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary d-flex align-items-center px-3">
                        <i class="bi bi-search me-1"></i> Cari
                    </button>
                </div>
            </form>
        </div>
    </div>
 
    <div class="table-responsive shadow-sm" style="border-radius: 10px;">
        <table class="table table-striped table-bordered table-hover bg-white mb-0" style="border-radius: 10px; overflow: hidden;">
                <thead class="table-primary text-center align-middle">
                <tr>
                    <th class="py-3" style="width: 60px;">No.</th>
                    <th class="py-3">Nama Pegawai</th>
                    <th class="py-3">NIP</th>
                    <th class="py-3">Fungsi</th>
                    <th class="py-3" style="width: 120px;">Aksi</th>
                </tr>
            </thead>
            <tbody class="align-middle">
                @forelse ($pegawais as $index => $pegawai)
                <tr>
                    <td class="text-center">{{ $pegawais->firstItem() + $index }}</td>
                    <td class="text-start fw-medium">{{ $pegawai->nama }}</td>
                    <td class="text-center">{{ $pegawai->nip ?? '-' }}</td>
                    <td class="text-center">{{ $pegawai->fungsi ?? '-' }}</td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-2">
                            
                            {{-- 🚨 TOMBOL EDIT (Diubah jadi Outline Warning) --}}
                            <button type="button" class="btn btn-outline-warning btn-sm shadow-sm btn-edit" 
                                title="Edit Data" 
                                data-bs-toggle="modal" 
                                data-bs-target="#modalPegawai"
                                data-id="{{ $pegawai->id_user }}"
                                data-url="{{ route('pegawai.update', ['pegawai' => $pegawai->id_user]) }}"
                                data-nama="{{ $pegawai->nama }}"
                                data-username="{{ $pegawai->username }}"
                                data-nip="{{ $pegawai->nip }}"
                                data-role="{{ $pegawai->role }}"
                                data-fungsi="{{ $pegawai->fungsi }}">
                                <i class="bi bi-pencil-fill"></i>
                            </button>
 
                            {{-- 🚨 TOMBOL HAPUS (Diubah jadi Outline Danger tanpa alert bawaan) --}}
                            <form action="{{ route('pegawai.destroy', $pegawai->id_user) }}" method="POST" class="d-inline form-hapus">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-outline-danger btn-sm shadow-sm btn-hapus-sweet" title="Hapus Data">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-5 text-muted">
                        <i class="bi bi-inbox fs-1 d-block mb-3 text-secondary" style="opacity: 0.5;"></i>
                        Belum ada data pegawai yang terdaftar.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
 
    <div class="d-flex justify-content-end mt-3">
        @if ($pegawais->hasPages())
            {{ $pegawais->links() }}
        @endif
    </div>
 
    @include('admin.pegawai.modal')
 
</div>
@endsection
 
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    
    // --- LOGIKA MODAL EDIT/TAMBAH ---
    var modalPegawai = document.getElementById('modalPegawai');
    if(modalPegawai) {
        modalPegawai.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget; 
            
            var modalTitle = document.getElementById('modalTitle');
            var formPegawai = document.getElementById('formPegawai');
            var inputMethod = document.getElementById('formMethod');
            var inputId = document.getElementById('pegawaiId');
            var inputNama = document.getElementById('nama');
            var inputUsername = document.getElementById('username');
            var inputPassword = document.getElementById('password');
            var inputNip = document.getElementById('nip');
            var selectRole = document.getElementById('role');
            var selectFungsi = document.getElementById('fungsi');

            if (button.classList.contains('btn-edit')) {
                modalTitle.textContent = 'Edit Data Pegawai';
                
                inputId.value = button.getAttribute('data-id');
                inputNama.value = button.getAttribute('data-nama');
                inputUsername.value = button.getAttribute('data-username');
                inputNip.value = button.getAttribute('data-nip');
                selectRole.value = button.getAttribute('data-role');
                selectFungsi.value = button.getAttribute('data-fungsi') || '';
                
                inputMethod.value = 'PUT';
                formPegawai.action = button.getAttribute('data-url');
                
                // Kosongkan kolom password dari Autofill browser!
                inputPassword.value = ''; 
                inputPassword.required = false;
                inputPassword.placeholder = "(Kosongkan jika tidak ingin ganti password)";

            } else {
                modalTitle.textContent = 'Tambah Data Pegawai';
                formPegawai.reset();
                inputId.value = '';
                
                inputMethod.value = '';
                formPegawai.action = "{{ route('pegawai.store') }}"; 
                inputPassword.required = true;
                inputPassword.placeholder = "";
            }
        });
    }

});
</script>
@endpush