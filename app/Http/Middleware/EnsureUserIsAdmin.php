<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Pastikan login dan memiliki role admin atau superadmin
        if (! $request->user() || ! $request->user()->isAdmin()) {
            abort(403, 'Akses Ditolak: Anda tidak memiliki izin untuk mengakses Console Admin.');
        }

        return $next($request);
    }
}
