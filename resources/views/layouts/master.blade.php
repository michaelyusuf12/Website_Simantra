<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SIMANTRA</title> 
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  
<style>
    .user-profile { display: flex; align-items: center; gap: 8px; }
    .user-profile i { font-size: 1.8rem; color: white; }
    .user-profile span { color: white; font-weight: 500; font-size: 16px; }
    .nav-link { transition: all 0.3s; }
</style>
</head>
<body>

  {{-- Navbar --}}
  @include('partials.navbar')

  <div class="d-flex">
    {{-- Sidebar --}}
    <div class="bg-primary text-white p-3 vh-100" style="width: 250px;">
        <ul class="nav flex-column gap-1">
            
            @php
                $userRole = auth()->user()->role;
                
                // MENENTUKAN LINK BERANDA
                // Semua role (Admin, Pegawai, PPK) akan menggunakan route 'beranda'
                $berandaLink = route('beranda'); 
                
                // Hanya Mitra yang memiliki panel dan controllernya sendiri
                if($userRole == 'mitra') $berandaLink = route('mitra.beranda'); 
            @endphp

            {{-- MENU BERANDA (Semua Aktor) --}}
            <li class="nav-item"> 
                <a href="{{ $berandaLink }}" 
                   class="nav-link {{ (request()->routeIs('beranda') || request()->routeIs('mitra.beranda') || request()->routeIs('pegawai.beranda') || request()->routeIs('ppk.beranda')) ? 'active bg-white text-primary rounded shadow-sm' : 'text-white' }}">
                    <i class="bi bi-house-door-fill me-2"></i> Beranda
                </a> 
            </li>

{{-- MENU KHUSUS ADMIN --}}
            @if($userRole == 'admin')
                <li class="nav-item"> 
                    <a href="{{ route('mitra.index') }}" class="nav-link {{ request()->routeIs('mitra.*') ? 'active bg-white text-primary rounded shadow-sm' : 'text-white' }}">
                        <i class="bi bi-people-fill me-2"></i> Data Mitra
                    </a> 
                </li>
                <li class="nav-item"> 
                    <a href="{{ route('pegawai.index') }}" class="nav-link {{ request()->routeIs('pegawai.*') ? 'active bg-white text-primary rounded shadow-sm' : 'text-white' }}">
                        <i class="bi bi-person-badge-fill me-2"></i> Data Pegawai
                    </a> 
                </li>
            @endif

            {{-- MENU DATA KEGIATAN (Diakses oleh Admin dan Pegawai) --}}
            @if($userRole == 'admin' || $userRole == 'pegawai')
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('datakegiatan*') ? 'active bg-white text-primary rounded shadow-sm' : 'text-white' }}" href="{{ route('datakegiatan.index') }}">
                        <i class="bi bi-card-list me-2"></i> Data Kegiatan
                    </a>
                </li>
            @endif

            {{-- MENU PENGATURAN --}}
            @if($userRole == 'admin')
                <li class="nav-item"> 
                    <a href="{{ route('settings.index') }}" class="nav-link {{ request()->routeIs('settings.*') ? 'active bg-white text-primary rounded shadow-sm' : 'text-white' }}">
                        <i class="bi bi-gear-fill me-2"></i> Pengaturan
                    </a> 
                </li>
            @endif

            {{-- MENU KHUSUS PEGAWAI --}}
            @if($userRole == 'pegawai')
                <li class="nav-item"> 
                    <a href="{{ route('kelolakegiatan.index') }}" 
                       class="nav-link {{ request()->routeIs('kelolakegiatan.*') ? 'active bg-white text-primary rounded shadow-sm' : 'text-white' }}">
                        <i class="bi bi-pencil-square me-2"></i> Kelola Penugasan
                    </a> 
                </li>
            @endif

            {{-- MENU KHUSUS MITRA --}}
            @if($userRole == 'mitra')
                <li class="nav-item"> 
                    <a href="{{ route('mitra.riwayat') }}" 
                       class="nav-link {{ request()->routeIs('mitra.riwayat') ? 'active bg-white text-primary rounded shadow-sm' : 'text-white' }}">
                        <i class="bi bi-clipboard-data-fill me-2"></i> Riwayat Penugasan
                    </a> 
                </li>
            @endif

            {{-- MENU KHUSUS KEPALA BPS (Bisa digabung dengan yang lain jika nanti butuh menu tambahan) --}}
            {{-- Kepala BPS saat ini hanya butuh menu Beranda --}}

            {{-- MENU KHUSUS PPK --}}
            @if($userRole == 'ppk')
                <li class="nav-item"> 
                    <a href="{{ route('ppk.approval') }}" 
                        class="nav-link {{ request()->routeIs('ppk.approval') ? 'active bg-white text-primary rounded shadow-sm' : 'text-white' }}">
                            <i class="bi bi-file-earmark-check me-2"></i> Approval Kontrak
                    </a>
                </li>
            @endif

            <hr class="text-white opacity-25">
            
            {{-- MENU PROFIL --}}
            <li class="nav-item">
                <a href="{{ route('profil.index') }}" 
                   class="nav-link {{ request()->routeIs('profil.index') ? 'active bg-white text-primary rounded shadow-sm' : 'text-white' }}">
                    <i class="bi bi-person-circle me-2"></i> Profil Saya
                </a>
            </li>

            {{-- LOGOUT --}}
            <li class="nav-item mt-auto pt-4"> 
                <form action="{{ route('logout') }}" method="POST"> 
                    @csrf 
                    <button type="submit" class="btn btn-outline-light w-100"><i class="bi bi-box-arrow-right me-2"></i> Keluar</button> 
                </form> 
            </li>
        </ul>
    </div>

    {{-- Konten --}}
    <div class="flex-grow-1 p-4" style="overflow-y: auto; height: calc(100vh - 56px);"> 
        @yield('content')
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    // ========================================================
    // 1. GLOBAL TOAST UNTUK NOTIFIKASI SUKSES / ERROR
    // ========================================================
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{!! session('success') !!}",
            showConfirmButton: false,
            timer: 2500,
            toast: true,
            position: 'top-end'
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: "{!! session('error') !!}",
            showConfirmButton: false,
            timer: 3500,
            toast: true,
            position: 'top-end'
        });
    @endif

    // ========================================================
    // 2. GLOBAL SWEETALERT UNTUK SEMUA TOMBOL HAPUS
    // ========================================================
    const btnHapusSweet = document.querySelectorAll('.btn-hapus-sweet');
    
    btnHapusSweet.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const formHapus = this.closest('.form-hapus'); 
            const namaData = this.getAttribute('data-name') || 'Data ini';

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: namaData + " akan dihapus permanen dari sistem!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="bi bi-trash"></i> Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    formHapus.submit(); 
                }
            });
        });
    });

});
</script>
  {{-- Tempat untuk menampung script dari halaman lain --}}
  @stack('scripts')
  
</body>
</html>