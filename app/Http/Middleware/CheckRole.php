<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- Import class Auth
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Menangani request yang masuk.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $role  // <-- Ini adalah role yang kita inginkan (misal: 'admin')
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // 1. Cek apakah pengguna sudah login
        // 2. Cek apakah role pengguna SAMA DENGAN role yang dibutuhkan
        if (!Auth::check() || Auth::user()->role != $role) {
            
            // 3. Jika tidak, redirect ke beranda dengan pesan error
            // (Kita akan buat rute 'beranda' ini nanti)
            return redirect()->route('beranda')
                             ->with('error', 'Anda tidak memiliki hak akses untuk membuka halaman tersebut.');
        }

        // 4. Jika lolos, izinkan request melanjutkan
        return $next($request);
    }
}