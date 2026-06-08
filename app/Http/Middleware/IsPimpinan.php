<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsPimpinan
{
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah user login dan merupakan pimpinan
        if (auth()->check() && auth()->user()->is_pimpinan) {
            return $next($request);
        }

        abort(403, 'Anda tidak memiliki akses ke halaman Pimpinan.');
    }
}