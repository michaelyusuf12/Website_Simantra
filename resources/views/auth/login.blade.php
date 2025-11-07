@extends('layouts.guest')
@section('title', 'Login')
@section('content')
    
<section class="d-flex align-items-center min-vh-100 py-5">
  <div class="container">
    {{-- Menggunakan Bootstrap Row untuk layout yang lebih seimbang --}}
    <div class="row justify-content-center">
      <div class="col-lg-10">
        <div class="card shadow-lg border-0 rounded-3">
          <div class="row g-0">
            <div class="col-md-6 d-none d-md-flex align-items-center justify-content-center p-5" style="background-color: #f8f9fa;">
              <img src="{{ asset('images/login.png') }}" alt="Ilustrasi Login" class="img-fluid">
            </div>

            <div class="col-md-6 d-flex align-items-center">
              <div class="card-body p-4 p-md-5">
                <h3 class="text-center fw-bold mb-4">Login SIKEPAS</h3>

                {{-- Menampilkan error login umum (jika ada) --}}
                @if(session('loginError'))
                    <div class="alert alert-danger" role="alert">
                        {{ session('loginError') }}
                    </div>
                @endif
                
                {{-- Method bisa POST saja, karena route login default biasanya POST --}}
                <form action="{{ route('login') }}" method="POST">
                  @csrf
                  <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" id="username" name="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username') }}" required autofocus autocomplete="off">
                    {{-- Error username sudah benar di sini --}}
                    @error('username')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                  </div>

                  <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    
                    <div class="input-group has-validation">
                        <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" required autocomplete="off" minlength="6">
                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                            <i class="bi bi-eye"></i>
                        </button>
                        
                        @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                  </div>
                  <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg fw-bold">LOGIN</button>
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
    const togglePassword = document.getElementById('togglePassword');
    const password = document.getElementById('password'); // <-- Kita sudah punya variabel 'password'
    const icon = togglePassword.querySelector('i');

    togglePassword.addEventListener('click', function () {
        // Toggle tipe input antara password dan text
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        
        // Ganti ikon mata
        icon.classList.toggle('bi-eye');
        icon.classList.toggle('bi-eye-slash');
    });

    // -----------------------------------------------------------------
    // /// --- PERBAIKANNYA DI SINI --- ///
    // Script untuk mengganti pesan validasi bawaan browser
    // -----------------------------------------------------------------
    
    // Saat browser mendeteksi error (sebelum submit)
    password.addEventListener("invalid", function(event) {
        // Cek jika errornya adalah karena 'minlength' (terlalu pendek)
        if (password.validity.tooShort) {
            password.setCustomValidity("Password minimal harus 6 karakter.");
        } 
        // Cek jika errornya adalah karena 'required' (kosong)
        else if (password.validity.valueMissing) {
            password.setCustomValidity("Password wajib diisi.");
        }
    });

    // Penting: Hapus pesan custom saat pengguna mulai mengetik lagi
    // Jika tidak, form tidak akan bisa disubmit
    password.addEventListener("input", function(event) {
        password.setCustomValidity("");
    });

});
</script>
@endpush