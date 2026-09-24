<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request and set the active application locale.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->cookie('locale') ?? Session::get('locale');

        if (! $locale || ! in_array($locale, ['id', 'en'], true)) {
            $locale = config('app.locale', 'id');
        }

        App::setLocale($locale);

        return $next($request);
    }
}
