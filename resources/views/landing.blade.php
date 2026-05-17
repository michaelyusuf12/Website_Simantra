@extends('layouts.guest')
@section('title', 'Selamat Datang di SIMANTRA')

{{-- Menambahkan CSS khusus untuk Halaman Landing --}}
@push('styles')
<style>
    /* 1. Pengaturan Background Utama */
    body.landing-page {
        /* Menggunakan tingkat kegelapan 0.6 agar teks putih lebih menonjol */
        background-image: linear-gradient(rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.3)), 
                          url('{{ asset('images/bps-kolaka-bg.png') }}');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        background-attachment: fixed; /* Membuat background diam saat discroll */
    }

    /* 2. Pengaturan Bayangan Teks (Keterbacaan) */
    body.landing-page h1,
    body.landing-page p.lead {
        color: white;
        text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.8);
    }

    /* 3. Wrapper Teks dengan Efek Kaca (Glassmorphism) */
    body.landing-page .text-content-wrapper {
        background-color: rgba(0, 0, 0, 0.2);
        padding: 40px;
        border-radius: 15px;
        backdrop-filter: blur(4px); /* Memberikan efek blur di belakang teks */
        border: 1px solid rgba(255, 255, 255, 0.1);
    } 

    /* 4. Efek Hover pada Tombol Masuk */
    .btn-masuk {
        transition: all 0.3s ease; /* Transisi pergerakan mulus selama 0.3 detik */
    }
    .btn-masuk:hover {
        transform: translateY(-5px); /* Tombol naik ke atas 5 pixel */
        box-shadow: 0 8px 20px rgba(13, 110, 253, 0.5); /* Bayangan biru menyala */
    }

    /* 5. Pengaturan Gaya untuk Kotak Fitur (Section Bawah) */
    .feature-box {
        background-color: rgba(255, 255, 255, 0.95);
        border-radius: 15px;
        padding: 30px 20px;
        height: 100%;
        transition: transform 0.3s ease;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }
    .feature-box:hover {
        transform: translateY(-10px); /* Kotak fitur melayang naik saat di-hover */
    }
    .feature-icon {
        font-size: 3rem;
        color: #0d6efd; /* Warna biru primary Bootstrap */
        margin-bottom: 15px;
    }
</style>
@endpush

@section('content')
{{-- HERO SECTION (Bagian Gambar Utama) --}}
{{-- min-vh-100 diubah menjadi min-vh-75 agar section fitur di bawahnya sedikit terlihat di layar --}}
<section class="d-flex align-items-center pt-5 pb-3" style="min-height: 60vh;">
  <div class="container">
    <div class="row align-items-center justify-content-center g-5">

      {{-- Kolom Teks Utama --}}
      <div class="col-md-9 col-lg-8 text-content-wrapper text-center mt-4"> 
        <h1 class="display-4 fw-bolder mb-3">
          Kelola Alokasi Kegiatan Mitra Statistik Secara Efisien
        </h1>
        <p class="lead mb-4 fw-normal">
          SIMANTRA membantu Anda mengalokasikan mitra secara cerdas untuk setiap kegiatan survei, memastikan distribusi tugas yang adil dan mencegah kelebihan honor.
        </p>
        <a href="{{ route('login') }}" class="btn btn-primary btn-lg fw-bold px-5 py-3 rounded-pill btn-masuk">
            Masuk Sekarang <i class="bi bi-arrow-right-short fs-4 align-middle"></i>
        </a>
      </div>

    </div>
  </div>
</section>

{{-- SEKSI FITUR UNGGULAN (Baru Ditambahkan) --}}
<section class="py-5 mb-5">
    <div class="container">
        <div class="row g-4 text-center justify-content-center">
            
            {{-- Fitur 1 --}}
            <div class="col-md-4">
                <div class="feature-box">
                    <i class="bi bi-shield-check feature-icon"></i>
                    <h4 class="fw-bold text-dark">Distribusi Adil</h4>
                    <p class="text-muted mb-0">Memastikan setiap mitra mendapatkan alokasi tugas yang merata dan sesuai dengan kapasitas beban kerja mereka.</p>
                </div>
            </div>
            
            {{-- Fitur 2 --}}
            <div class="col-md-4">
                <div class="feature-box">
                    <i class="bi bi-wallet2 feature-icon"></i>
                    <h4 class="fw-bold text-dark">Pantau Limit Honor</h4>
                    <p class="text-muted mb-0">Sistem pintar secara otomatis mendeteksi dan mencegah penugasan yang berpotensi melewati batas maksimum Pagu.</p>
                </div>
            </div>
            
            {{-- Fitur 3 --}}
            <div class="col-md-4">
                <div class="feature-box">
                    <i class="bi bi-graph-up-arrow feature-icon"></i>
                    <h4 class="fw-bold text-dark">Efisiensi Kinerja</h4>
                    <p class="text-muted mb-0">Mempercepat proses administrasi, persetujuan SPK, hingga pencetakan dokumen dengan satu alur kerja terintegrasi.</p>
                </div>
            </div>

        </div>
    </div>
</section>
@endsection

{{-- Script untuk menambahkan class ke body --}}
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Menambahkan class landing-page ke tag <body> saat halaman dimuat
        document.body.classList.add('landing-page');
    });
</script>
@endpush