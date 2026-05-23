@extends('layouts.guest')
@section('title', 'Login')

{{-- Tambahan CSS khusus halaman login --}}
@push('styles')
<style>
    /* Mengubah latar belakang seluruh halaman menjadi abu-abu sangat terang */
    body {
        background-color: #f4f7fa; 
    }
    
    /* Memperhalus input group agar icon dan kolom input menyatu dengan cantik */
    .input-group-text {
        border-right: none;
    }
    .input-group .form-control {
        border-left: none;
    }
    .input-group .form-control:focus {
        box-shadow: none;
        border-color: #dee2e6;
    }
    .input-group:focus-within {
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        border-radius: 0.375rem;
    }
</style>
@endpush

@section('content')
<section class="d-flex align-items-center min-vh-100 py-5">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-10">
        
        {{-- Kotak Login Utama: Sudut lebih melengkung (rounded-4) & bayangan besar (shadow-lg) --}}
        <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
          <div class="row g-0">
            
            {{-- KOLOM KIRI: Ilustrasi & Sapaan --}}
            <div class="col-md-6 d-none d-md-flex flex-column align-items-center justify-content-center p-5" style="background: linear-gradient(135deg, #e0eafc, #cfdef3);">
              <img src="{{ asset('images/login.png') }}" alt="Ilustrasi Login" class="img-fluid mb-4" style="max-width: 85%;">
              <h4 class="fw-bold text-primary mb-2">Selamat Datang!</h4>
              <p class="text-muted text-center px-3">Masuk ke SIMANTRA untuk mengelola alokasi kegiatan mitra statistik dengan lebih mudah dan efisien.</p>
            </div>

            {{-- KOLOM KANAN: Form Login --}}
            <div class="col-md-6 d-flex align-items-center bg-white">
              <div class="card-body p-4 p-md-5 w-100">
                <h3 class="text-center fw-bold mb-4 text-dark">Login <span class="text-primary">SIMANTRA</span></h3>

                {{-- Menampilkan error login umum (jika ada) --}}
                @if(session('loginError'))
                    <div class="alert alert-danger rounded-3" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('loginError') }}
                    </div>
                @endif
                
                <form action="{{ route('login') }}" method="POST">
                  @csrf
                  
                  {{-- Input Username dengan Ikon --}}
                  <div class="mb-4">
                    <label for="username" class="form-label fw-medium text-muted small text-uppercase">Username / Email (Khusus Mitra)</label>
                    <div class="input-group has-validation">
                        <span class="input-group-text bg-white text-primary">
                            <i class="bi bi-person-fill"></i>
                        </span>
                        <input type="text" id="username" name="username" class="form-control form-control-lg @error('username') is-invalid @enderror" value="{{ old('username') }}" placeholder="Masukkan username" required autofocus autocomplete="off">
                        @error('username')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                  </div>

                  {{-- Input Password dengan Ikon dan Tombol Mata --}}
                  <div class="mb-4">
                    <label for="password" class="form-label fw-medium text-muted small text-uppercase">Password</label>
                    <div class="input-group has-validation">
                        <span class="input-group-text bg-white text-primary">
                            <i class="bi bi-lock-fill"></i>
                        </span>
                        <input type="password" id="password" name="password" class="form-control form-control-lg @error('password') is-invalid @enderror" placeholder="Masukkan password" required autocomplete="off" minlength="6">
                        <button class="btn btn-outline-secondary bg-white" type="button" id="togglePassword" style="border-left: none; border-color: #dee2e6;">
                            <i class="bi bi-eye text-muted"></i>
                        </button>
                        
                        @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                  </div>
                  
                  {{-- Tombol Login --}}
                  <div class="d-grid mt-5">
                    <button type="submit" class="btn btn-primary btn-lg fw-bold rounded-pill shadow-sm py-3">MASUK</button>
                  </div>
                  
                </form>
              </div>
            </div>
            
          </div>
        </div>
        
      </div>
    </div>
  </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Fitur Toggle Password (Sembunyikan/Tampilkan)
    const togglePassword = document.getElementById('togglePassword');
    const password = document.getElementById('password'); 
    const icon = togglePassword.querySelector('i');

    togglePassword.addEventListener('click', function () {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        
        // Ganti ikon mata
        icon.classList.toggle('bi-eye');
        icon.classList.toggle('bi-eye-slash');
        icon.classList.toggle('text-primary'); // Opsional: Beri warna biru saat password terlihat
    });

    // Custom Validation Message untuk Password
    password.addEventListener("invalid", function(event) {
        if (password.validity.tooShort) {
            password.setCustomValidity("Password minimal harus 6 karakter.");
        } else if (password.validity.valueMissing) {
            password.setCustomValidity("Password wajib diisi.");
        }
    });

    password.addEventListener("input", function(event) {
        password.setCustomValidity("");
    });
});
</script>
@endpush