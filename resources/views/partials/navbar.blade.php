<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
  <div class="container-fluid px-4">
    <a class="navbar-brand fs-3 fw-bold" href="{{ url('/') }}">SIKEPAS</a>

    <div>
      @if (Request::is('/'))
        <a href="{{ route('login') }}" class="btn btn-light btn-lg fw-bold">Login</a>
      @else
        @auth
        <div class="user-profile">
            <i class="bi bi-person-circle"></i>
            <span>{{ Auth::user()->username }}</span>
        </div>
        @endauth
      @endif
    </div>
  </div>
</nav>