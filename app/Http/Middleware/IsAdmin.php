<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah user sudah login dan memiliki is_admin = true
        if (auth()->check() && auth()->user()->is_admin) {
            return $next($request);
        }

        // Jika bukan admin, tendang kembali ke halaman utama dengan error 403 (Forbidden)
        abort(403, 'Anda tidak memiliki akses ke halaman Admin.');
    }
}