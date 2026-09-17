<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Services\DigiflazzService;
use App\Services\WhatsAppService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentWebhookController extends Controller
{
    /**
     * Webhook Callback Pembayaran dari Tripay
     */
    public function handleTripay(
        Request $request,
        WhatsAppService $waService,
        DigiflazzService $digiflazzService
    ): JsonResponse {
        $callbackSignature = $request->header('X-Callback-Signature');
        $json = $request->getContent();
        $privateKey = config('services.tripay.private_key') ?? env('TRIPAY_PRIVATE_KEY');

        if (!$callbackSignature || !$privateKey) {
            return response()->json([
                'success' => false,
                'message' => 'Signature or private key not configured',
            ], 400);
        }

        $signature = hash_hmac('sha256', $json, $privateKey);

        if (!hash_equals($signature, (string) $callbackSignature)) {
            Log::warning('Tripay webhook invalid signature attempt', [
                'received'   => $callbackSignature,
                'calculated' => $signature,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Invalid signature',
            ], 403);
        }

        $event = $request->header('X-Callback-Event');
        $data = json_decode($json, true);

        if ($event !== 'payment_status' || !isset($data['merchant_ref'])) {
            return response()->json([
                'success' => true,
                'message' => 'Ignored event',
            ]);
        }

        $invoiceNumber = $data['merchant_ref'];
        $trx = Transaction::with(['productItem.product', 'paymentMethod'])
            ->where('invoice_number', $invoiceNumber)
            ->first();

        if (!$trx) {
            return response()->json([
                'success' => false,
                'message' => 'Transaction not found',
            ], 404);
        }

        if ($trx->payment_status === 'paid') {
            return response()->json([
                'success' => true,
                'message' => 'Transaction already processed',
            ]);
        }

        $tripayStatus = strtoupper($data['status'] ?? '');

        switch ($tripayStatus) {
            case 'PAID':
                $trx->update([
                    'payment_status'  => 'paid',
                    'delivery_status' => 'processing',
                ]);

                // Eksekusi langsung ke Digiflazz
                if (env('DIGIFLAZZ_USERNAME')) {
                    $digiflazzService->processTransaction($trx);
                } else {
                    $trx->update([
                        'delivery_status' => 'success',
                        'serial_number'   => 'AS-AUTO-' . strtoupper(\Illuminate\Support\Str::random(16)),
                        'provider_response' => $data,
                    ]);
                    $waService->sendPaymentSuccess($trx);
                }
                break;

            case 'EXPIRED':
                $trx->update([
                    'payment_status'    => 'expired',
                    'delivery_status'   => 'failed',
                    'provider_response' => $data,
                ]);
                break;

            case 'FAILED':
                $trx->update([
                    'payment_status'    => 'failed',
                    'delivery_status'   => 'failed',
                    'provider_response' => $data,
                ]);
                break;

            default:
                break;
        }

        return response()->json([
            'success' => true,
            'message' => 'Webhook successfully processed',
        ]);
    }

    /**
     * Webhook Callback Pengiriman dari Digiflazz
     */
    public function handleDigiflazz(Request $request, WhatsAppService $waService): JsonResponse
    {
        $secret = env('DIGIFLAZZ_WEBHOOK_SECRET', '');
        $postData = $request->getContent();

        if (!empty($secret)) {
            $signature = 'sha1=' . hash_hmac('sha1', $postData, $secret);
            if ($request->header('X-Hub-Signature') !== $signature) {
                return response()->json(['message' => 'Invalid Digiflazz signature'], 403);
            }
        }

        $data = $request->json('data') ?? [];
        $refId = $data['ref_id'] ?? null;

        if (!$refId) {
            return response()->json(['message' => 'Ref ID missing'], 400);
        }

        $trx = Transaction::with(['productItem.product', 'paymentMethod'])
            ->where('invoice_number', $refId)
            ->first();

        if ($trx) {
            $status = strtolower($data['status'] ?? '');
            $sn = $data['sn'] ?? null;
            $message = $data['message'] ?? '';

            if ($status === 'sukses') {
                $trx->update([
                    'delivery_status'   => 'success',
                    'serial_number'     => $sn ?: $trx->serial_number,
                    'provider_response' => $data,
                ]);
                $waService->sendPaymentSuccess($trx);
            } elseif ($status === 'gagal') {
                $trx->update([
                    'delivery_status'   => 'failed',
                    'provider_response' => $data,
                ]);
                $waService->sendOrderFailed($trx, $message ?: 'Ditolak operator');
                $waService->sendAdminOrderFailedAlert($trx, $message ?: 'Ditolak operator');
            }
        }

        return response()->json(['success' => true]);
    }
}
