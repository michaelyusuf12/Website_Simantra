@extends('layouts.master')
 
@section('title', 'Data Mitra')
 
@section('content')
<div class="container-fluid py-4">
 
    <div class="text-center mb-5">
        <h3 class="text-dark fw-bold">Data Mitra</h3>
    </div>
 
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
            <button type="button" class="btn btn-success btn-tambah" data-bs-toggle="modal" data-bs-target="#modalMitra">
                <i class="bi bi-plus-circle me-1"></i> Tambah Mitra
            </button>
        </div>
        
        <div class="col-md-6 d-flex justify-content-md-end">
            <form action="{{ route('mitra.index') }}" method="GET" style="width: 100%; max-width: 400px;">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Cari nama mitra..." value="{{ request('search') }}">
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
                    <th class="py-3">Nama Petugas</th>
                    <th class="py-3">Posisi</th>
                    <th class="py-3">Email</th>
                    <th class="py-3">Nomor Telepon</th>
                    <th class="py-3">Alamat</th>
                    <th class="py-3" style="width: 120px;">Aksi</th>
                </tr>
            </thead>
            <tbody class="align-middle">
                @forelse ($mitras as $index => $mitra)
                <tr>
                    <td class="text-center">{{ $mitras->firstItem() + $index }}</td>
                    <td class="text-start fw-medium">{{ $mitra->nama_petugas }}</td>
                    <td class="text-center">{{ $mitra->posisi_petugas }}</td>
                    <td class="text-center">{{ $mitra->email ?? '-' }}</td>
                    <td class="text-center">{{ $mitra->telepon ?? '-' }}</td>
                    <td class="text-start">{{ Str::limit($mitra->alamat, 30) ?? '-' }}</td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-2">
                            
                            {{-- 🚨 TOMBOL EDIT (Diubah jadi Outline Warning) --}}
                            <button type="button" class="btn btn-outline-warning btn-sm shadow-sm btn-edit" 
                                title="Edit Data" 
                                data-bs-toggle="modal" 
                                data-bs-target="#modalMitra"
                                data-id="{{ $mitra->sobat_id }}"
                                data-url="{{ route('mitra.update', $mitra->sobat_id) }}"
                                data-nama="{{ $mitra->nama_petugas }}"
                                data-username="{{ $mitra->user->username ?? '' }}" 
                                data-email="{{ $mitra->email }}"
                                data-telepon="{{ $mitra->telepon }}"
                                data-posisi="{{ $mitra->posisi_petugas }}"
                                data-sobat="{{ $mitra->sobat_id }}"
                                data-kodeprov="{{ $mitra->kode_prov }}"
                                data-kodekab="{{ $mitra->kode_kab }}"
                                data-alamat="{{ $mitra->alamat }}">
                                <i class="bi bi-pencil-fill"></i>
                            </button>
 
                            {{-- 🚨 TOMBOL HAPUS (Diubah jadi Outline Danger tanpa alert bawaan) --}}
                            <form action="{{ route('mitra.destroy', $mitra->sobat_id) }}" method="POST" class="d-inline form-hapus">
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
                    <td colspan="7" class="text-center py-5 text-muted">
                        <i class="bi bi-inbox fs-1 d-block mb-3 text-secondary" style="opacity: 0.5;"></i>
                        Belum ada data mitra yang terdaftar.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
 
    <div class="d-flex justify-content-end mt-3">
        @if ($mitras->hasPages())
            {{ $mitras->links() }}
        @endif
    </div>
 
    {{-- Memanggil file form modal --}}
    @include('admin.mitra.modal')
 
</div>
@endsection
 
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    
    // --- LOGIKA MODAL EDIT/TAMBAH ---
    var modalMitra = document.getElementById('modalMitra');
    if(modalMitra) {
        modalMitra.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget; 
            
            var modalTitle = document.getElementById('modalTitle');
            var formMitra = document.getElementById('formMitra');
            var inputMethod = document.getElementById('formMethod');
            var inputId = document.getElementById('mitraId');
            
            var inputNama = document.getElementById('namaPetugas');
            var inputUsername = document.getElementById('username');
            var inputPassword = document.getElementById('password');
            var inputEmail = document.getElementById('emailMitra');
            var inputTelepon = document.getElementById('telepon');
            var selectPosisi = document.getElementById('posisiPetugas');
            var inputSobat = document.getElementById('sobatId');
            var inputKodeProv = document.getElementById('kodeProvinsi');
            var inputKodeKab = document.getElementById('kodeKabupaten');
            var inputAlamat = document.getElementById('alamat');

            if (button.classList.contains('btn-edit')) {
                modalTitle.textContent = 'Edit Data Mitra';
                
                inputId.value = button.getAttribute('data-sobat_id');
                inputNama.value = button.getAttribute('data-nama');
                inputUsername.value = button.getAttribute('data-username');
                inputEmail.value = button.getAttribute('data-email');
                inputTelepon.value = button.getAttribute('data-telepon');
                selectPosisi.value = button.getAttribute('data-posisi') || '';
                inputSobat.value = button.getAttribute('data-sobat');
                inputKodeProv.value = button.getAttribute('data-kodeprov');
                inputKodeKab.value = button.getAttribute('data-kodekab');
                inputAlamat.value = button.getAttribute('data-alamat');
                
                inputMethod.value = 'PUT';
                formMitra.action = button.getAttribute('data-url'); 
                
                inputPassword.required = false;
                inputPassword.placeholder = "(Kosongkan jika tidak ingin ganti password)";

            } else {
                modalTitle.textContent = 'Tambah Data Mitra';
                formMitra.reset();
                inputId.value = '';
                
                inputKodeProv.value = '74';
                inputKodeKab.value = '04';

                inputMethod.value = '';
                formMitra.action = "{{ route('mitra.store') }}"; 
                
                inputPassword.required = true;
                inputPassword.placeholder = "";
            }
        });
    }

});
</script>
@endpush