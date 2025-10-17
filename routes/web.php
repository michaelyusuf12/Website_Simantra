<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\KelolaKegiatanController;
use App\Http\Controllers\DataKegiatanController;
use App\Http\Controllers\MitraController;
use App\Http\Controllers\ProfilController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- RUTE UNTUK TAMU (GUEST) ---
// Hanya bisa diakses oleh pengguna yang BELUM login.
Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return view('landing');
    })->name('landing');

    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// --- RUTE YANG WAJIB LOGIN (AUTHENTICATED) ---
// Semua route di dalam grup ini WAJIB login untuk diakses.
Route::middleware('auth')->group(function () {
    
    // Beranda / Dashboard (Sekarang aman)
    Route::get('/beranda', [BerandaController::class, 'index'])->name('beranda');

    // Profil Pengguna
    Route::get('/profil', [ProfilController::class, 'show'])->name('profil.show');
    Route::get('/profil/edit', [ProfilController::class, 'edit'])->name('profil.edit');
    Route::put('/profil', [ProfilController::class, 'update'])->name('profil.update');

    // Resource Controllers untuk modul CRUD 
    Route::resource('mitra', MitraController::class);
    Route::resource('datakegiatan', DataKegiatanController::class);
    Route::resource('kelolakegiatan', KelolaKegiatanController::class);
    
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// --- RUTE PUBLIK SEMENTARA (SEKARANG KOSONG) ---
// Pastikan tidak ada route aplikasi utama di sini lagi.