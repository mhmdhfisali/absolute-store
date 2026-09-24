<?php

namespace App\Livewire\Admin;

use App\Models\AuditLog;
use App\Models\Product;
use App\Services\DigiflazzService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class SystemDiagnostics extends Component
{
    // Inquiry Sandbox Fields
    public ?int $selectedProductId = null;

    public string $targetAccount = '';

    public string $targetZone = '';

    public ?array $inquiryResult = null;

    public bool $isInquiring = false;

    // Health Check Statuses
    public array $systemStatus = [];

    public function mount(): void
    {
        $this->runHealthChecks();
        $this->selectedProductId = Product::where('is_active', true)->first()?->id;
    }

    public function runHealthChecks(): void
    {
        // 1. Database Check
        $dbStart = microtime(true);
        $dbOk = false;
        try {
            DB::select('SELECT 1');
            $dbOk = true;
        } catch (\Exception $e) {
            $dbOk = false;
        }
        $dbLatency = round((microtime(true) - $dbStart) * 1000, 2);

        // 2. Cache / Redis Check
        $cacheStart = microtime(true);
        $cacheOk = false;
        try {
            Cache::put('health_ping', 'ok', 10);
            $cacheOk = Cache::get('health_ping') === 'ok';
        } catch (\Exception $e) {
            $cacheOk = false;
        }
        $cacheLatency = round((microtime(true) - $cacheStart) * 1000, 2);

        // 3. Tripay Gateway Check
        $tripayStart = microtime(true);
        $tripayOk = false;
        try {
            $res = Http::timeout(3)->get('https://tripay.co.id/api/merchant/payment-channel');
            $tripayOk = $res->status() < 500;
        } catch (\Exception $e) {
            $tripayOk = false;
        }
        $tripayLatency = round((microtime(true) - $tripayStart) * 1000, 2);

        // 4. Digiflazz Supplier API Check
        $digiStart = microtime(true);
        $digiOk = false;
        try {
            $res = Http::timeout(3)->post('https://api.digiflazz.com/v1/cek-saldo', [
                'cmd' => 'ping',
            ]);
            $digiOk = $res->status() < 500;
        } catch (\Exception $e) {
            $digiOk = false;
        }
        $digiLatency = round((microtime(true) - $digiStart) * 1000, 2);

        $this->systemStatus = [
            'database' => ['ok' => $dbOk, 'latency' => $dbLatency, 'name' => 'MySQL Database Engine'],
            'cache' => ['ok' => $cacheOk, 'latency' => $cacheLatency, 'name' => 'Cache & Redis Store'],
            'tripay' => ['ok' => $tripayOk, 'latency' => $tripayLatency, 'name' => 'Tripay Payment Gateway'],
            'digiflazz' => ['ok' => $digiOk, 'latency' => $digiLatency, 'name' => 'Digiflazz Supplier Server'],
        ];
    }

    public function testAccountInquiry(DigiflazzService $digiflazz): void
    {
        $this->validate([
            'selectedProductId' => 'required|exists:products,id',
            'targetAccount' => 'required|string|max:100',
        ]);

        $this->isInquiring = true;
        $product = Product::findOrFail($this->selectedProductId);

        try {
            $result = $digiflazz->checkAccount(
                $product->slug,
                $this->targetAccount,
                $this->targetZone ?: null
            );

            $this->inquiryResult = [
                'success' => $result['status'],
                'nickname' => $result['username'] ?? 'Tidak ditemukan',
                'raw' => $result,
                'timestamp' => now()->format('H:i:s WIB'),
            ];

            AuditLog::log('TEST_INQUIRY', "Menjalankan diagnostik tes akun {$this->targetAccount} untuk produk {$product->name}");
        } catch (\Exception $e) {
            $this->inquiryResult = [
                'success' => false,
                'nickname' => null,
                'error' => $e->getMessage(),
                'timestamp' => now()->format('H:i:s WIB'),
            ];
        } finally {
            $this->isInquiring = false;
        }
    }

    public function clearSystemCache(): void
    {
        Cache::flush();
        AuditLog::log('FLUSH_CACHE', 'Membersihkan cache aplikasi via Diagnostics Console');

        $this->dispatch('show-toast', [
            'type' => 'success',
            'title' => 'Cache Dibersihkan',
            'message' => 'Cache memori sistem berhasil dibersihkan.',
        ]);

        $this->runHealthChecks();
    }

    public function render()
    {
        $products = Product::where('is_active', true)->orderBy('name')->get();

        return view('livewire.admin.system-diagnostics', compact('products'))->layout('layouts.app');
    }
}
