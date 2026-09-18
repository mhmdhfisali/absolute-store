<?php

namespace App\Services;

use App\Models\ProductItem;
use App\Models\Transaction;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class DigiflazzService
{
  protected string $username;
  protected string $apiKey;
  protected string $baseUrl;
  protected int $minBalanceAlert = 100000;

  public function __construct()
  {
    $this->username = config('services.digiflazz.username') ?? env('DIGIFLAZZ_USERNAME', '');
    $this->apiKey = config('services.digiflazz.api_key') ?? env('DIGIFLAZZ_API_KEY', '');
    $this->baseUrl = 'https://api.digiflazz.com/v1';
  }

  /**
   * Cek Saldo Akun Digiflazz
   */
  public function checkBalance(): int
  {
    $sign = md5($this->username . $this->apiKey . 'depo');

    try {
      $response = Http::timeout(10)->post("{$this->baseUrl}/cek-saldo", [
        'cmd'      => 'deposit',
        'username' => $this->username,
        'sign'     => $sign,
      ]);

      $balance = (int) ($response->json('data.deposit') ?? 0);
      $this->evaluateBalanceThreshold($balance);

      return $balance;
    } catch (\Exception $e) {
      Log::error('Digiflazz Check Balance Error: ' . $e->getMessage());
      return 0;
    }
  }

  /**
   * Evaluasi saldo dan kirim alert WhatsApp ke Admin jika tipis
   */
  public function evaluateBalanceThreshold(int $balance): void
  {
    if ($balance <= 0) {
      return;
    }

    if ($balance < $this->minBalanceAlert) {
      $cacheKey = 'digiflazz_low_balance_notified';

      if (!Cache::has($cacheKey)) {
        app(WhatsAppService::class)->sendLowBalanceAlert($balance, $this->minBalanceAlert);
        Cache::put($cacheKey, true, now()->addHours(3));
        Log::warning("Peringatan saldo rendah terkirim ke admin. Sisa: Rp {$balance}");
      }
    } else {
      Cache::forget('digiflazz_low_balance_notified');
    }
  }

  /**
   * Cek username / Nickname Game & Validasi Akun Real-Time (Inquiry)
   */
  public function checkAccount(string $gameSlug, string $targetId, ?string $zoneId = null): array
  {
    $cleanId = trim($targetId);
    $cleanZone = trim($zoneId ?? '');

    if (empty($cleanId)) {
      return ['status' => false, 'username' => null, 'message' => 'User ID wajib diisi.'];
    }

    // Mode Dev / Offline Fallback jika testing tanpa akun live Digiflazz
    if (empty($this->username) || env('DIGIFLAZZ_MODE') !== 'production') {
      return [
        'status'   => true,
        'username' => 'Player_' . substr($cleanId, 0, 5) . ($cleanZone ? " ({$cleanZone})" : ''),
        'message'  => 'Akun terverifikasi (Dev Mode)'
      ];
    }

    $customerNo = $cleanZone ? "{$cleanId}{$cleanZone}" : $cleanId;
    $refId = 'INQ-' . strtoupper(Str::random(10));
    $sign = md5($this->username . $this->apiKey . $refId);

    try {
      $response = Http::timeout(12)->post("{$this->baseUrl}/transaction", [
        'commands'       => 'inq-pasca',
        'username'       => $this->username,
        'buyer_sku_code' => $gameSlug,
        'customer_no'    => $customerNo,
        'ref_id'         => $refId,
        'sign'           => $sign,
      ]);

      $data = $response->json('data') ?? [];

      if (!empty($data['customer_name'])) {
        return [
          'status'   => true,
          'username' => $data['customer_name'],
          'message'  => 'Akun ditemukan'
        ];
      }

      return [
        'status'   => false,
        'username' => null,
        'message'  => $data['message'] ?? 'ID Akun tidak ditemukan.'
      ];
    } catch (\Exception $e) {
      Log::warning("Gagal validasi akun IGN [{$gameSlug} - {$customerNo}]: " . $e->getMessage());

      return [
        'status'   => false,
        'username' => null,
        'message'  => 'Server validasi sedang sibuk.'
      ];
    }
  }

  /**
   * Sinkronisasi Daftar Harga & SKU dari Digiflazz
   */
  public function syncPriceList(int $defaultMarginFlat = 1500, float $defaultMarginPercent = 0.0): array
  {
    $sign = md5($this->username . $this->apiKey . 'pricelist');

    try {
      $response = Http::timeout(45)->post("{$this->baseUrl}/price-list", [
        'cmd'      => 'prepaid',
        'username' => $this->username,
        'sign'     => $sign,
      ]);

      $priceListData = $response->json('data') ?? [];

      if (empty($priceListData)) {
        return [
          'success' => false,
          'message' => 'Gagal mengambil price list dari Digiflazz atau data kosong.',
          'updated' => 0,
        ];
      }

      $updatedCount = 0;
      $digiItems = collect($priceListData)->keyBy('buyer_sku_code');
      $localItems = ProductItem::all();

      foreach ($localItems as $localItem) {
        if ($digiItems->has($localItem->sku_code)) {
          $digi = $digiItems->get($localItem->sku_code);

          $costPrice = (int) ($digi['price'] ?? 0);
          $isBuyerProductActive = (bool) ($digi['buyer_product_status'] ?? false);
          $isSellerProductActive = (bool) ($digi['seller_product_status'] ?? false);
          $isAvailable = $isBuyerProductActive && $isSellerProductActive;

          $marginFromPercent = (int) round($costPrice * ($defaultMarginPercent / 100));
          $newSellingPrice = $costPrice + $defaultMarginFlat + $marginFromPercent;
          $newSellingPrice = (int) (ceil($newSellingPrice / 100) * 100);

          $localItem->update([
            'original_price' => $costPrice,
            'selling_price'  => $newSellingPrice,
            'is_available'   => $isAvailable,
          ]);

          $updatedCount++;
        }
      }

      Log::info("Digiflazz Price List Synced: {$updatedCount} items updated.");

      return [
        'success' => true,
        'message' => "Berhasil menyinkronkan {$updatedCount} item SKU dengan server Digiflazz.",
        'updated' => $updatedCount,
      ];
    } catch (\Exception $e) {
      Log::error('Digiflazz Sync Price List Error: ' . $e->getMessage());

      return [
        'success' => false,
        'message' => 'Koneksi ke Digiflazz gagal: ' . $e->getMessage(),
        'updated' => 0,
      ];
    }
  }

  /**
   * Kirim permintaan transaksi top-up ke Digiflazz
   */
  public function processTransaction(Transaction $trx): array
  {
    $sku = $trx->productItem->sku_code;
    $refId = $trx->invoice_number;

    $customerNo = $trx->target_zone
      ? $trx->target_account . $trx->target_zone
      : $trx->target_account;

    $sign = md5($this->username . $this->apiKey . $refId);

    $payload = [
      'username'       => $this->username,
      'buyer_sku_code' => $sku,
      'customer_no'    => $customerNo,
      'ref_id'         => $refId,
      'sign'           => $sign,
      'testing'        => env('DIGIFLAZZ_MODE', 'development') !== 'production',
    ];

    try {
      $response = Http::timeout(30)
        ->withHeaders(['Content-Type' => 'application/json'])
        ->post("{$this->baseUrl}/transaction", $payload);

      $result = $response->json();

      Log::info('Digiflazz Transaction Request Sent', [
        'ref_id'   => $refId,
        'response' => $result,
      ]);

      if (isset($result['data']['deposit'])) {
        $this->evaluateBalanceThreshold((int) $result['data']['deposit']);
      }

      return $this->handleProviderResponse($trx, $result);
    } catch (\Exception $e) {
      Log::error('Digiflazz Connection Error: ' . $e->getMessage(), [
        'ref_id' => $refId,
      ]);

      $trx->update([
        'delivery_status'   => 'failed',
        'provider_response' => ['error' => $e->getMessage()],
      ]);

      $wa = app(WhatsAppService::class);
      $wa->sendOrderFailed($trx, 'Koneksi ke server provider terputus: ' . $e->getMessage());
      $wa->sendAdminOrderFailedAlert($trx, $e->getMessage());

      return ['status' => 'failed', 'message' => $e->getMessage()];
    }
  }

  /**
   * Parsing respon status dari Digiflazz
   */
  protected function handleProviderResponse(Transaction $trx, ?array $result): array
  {
    $wa = app(WhatsAppService::class);

    if (!isset($result['data'])) {
      $trx->update([
        'delivery_status'   => 'failed',
        'provider_response' => $result,
      ]);

      $errorMsg = $result['message'] ?? 'Format respon provider tidak dikenali.';
      $wa->sendOrderFailed($trx, $errorMsg);
      $wa->sendAdminOrderFailedAlert($trx, $errorMsg);

      return ['status' => 'failed', 'data' => $result];
    }

    $data = $result['data'];
    $status = strtolower($data['status'] ?? '');
    $sn = $data['sn'] ?? null;
    $message = $data['message'] ?? '';

    switch ($status) {
      case 'sukses':
        $trx->update([
          'delivery_status'   => 'success',
          'serial_number'     => $sn ?: $trx->serial_number,
          'provider_response' => $data,
        ]);
        $wa->sendPaymentSuccess($trx);
        break;

      case 'pending':
        $trx->update([
          'delivery_status'   => 'processing',
          'serial_number'     => $sn,
          'provider_response' => $data,
        ]);
        break;

      case 'gagal':
      default:
        $trx->update([
          'delivery_status'   => 'failed',
          'provider_response' => $data,
        ]);
        $wa->sendOrderFailed($trx, $message ?: 'Ditolak oleh operator.');
        $wa->sendAdminOrderFailedAlert($trx, $message ?: 'Ditolak oleh operator.');
        break;
    }

    return ['status' => $status, 'sn' => $sn, 'data' => $data];
  }
}
