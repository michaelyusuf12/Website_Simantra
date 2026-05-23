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
        Route::get('/kelolakegiatan/cek-akumulasi', [KelolaKegiatanController::class, 'cekAkumulasi'])->name('kelolakegiatan.cekAkumulasi');
        
        // [PERBAIKAN] Pindahkan route EXPORT ke dalam grup pegawai agar rapi
        Route::post('/kelolakegiatan/export', [KelolaKegiatanController::class, 'exportExcel'])->name('kelolakegiatan.export');
        
        Route::resource('kelolakegiatan', KelolaKegiatanController::class);
    });

    // --- RUTE KHUSUS MITRA ---
    Route::middleware('role:mitra')->group(function () {
        Route::get('/mitra-beranda', [\App\Http\Controllers\MitraPanelController::class, 'beranda'])->name('mitra.beranda');
        Route::get('/mitra-riwayat', [\App\Http\Controllers\MitraPanelController::class, 'riwayat'])->name('mitra.riwayat');
        
    });

    // --- RUTE KHUSUS PPK ---
        Route::middleware('role:ppk')->group(function () {
        Route::get('/ppk-beranda', [BerandaController::class, 'index'])->name('ppk.beranda'); 
        Route::get('/ppk/approval/show/{id}', [ApprovalController::class, 'show'])->name('ppk.approval.show');
    
    // [PERBAIKAN] Baris dirapikan ke bawah
        Route::get('/ppk/approval-kontrak', [ApprovalController::class, 'index'])->name('ppk.approval');
        Route::post('/ppk/approval-kontrak/{id}/approve', [ApprovalController::class, 'approve'])->name('ppk.approval.approve');
        Route::post('/ppk/approval-kontrak/{id}/reject', [ApprovalController::class, 'reject'])->name('ppk.approval.reject');
        Route::post('/ppk/approval-kontrak/bulk-approve', [ApprovalController::class, 'bulkApprove'])->name('ppk.approval.bulkApprove');
        
        Route::get('/ppk/approval/cetak', [ApprovalController::class, 'cetakLaporan'])->name('ppk.approval.cetak');
    });

    // RUTE GLOBAL AKSES (Bisa diakses Pegawai & Admin)
    Route::resource('datakegiatan', DataKegiatanController::class);
    
    Route::get('/kelolakegiatan/show/{id}', [KelolaKegiatanController::class, 'show'])->name('kelolakegiatan.show');
    Route::get('/kelolakegiatan/{id}/cetak', [KelolaKegiatanController::class, 'cetak'])->name('kelolakegiatan.cetak');
});