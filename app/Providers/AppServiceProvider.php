<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Pembatasan Rate Limiting Khusus Keamanan & Anti-Abuse
        RateLimiter::for('invoice-search', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip())->response(function () {
                return response()->json([
                    'success' => false,
                    'message' => 'Terlalu banyak permintaan pelacakan pesanan. Silakan coba lagi dalam 1 menit.',
                ], 429);
            });
        });

        RateLimiter::for('checkout', function (Request $request) {
            return Limit::perMinute(5)->by($request->user()?->id ?: $request->ip())->response(function () {
                return response()->json([
                    'success' => false,
                    'message' => 'Batas frekuensi checkout tercapai. Harap tunggu beberapa saat.',
                ], 429);
            });
        });

        RateLimiter::for('api-login', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip())->response(function () {
                return response()->json([
                    'success' => false,
                    'message' => 'Terlalu banyak percobaan autentikasi. Akun ditangguhkan sementara selama 1 menit.',
                ], 429);
            });
        });
    }
}
