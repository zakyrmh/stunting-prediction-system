<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsBidan
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || !auth()->user()->isBidan()) {
            abort(403, 'Akses ditolak. Halaman ini hanya untuk Bidan / Tenaga Kesehatan.');
        }

        return $next($request);
    }
}
