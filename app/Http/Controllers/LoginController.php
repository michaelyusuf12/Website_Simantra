<?php

namespace App\Http\Controllers; 

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login'); 
    }

    public function authenticate(Request $request)
    {
        $rules = [
            'username' => 'required|string', 
            'password' => 'required|string|min:6',
        ];

        $messages = [
            'username.required' => 'Username / Email wajib diisi.',
            'password.required' => 'Password wajib diisi.',
            'password.min'      => 'Password minimal harus 6 karakter.',
        ];

        $credentials = $request->validate($rules, $messages);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            $user = Auth::user();
            
            // LOGIKA REDIRECT BERDASARKAN ROLE
            if ($user->role == 'admin') {
                return redirect()->intended('beranda')->with('success', 'Selamat Datang Admin!');
            } elseif ($user->role == 'pegawai') {
                return redirect()->intended('beranda')->with('success', 'Selamat Datang Pegawai!');
            } elseif ($user->role == 'ppk') { 
                return redirect()->intended('ppk-beranda')->with('success', 'Selamat Datang PPK!');
            } elseif ($user->role == 'kepala_bps') { 
                return redirect()->intended('beranda')->with('success', 'Selamat Datang Kepala BPS!');
            } elseif ($user->role == 'mitra') {
                return redirect()->intended('beranda')->with('success', 'Selamat Datang Mitra Statistik!');
            }
            
            return redirect()->intended('beranda'); 
        }

        return back()
            ->with('loginError', 'Username/Email atau password salah. Silakan coba lagi.')
            ->withInput($request->only('username'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/login');
    }
}