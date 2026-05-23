@extends('layouts.master')
@section('title', 'Profil Saya')

@section('content')
<div class="container-fluid">
    <h3 class="mb-4 fw-bold">Profil Saya</h3>

    <div class="row">
        {{-- KARTU 1: DETAIL INFORMASI & FOTO --}}
        <div class="col-md-7">
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
                <div class="card-header bg-white py-3" style="border-radius: 15px 15px 0 0;">
                    <h5 class="mb-0 text-primary"><i class="bi bi-person-lines-fill me-2"></i> Detail Informasi</h5>
                </div>
                <div class="card-body p-4">
                    
                    {{-- Form Update Profil --}}
                    <form action="{{ route('profil.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- --- BAGIAN FOTO PROFIL --- --}}
                        <div class="text-center mb-4 pb-3 border-bottom">
                            <div class="position-relative d-inline-block">
                                @php
                                    // Inisial jika foto kosong
                                    $defaultAvatar = 'https://ui-avatars.com/api/?name='.urlencode($user->nama).'&background=0d6efd&color=fff&size=120';
                                    // Path foto ke folder storage/profiles
                                    $fotoPath = $user->foto ? asset('storage/profiles/' . $user->foto) : $defaultAvatar;
                                @endphp
                                
                                <img id="previewFoto" 
                                     src="{{ $fotoPath }}" 
                                     alt="Foto Profil" 
                                     class="rounded-circle shadow-sm object-fit-cover" 
                                     style="width: 120px; height: 120px; border: 3px solid #f8f9fa;">
                                
                                {{-- Tombol Kamera untuk Upload --}}
                                <label for="inputFoto" class="position-absolute bottom-0 end-0 bg-primary text-white rounded-circle p-2 shadow-sm" style="cursor: pointer; transform: translate(10%, 10%);" title="Ubah Foto">
                                    <i class="bi bi-camera-fill"></i>
                                </label>
                                
                                <input type="file" id="inputFoto" name="foto" class="d-none" accept="image/*" onchange="previewImage(event)">
                            </div>
                            <div class="mt-3">
                                <small class="text-muted">Klik ikon kamera untuk mengganti foto</small>
                            </div>
                        </div>

                        {{-- INFORMASI ROLE & USERNAME --}}
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label small text-muted">Role Pengguna</label>
                                <input type="text" class="form-control bg-light border-0 text-primary fw-bold" value="{{ strtoupper($user->role) }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small text-muted">Username (ID Login)</label>
                                <input type="text" class="form-control bg-light border-0" value="{{ $user->username }}" readonly>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="nama" class="form-label small text-muted">Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" 
                                   value="{{ old('nama', $user->nama) }}" 
                                   {{ $user->role == 'mitra' ? 'readonly bg-light' : '' }}>
                            @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            @if($user->role == 'mitra') 
                                <small class="text-info" style="font-size: 0.7rem;">*Hubungi admin untuk perubahan nama mitra</small> 
                            @endif
                        </div>

                        <hr class="my-4 opacity-25">

{{-- JIKA MITRA: Tampilkan data dari tabel Mitras --}}
                        @if($user->role == 'mitra')
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label small text-muted">No Telepon</label>
                                    <input type="text" class="form-control bg-light border-0" 
                                           value="{{ $user->dataMitra ? $user->dataMitra->telepon : '-' }}" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small text-muted">Email</label>
                                    <input type="text" class="form-control bg-light border-0" 
                                           value="{{ $user->dataMitra ? $user->dataMitra->email : '-' }}" readonly>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small text-muted">Alamat</label>
                                <textarea class="form-control bg-light border-0" rows="2" readonly>{{ $user->dataMitra ? $user->dataMitra->alamat : '-' }}</textarea>
                            </div>


                        {{-- JIKA PEGAWAI/ADMIN: Tampilkan NIP --}}
                        @else
                            <div class="mb-3">
                                <label for="nip" class="form-label small text-muted">NIP (Nomor Induk Pegawai)</label>
                                <input type="text" name="nip" class="form-control @error('nip') is-invalid @enderror" 
                                       value="{{ old('nip', $user->nip) }}" 
                                       {{ $user->role != 'admin' ? 'readonly bg-light' : '' }}>
                                @error('nip') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        @endif

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary px-4 rounded-pill shadow-sm">
                                <i class="bi bi-check-circle me-1"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- KARTU 2: KEAMANAN (PASSWORD) --}}
        <div class="col-md-5">
            <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                <div class="card-header bg-white py-3" style="border-radius: 15px 15px 0 0;">
                    <h5 class="mb-0 text-danger"><i class="bi bi-shield-lock me-2"></i> Keamanan Akun</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('profil.password') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label small text-muted">Password Saat Ini</label>
                            <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror">
                            @error('current_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label small text-muted">Password Baru</label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label small text-muted">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-danger w-100 rounded-pill mt-3 shadow-sm">Perbarui Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Fungsi untuk preview foto sebelum upload
    function previewImage(event) {
        const input = event.target;
        const reader = new FileReader();
        
        reader.onload = function(){
            const dataURL = reader.result;
            const output = document.getElementById('previewFoto');
            output.src = dataURL;
        };
        
        if(input.files && input.files[0]) {
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush