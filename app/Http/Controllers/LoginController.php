<?php

namespace App\Http\Controllers; 

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 

class LoginController extends Controller
{
    /**
     * Menampilkan form login.
     */
    public function showLoginForm()
    {
        return view('auth.login'); 
    }

    /**
     * Menangani percobaan autentikasi (proses login).
     */
    public function authenticate(Request $request)
{
    $rules = [
        'username' => 'required|string',
        'password' => 'required|string|min:6',
    ];

    $messages = [
        'username.required' => 'Username wajib diisi.',
        'password.required' => 'Password wajib diisi.',
        'password.min'      => 'Password minimal harus 6 karakter.',
    ];

    $credentials = $request->validate($rules, $messages);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        
        // LOGIKA REDIRECT BERDASARKAN ROLE
        $user = Auth::user();
        if ($user->role == 'admin') {
            return redirect()->intended('beranda')->with('success', 'Selamat Datang Admin!');
        } elseif ($user->role == 'pegawai') {
            return redirect()->intended('beranda')->with('success', 'Selamat Datang Pegawai!');
        } elseif ($user->role == 'kepala') {
            return redirect()->intended('kepala-beranda')->with('success', 'Selamat Datang Kepala BPS!');
        } elseif ($user->role == 'mitra') {
            // Mitra langsung diarahkan ke profil atau riwayat penugasan
            return redirect()->intended('beranda')->with('success', 'Selamat Datang Mitra Statistik!');
        }

        return redirect()->intended('beranda'); 
    }

    return back()
        ->with('loginError', 'Username atau password salah. Silakan coba lagi.')
        ->withInput($request->only('username'));
}

    /**
     * Menangani proses logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login'); // Redirect ke halaman login
    }
}