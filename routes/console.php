<?php

use App\Models\Transaction;
use App\Services\DigiflazzService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// ==========================================
// 1. PENJADWALAN CEK SALDO DIGIFLAZZ (TIAP JAM)
// ==========================================
Schedule::call(function (DigiflazzService $digiflazz) {
    if (! env('DIGIFLAZZ_USERNAME')) {
        return;
    }

    $balance = $digiflazz->checkBalance();
    Log::info('[Scheduler] Pengecekan Saldo Digiflazz: Rp '.number_format($balance, 0, ',', '.'));
})->hourly()->name('check-digiflazz-balance')->withoutOverlapping();

// ==========================================
// 2. SINKRONISASI HARGA & SKU OTOMATIS (SETIAP HARI PUKUL 03:00)
// ==========================================
Schedule::command('digiflazz:sync --margin-flat=1500')
    ->dailyAt('03:00')
    ->name('daily-sync-digiflazz-prices')
    ->withoutOverlapping();

// ==========================================
// 3. EXPIRE INVOICE KEDALUWARSA (TIAP 10 MENIT)
// ==========================================
Schedule::call(function () {
    $expiredCount = Transaction::where('payment_status', 'unpaid')
        ->where('created_at', '<', now()->subHours(2))
        ->update([
            'payment_status' => 'expired',
            'delivery_status' => 'failed',
        ]);

    if ($expiredCount > 0) {
        Log::info("[Scheduler] {$expiredCount} transaksi kedaluwarsa berhasil diubah ke expired.");
    }
})->everyTenMinutes()->name('auto-expire-invoices')->withoutOverlapping();
