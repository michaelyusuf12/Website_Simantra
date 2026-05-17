<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
  <div class="container d-flex justify-content-between align-items-center">
    <a class="navbar-brand fs-3 fw-bold" href="{{ url('/') }}">SIMANTRA</a>

    {{-- Tampilkan tombol login hanya di landing --}}
    @if (Request::is('/'))
      <div>
        <a href="{{ route('login') }}" class="btn btn-light fw-bold">Login</a>
      </div>
    @endif
  </div>
</nav>
