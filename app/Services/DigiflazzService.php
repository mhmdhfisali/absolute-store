<?php

namespace App\Services;

use App\Models\Transaction;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DigiflazzService
{
  protected string $username;
  protected string $apiKey;
  protected string $baseUrl;
  protected int $minBalanceAlert = 100000; // Batas minimal Rp 100.000

  public function __construct()
  {
    $this->username = config('services.digiflazz.username') ?? env('DIGIFLAZZ_USERNAME', '');
    $this->apiKey = config('services.digiflazz.api_key') ?? env('DIGIFLAZZ_API_KEY', '');
    $this->baseUrl = 'https://api.digiflazz.com/v1';
  }

  /**
   * Cek Saldo Akun Digiflazz & trigger alert jika menipis
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
   * Evaluasi saldo dan kirim WhatsApp ke Admin jika tipis (Cooldown 3 Jam)
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
   * Kirim permintaan top-up game, pulsa, atau token PLN ke Digiflazz
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

      // Beritahu pembeli dan admin jika terjadi kegagalan sistem
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
