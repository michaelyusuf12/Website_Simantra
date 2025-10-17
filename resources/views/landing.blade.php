@extends('layouts.guest')
@section('title', 'Selamat Datang di SIKEPAS')
@section('content')

{{-- Menambahkan padding vertikal (py-5) untuk ruang di layar kecil --}}
<section class="d-flex align-items-center min-vh-100 py-5">
  <div class="container">
    {{-- Mengganti align-items-start menjadi align-items-center untuk posisi vertikal di tengah --}}
    <div class="row align-items-center justify-content-center g-5">

      <div class="col-md-6">
        {{-- Menambahkan kelas display-4 untuk judul yang lebih besar dan menarik --}}
        <h1 class="display-4 fw-bolder mb-3">
          Kelola Alokasi Kegiatan Mitra Statistik Secara Efisien
        </h1>
        {{-- Menggunakan paragraf dengan kelas lead untuk subjudul yang lebih lembut dan jelas --}}
        <p class="lead mb-4">
          SIKEPAS membantu Anda mengalokasikan mitra secara cerdas untuk setiap kegiatan survei, memastikan distribusi tugas yang adil dan mencegah kelebihan honor.
        </p>
        
        {{-- Menambahkan tombol Call to Action (CTA) yang sangat penting --}}
        <a href="{{ route('login') }}" class="btn btn-primary btn-lg fw-bold px-4">
            Masuk Sekarang <i class="bi bi-arrow-right-short"></i>
        </a>
      </div>

      <div class="col-md-4 text-center">
        <img src="{{ asset('images/landing.png') }}" 
             alt="Ilustrasi Mitra Statistik" 
             class="img-fluid">
      </div>

    </div>
  </div>
</section>
@endsection