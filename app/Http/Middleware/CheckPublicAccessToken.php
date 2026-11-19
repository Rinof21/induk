<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\AppSetting;

class CheckPublicAccessToken
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Cek apakah pengaturan token publik diaktifkan
        $tokenEnabled = AppSetting::getVal('public_token_enabled', '0');

        if ($tokenEnabled !== '1' && $tokenEnabled !== 1 && $tokenEnabled !== true) {
            // Token tidak aktif, semua bagian publik/fakultas bebas akses
            return $next($request);
        }

        // 2. Jika user sudah login di sistem admin, berikan akses langsung
        if (auth()->check()) {
            return $next($request);
        }

        // 3. Cek apakah ada query string ?token=... pada URL
        $queryToken = $request->query('token');
        $currentToken = AppSetting::getVal('public_access_token', 'kitasehat');

        if ($queryToken && strcasecmp($currentToken, (string)$queryToken) === 0) {
            // Token valid dari URL, simpan ke session
            $request->session()->put('public_authenticated', true);
            return $next($request);
        }

        // 4. Cek apakah pengunjung sudah terverifikasi di session
        if ($request->session()->get('public_authenticated') === true) {
            return $next($request);
        }

        // 5. Belum terverifikasi, arahkan ke halaman input token
        return redirect()->route('publik.token.gate');
    }
}

