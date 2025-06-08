<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FirebaseAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Periksa apakah sesi 'firebase_uid' ada.
        // Ini adalah "penjaga gerbang" untuk halaman yang dilindungi.
        if (!session()->has('firebase_uid')) {
            // Jika tidak ada (belum login), "lempar" kembali ke halaman login.
            return redirect()->route('login');
        }

        // Jika ada, izinkan permintaan untuk melanjutkan ke tujuan (misal: dashboard).
        return $next($request);
    }
}
