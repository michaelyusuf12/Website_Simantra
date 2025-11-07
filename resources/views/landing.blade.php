@extends('layouts.guest')
@section('title', 'Selamat Datang di SIKEPAS')

{{-- Menambahkan CSS untuk background --}}
@push('styles')
<style>
    body.landing-page {
        background-image: linear-gradient(rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.3)), /* Overlay gelap agar teks terbaca */
                          url('{{ asset('images/bps-kolaka-bg.png') }}'); /* Ganti nama file jika perlu */
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        background-attachment: fixed; /* Opsi: gambar tetap saat scroll */
    }

    /* Membuat teks lebih kontras */
    body.landing-page h1,
    body.landing-page p.lead {
        color: white;
        text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.7); /* Bayangan teks */
    }

     Opsional: Memberi sedikit background pada teks agar lebih terbaca */
     body.landing-page .text-content-wrapper {
        background-color: rgba(0, 0, 0, 0.3);
        padding: 20px;
        border-radius: 8px;
    } 
</style>
@endpush

@section('content')
<section class="d-flex align-items-center min-vh-100 py-5">
  <div class="container">
    <div class="row align-items-center justify-content-center g-5">

      {{-- Kolom Teks --}}
      <div class="col-md-7 col-lg-6 text-content-wrapper text-start"> {{-- Tambah kelas wrapper jika pakai background teks --}}
        <h1 class="display-4 fw-bolder mb-3">
          Kelola Alokasi Kegiatan Mitra Statistik Secara Efisien
        </h1>
        <p class="lead mb-4">
          SIKEPAS membantu Anda mengalokasikan mitra secara cerdas untuk setiap kegiatan survei, memastikan distribusi tugas yang adil dan mencegah kelebihan honor.
        </p>
        <a href="{{ route('login') }}" class="btn btn-primary btn-lg fw-bold px-4">
            Masuk Sekarang <i class="bi bi-arrow-right-short"></i>
        </a>
      </div>

      {{-- Kolom Gambar Ilustrasi (Opsional, bisa dihapus jika tidak perlu) --}}
      {{-- <div class="col-md-5 col-lg-6 text-center d-none d-md-block"> --}}
        {{-- <img src="{{ asset('images/landing.png') }}"
             alt="Ilustrasi Mitra Statistik"
             class="img-fluid" style="max-width: 400px; opacity: 0.9;"> --}}
      {{-- </div> --}}

    </div>
  </div>
</section>
@endsection

{{-- Script untuk menambahkan class ke body --}}
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.body.classList.add('landing-page');
    });
</script>
@endpush