<?php

use Illuminate\Support\Facades\Route;
// HAPUS AuthController JIKA TIDAK DIPAKAI LOGIN LAGI
use App\Http\Controllers\AuthController; 
use App\Http\Controllers\LoginController; 
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\KelolaKegiatanController;
use App\Http\Controllers\DataKegiatanController;
use App\Http\Controllers\MitraController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\SettingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- RUTE UNTUK TAMU (GUEST) ---
Route::middleware('guest')->group(function () {
    Route::get('/', function () { return view('landing'); })->name('landing');
    
    // PERUBAHAN 2: Arahkan ke LoginController
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    // PERUBAHAN 3: Arahkan ke LoginController dan method 'authenticate'
    Route::post('/login', [LoginController::class, 'authenticate']); 
});

// --- RUTE YANG WAJIB LOGIN (AUTHENTICATED) ---
Route::middleware('auth')->group(function () {

    // Beranda / Dashboard
    Route::get('/beranda', [BerandaController::class, 'index'])->name('beranda');

    // CRUD Utama
    Route::resource('pegawai', PegawaiController::class);
    Route::resource('mitra', MitraController::class);
    Route::resource('datakegiatan', DataKegiatanController::class);
    Route::resource('kelolakegiatan', KelolaKegiatanController::class);

    // Pengaturan Batas Honor
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');

    // PERUBAHAN 4: Arahkan logout ke LoginController juga (karena kodenya sudah ada di sana)
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});