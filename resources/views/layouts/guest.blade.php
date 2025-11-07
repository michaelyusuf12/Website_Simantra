<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'SIKEPAS')</title> {{-- Tambahkan title --}}

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    {{-- Tempat untuk menempelkan CSS dari halaman anak --}}
    @stack('styles') 
</head>
<body>

    {{-- Navbar Guest --}}
    {{-- @include('partials.navbar-guest') --}} {{-- Navbar mungkin tidak perlu di landing/login --}}
    
    {{-- Jika Anda MENGGUNAKAN navbar di landing/login, gunakan navbar biasa --}}
    @include('partials.navbar') 

    <main>
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    
    {{-- Tempat untuk menempelkan JavaScript dari halaman anak --}}
    @stack('scripts') 
</body>
</html>