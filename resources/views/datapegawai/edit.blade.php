@extends('layouts.master')
@section('title', 'Edit Profil Saya')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4">Edit Profil Saya</h3>

    <div class="card shadow-sm">
        <div class="card-body">
            {{-- Form mengarah ke route 'profil.update' dengan method PUT --}}
            <form action="{{ route('profil.update') }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Tampilkan error validasi umum --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Input Username --}}
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" value="{{ old('username', $pegawai->username) }}" required>
                    @error('username')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- >>> INPUT NIP (DIAKTIFKAN) <<< --}}
                <div class="mb-3">
                    <label for="nip" class="form-label">NIP</label>
                    <input type="text" class="form-control @error('nip') is-invalid @enderror" id="nip" name="nip" value="{{ old('nip', $pegawai->nip) }}">
                    @error('nip')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                {{-- >>> INPUT SEKSI (DIAKTIFKAN) <<< --}}
                <div class="mb-3">
                    <label for="seksi" class="form-label">Seksi / Bagian</label>
                    <input type="text" class="form-control @error('seksi') is-invalid @enderror" id="seksi" name="seksi" value="{{ old('seksi', $pegawai->seksi) }}">
                    @error('seksi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="text-end mt-4">
                    <a href="{{ route('profil.show') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection