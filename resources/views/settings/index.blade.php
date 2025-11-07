@extends('layouts.master')
@section('title', 'Pengaturan Batas Honor')

@section('content')
<div class="container-fluid">

    {{-- Header dengan Pilihan Tahun --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Pengaturan Batas Honor</h3>
        <form action="{{ route('settings.index') }}" method="GET" id="filterForm">
             <div class="input-group input-group-sm">
                <span class="input-group-text">Tahun:</span>
                <select class="form-select" name="year" onchange="this.form.submit()">
                    @foreach($availableYears as $year)
                        <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>{{ $year }}</option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>

    {{-- Notifikasi --}}
    @if (session('success'))
       <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }} <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
     @if ($errors->any())
       <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Gagal menyimpan!</strong> Periksa input Anda.
             <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Form Utama untuk Simpan --}}
    <div class="card shadow-sm mt-4">
         <div class="card-header text-center">
            <h5>Edit Batas Honor Bulanan - Tahun {{ $selectedYear }}</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('settings.update') }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="tahun" value="{{ $selectedYear }}">

                {{-- Batas Honor Lapangan --}}
                <div class="mb-3 row align-items-center">
                    <label for="batas_honor_lapangan" class="col-sm-3 col-form-label fw-semibold">Batas Honor Lapangan (Rp)</label>
                    <div class="col-sm-6">
                        <input type="number" step="1000" class="form-control @error('batas_honor_lapangan') is-invalid @enderror"
                               id="batas_honor_lapangan" name="batas_honor_lapangan"
                               value="{{ old('batas_honor_lapangan', $settingLapangan->batas_honor ?? 6000000) }}" required>
                        @error('batas_honor_lapangan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                         <small class="form-text text-muted">Batas honor bulanan yang berlaku sepanjang tahun {{ $selectedYear }} untuk posisi Lapangan (Kode: 1).</small>
                    </div>
                </div>

                {{-- Batas Honor Pengolahan --}}
                <div class="mb-3 row align-items-center">
                    <label for="batas_honor_pengolahan" class="col-sm-3 col-form-label fw-semibold">Batas Honor Pengolahan (Rp)</label>
                    <div class="col-sm-6">
                        <input type="number" step="1000" class="form-control @error('batas_honor_pengolahan') is-invalid @enderror"
                               id="batas_honor_pengolahan" name="batas_honor_pengolahan"
                               value="{{ old('batas_honor_pengolahan', $settingPengolahan->batas_honor ?? 4500000) }}" required>
                         @error('batas_honor_pengolahan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                         <small class="form-text text-muted">Batas honor bulanan yang berlaku sepanjang tahun {{ $selectedYear }} untuk posisi Pengolahan (Kode: 2).</small>
                    </div>
                </div>

                <hr>
                <div class="text-end">
                    <button type="submit" class="btn btn-primary">Simpan Pengaturan Tahun {{ $selectedYear }}</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection