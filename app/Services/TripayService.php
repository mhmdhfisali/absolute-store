<?php

namespace App\Services;

use App\Models\Transaction;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TripayService
{
    protected string $apiKey;

    protected string $privateKey;

    protected string $merchantCode;

    protected string $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.tripay.api_key', env('TRIPAY_API_KEY', ''));
        $this->privateKey = config('services.tripay.private_key', env('TRIPAY_PRIVATE_KEY', ''));
        $this->merchantCode = config('services.tripay.merchant_code', env('TRIPAY_MERCHANT_CODE', ''));
        $this->baseUrl = env('TRIPAY_MODE', 'sandbox') === 'production'
          ? 'https://tripay.co.id/api'
          : 'https://tripay.co.id/api-sandbox';
    }

    /**
     * Generate Pembayaran QRIS / VA
     */
    public function createTransaction(Transaction $trx, string $methodCode): array
    {
        if (empty($this->apiKey)) {
            // Mode Dev Fallback jika API key belum diisi
            return [
                'success' => true,
                'qr_url' => 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data='.$trx->invoice_number,
                'pay_code' => '8801'.substr(time(), -8),
                'reference' => 'DEV-REF-'.$trx->invoice_number,
                'checkout_url' => null,
            ];
        }

        $signature = hash_hmac('sha256', $this->merchantCode.$trx->invoice_number.(int) $trx->total_amount, $this->privateKey);

        $payload = [
            'method' => $methodCode,
            'merchant_ref' => $trx->invoice_number,
            'amount' => (int) $trx->total_amount,
            'customer_name' => 'Pelanggan Absolute',
            'customer_email' => filter_var($trx->contact_email_or_phone, FILTER_VALIDATE_EMAIL) ? $trx->contact_email_or_phone : 'customer@absolutestore.id',
            'customer_phone' => preg_replace('/[^0-9]/', '', $trx->contact_email_or_phone),
            'order_items' => [
                [
                    'sku' => $trx->productItem->sku_code,
                    'name' => $trx->productItem->name,
                    'price' => (int) $trx->total_amount,
                    'quantity' => 1,
                ],
            ],
            'return_url' => route('order.invoice', $trx->invoice_number),
            'expired_time' => (time() + (24 * 60 * 60)), // 24 jam
            'signature' => $signature,
        ];

        try {
            $response = Http::withToken($this->apiKey)->post("{$this->baseUrl}/transaction/create", $payload);
            $result = $response->json();

            if ($response->successful() && ($result['success'] ?? false)) {
                $data = $result['data'];

                return [
                    'success' => true,
                    'qr_url' => $data['qr_url'] ?? null,
                    'pay_code' => $data['pay_code'] ?? null,
                    'reference' => $data['reference'] ?? null,
                    'checkout_url' => $data['checkout_url'] ?? null,
                ];
            }

            Log::error('Tripay Create Transaction Error', ['res' => $result]);

            return ['success' => false, 'message' => $result['message'] ?? 'Gagal membuat pembayaran ke payment gateway.'];
        } catch (\Exception $e) {
            Log::error('Tripay Exception: '.$e->getMessage());

            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Validasi Signature Webhook Callback
     */
    public function validateCallbackSignature(string $rawJson, string $receivedSignature): bool
    {
        if (empty($this->privateKey)) {
            return true; // Mode dev
        }
        $expectedSignature = hash_hmac('sha256', $rawJson, $this->privateKey);

        return hash_equals($expectedSignature, $receivedSignature);
    }
}
