@extends('layouts.master')
@section('title', 'Profil Saya')
@section('content')
<div class="container mt-4">

    {{-- Header Halaman --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Profil Saya</h3>
        <a href="{{ route('beranda') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali ke Beranda
        </a>
    </div>

    {{-- Notifikasi Sukses --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Card Data Pegawai --}}
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white text-center">
            <h4>Informasi Pegawai</h4>
        </div>
        <div class="card-body p-4">
            <div class="row align-items-center">
                {{-- Bagian Foto/Ikon --}}
                <div class="col-md-4 text-center mb-3 mb-md-0">
                    @if ($pegawai->photo) 
                        <img src="{{ asset('storage/' . $pegawai->photo) }}" alt="Foto Profil" class="img-fluid rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                    @else
                        <i class="bi bi-person-circle" style="font-size: 150px;"></i>
                    @endif
                    <h5 class="fw-bold mt-3 mb-1">{{ $pegawai->nama ?? $pegawai->username }}</h5> 
                    <p class="text-muted">{{ $pegawai->username }}</p>
                </div>

                {{-- Bagian Data Detail --}}
                <div class="col-md-8">
                    <div class="row g-3">
                        {{-- NIP --}}
                        @if($pegawai->nip) 
                        <div class="col-12">
                            <label class="form-label fw-semibold">NIP</label>
                            <p class="form-control-plaintext bg-light p-2 rounded">{{ $pegawai->nip }}</p>
                        </div>
                        @endif
                        
                        {{-- Seksi --}}
                         @if($pegawai->seksi) 
                        <div class="col-12">
                            <label class="form-label fw-semibold">Seksi / Bagian</label>
                            <p class="form-control-plaintext bg-light p-2 rounded">{{ $pegawai->seksi }}</p>
                        </div>
                        @endif
                    </div>
                </div> 
            </div> 
        </div> 

        <div class="card-footer text-end">
            <a href="{{ route('profil.edit') }}" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Edit Profil
            </a>
        </div>
    </div>
</div> 
@endsection 