<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsOrangTua
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || !auth()->user()->isOrangTua()) {
            abort(403, 'Akses ditolak. Halaman ini hanya untuk Orang Tua / Ibu Balita.');
        }

        return $next($request);
    }
}
