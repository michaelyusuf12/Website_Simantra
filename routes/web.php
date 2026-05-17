<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController; 
use App\Http\Controllers\LoginController; 
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\KelolaKegiatanController;
use App\Http\Controllers\DataKegiatanController;
use App\Http\Controllers\MitraController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ProfilController; 
use App\Http\Controllers\ApprovalController;

// ==========================================
// 1. RUTE UNTUK TAMU (GUEST / BELUM LOGIN)
// ==========================================
Route::middleware('guest')->group(function () {
    Route::get('/', function () { return view('landing'); })->name('landing');
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate']); 
});

// ==========================================
// 2. RUTE WAJIB LOGIN (AUTHENTICATED)
// ==========================================
Route::middleware('auth')->group(function () {

    // --- RUTE UMUM (Semua Role Bisa Akses) ---
    Route::get('/beranda', [BerandaController::class, 'index'])->name('beranda');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/profil', [ProfilController::class, 'index'])->name('profil.index');
    Route::put('/profil/update', [ProfilController::class, 'update'])->name('profil.update');
    Route::put('/profil/password', [ProfilController::class, 'updatePassword'])->name('profil.password');

    // --- RUTE KHUSUS ADMIN ---
    Route::middleware('role:admin')->group(function () {
        Route::resource('pegawai', PegawaiController::class);
        Route::resource('mitra', MitraController::class);
        Route::resource('datakegiatan', DataKegiatanController::class);
        
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
    });

    // --- RUTE KHUSUS PEGAWAI ---
    Route::middleware('role:pegawai')->group(function () {
        Route::get('/pegawai-beranda', function () { return view('pegawai.beranda'); })->name('pegawai.beranda');
        Route::get('/kelolakegiatan/cek-akumulasi', [App\Http\Controllers\KelolaKegiatanController::class, 'cekAkumulasi'])->name('kelolakegiatan.cekAkumulasi');
        Route::resource('kelolakegiatan', KelolaKegiatanController::class);
        
        // Rute untuk cek akumulasi honor via AJAX
    });

    // --- RUTE KHUSUS MITRA ---
    Route::middleware('role:mitra')->group(function () {
        Route::get('/mitra-beranda', [\App\Http\Controllers\MitraPanelController::class, 'beranda'])->name('mitra.beranda');
        Route::get('/mitra-riwayat', [\App\Http\Controllers\MitraPanelController::class, 'riwayat'])->name('mitra.riwayat');
    });

    // --- RUTE KHUSUS KEPALA BPS ---
    Route::middleware('role:kepala')->group(function () {
        Route::get('/kepala-beranda', [App\Http\Controllers\BerandaController::class, 'index'])->name('kepala.beranda');        Route::get('/kepala/approval-kontrak', [ApprovalController::class, 'index'])->name('kepala.approval');
        Route::post('/kepala/approval-kontrak/{id}/approve', [ApprovalController::class, 'approve'])->name('kepala.approval.approve');
        Route::post('/kepala/approval-kontrak/{id}/reject', [ApprovalController::class, 'reject'])->name('kepala.approval.reject');
        Route::post('/kepala/approval-kontrak/bulk-approve', [ApprovalController::class, 'bulkApprove'])->name('kepala.approval.bulkApprove');
    });

    // Rute untuk mengambil data detail penugasan via AJAX
    Route::get('/kelolakegiatan/{id}/detail', [App\Http\Controllers\KelolaKegiatanController::class, 'show'])->name('kelolakegiatan.show');

    // Rute untuk mencetak SPK
    Route::get('/kelolakegiatan/{id}/cetak', [App\Http\Controllers\KelolaKegiatanController::class, 'cetak'])->name('kelolakegiatan.cetak');
    // Rute Cetak Laporan 
    Route::get('/kepala/approval/cetak', [App\Http\Controllers\ApprovalController::class, 'cetakLaporan'])->name('kepala.approval.cetak');

});