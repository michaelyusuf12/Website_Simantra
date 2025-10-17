<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SIKEPAS</title> {{-- Saya tambahkan title untuk kelengkapan --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  
  {{-- CSS UNTUK PROFIL PENGGUNA DI NAVBAR --}}
<style>
    /* Container untuk profil di navbar */
    .user-profile {
        display: flex;
        align-items: center;
        gap: 8px; /* Jarak bisa sedikit dikurangi */
    }

    /* Styling untuk IKON profil */
    .user-profile i {
        font-size: 1.8rem; /* Mengatur besar ikon, sekitar 28px */
        color: white;
    }

    /* Styling untuk NAMA */
    .user-profile span {
        color: white;
        font-weight: 500;
        font-size: 16px;
    }
</style>
</head>
<body>

  {{-- Navbar --}}
  @include('partials.navbar')

  <div class="d-flex">
    {{-- Sidebar --}}
    <div class="bg-primary text-white p-3 vh-100" style="width: 250px;">
      <ul class="nav flex-column">
        <li class="nav-item mb-2">
          <a href="{{ route('beranda') }}" class="nav-link text-white">
            <i class="bi bi-house-door-fill"></i> Beranda
          </a>
        </li>
        <li class="nav-item mb-2">
        <a href="{{ route('kelolakegiatan.index') }}" class="nav-link text-white">
          <i class="bi bi-clipboard2-data-fill"></i> Kelola Kegiatan
        </a>
        </li>
        <li class="nav-item mb-2">
        <a href="{{ route('mitra.index') }}" class="nav-link text-white">
          <i class="bi bi-people-fill"></i> Mitra</a>
        </li>
        <li class="nav-item mb-2">
        <a href="{{ route('datakegiatan.index') }}" class="nav-link text-white">
          <i class="bi bi-calendar-check-fill"></i> Data Kegiatan</a>
        </li>
        <a href="{{ route('profil.show') }}" class="nav-link text-white">
          <i class="bi bi-person-badge"></i> Profil Saya
        </a>
        <li class="nav-item mt-auto"> {{-- Menggunakan mt-auto agar tombol keluar menempel di bawah --}}
          <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-outline-light w-100">
              <i class="bi bi-box-arrow-right"></i> Keluar
            </button>
          </form>
        </li>
      </ul>
    </div>

    {{-- Konten --}}
    <div class="flex-grow-1 p-4" style="overflow-y: auto; height: calc(100vh - 56px);"> {{-- Menambahkan style agar konten bisa di-scroll --}}
      @yield('content')
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  @stack('scripts')
</body>
</html>