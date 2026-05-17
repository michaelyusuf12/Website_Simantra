<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
  <div class="container-fluid px-4">
    
    {{-- BAGIAN LOGO & NAMA APLIKASI --}}
    <a class="navbar-brand d-flex align-items-center fs-3 fw-bold text-decoration-none" href="{{ url('/') }}">
        {{-- Gambar Logo BPS --}}
        <img src="{{ asset('images/logo-bps.png') }}" alt="Logo BPS" style="height: 40px;" class="me-3 bg-white p-1 rounded shadow-sm">
        {{-- Teks SIMANTRA --}}
        <span>SIMANTRA</span>
    </a>

    <div>
      @if (Request::is('/'))
        <a href="{{ route('login') }}" class="btn btn-light btn-lg fw-bold shadow-sm">Login</a>
      @else
        @auth
    <div class="user-profile d-flex align-items-center gap-2">
      @php
          // Menggunakan inisial nama jika foto kosong
          $defaultAvatar = 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->nama).'&background=ffffff&color=0d6efd&size=40';
          
          // Cek apakah kolom foto ada isinya, jika ada arahkan ke folder profiles
          $fotoProfil = Auth::user()->foto ? asset('storage/profiles/' . Auth::user()->foto) : $defaultAvatar;
      @endphp
    
      <div class="text-end d-none d-sm-block me-1">
          <div class="text-white fw-bold lh-1" style="font-size: 0.85rem;">{{ Auth::user()->nama }}</div>
          <small class="text-white-50" style="font-size: 0.7rem;">{{ ucfirst(Auth::user()->role) }}</small>
      </div>
      <img src="{{ $fotoProfil }}" alt="Profil" class="rounded-circle object-fit-cover shadow-sm" style="width: 38px; height: 38px; border: 2px solid rgba(255,255,255,0.5);">
  </div>
        @endauth
      @endif
    </div>
  </div>
</nav>