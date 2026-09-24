<?php

use App\Models\Deposit;
use App\Models\Transaction;
use App\Models\WalletTransaction;
use App\Services\AffiliateService;
use App\Services\DigiflazzService;
use App\Services\TripayService;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| API Routes & Webhook Receivers
|--------------------------------------------------------------------------
*/

Route::post('/webhook/tripay', function (Request $request, TripayService $tripay, DigiflazzService $digiflazz, WhatsAppService $wa) {
    $rawJson = $request->getContent();
    $signature = $request->header('X-Callback-Signature', '');

    if (! $tripay->validateCallbackSignature($rawJson, $signature)) {
        return response()->json(['success' => false, 'message' => 'Invalid signature'], 403);
    }

    $data = json_decode($rawJson, true);
    $status = strtoupper($data['status'] ?? '');
    $invoice = $data['merchant_ref'] ?? null;

    if (! $invoice) {
        return response()->json(['success' => false, 'message' => 'Missing merchant_ref'], 400);
    }

    // =========================================================================
    // IDEMPOTENCY & CONCURRENCY CONTROL: Redis / Cache Atomic Lock
    // =========================================================================
    $lock = Cache::lock("tripay_webhook_{$invoice}", 15);

    if (! $lock->get()) {
        Log::warning("Tripay Webhook: Concurrent request detected for invoice {$invoice}, locked.");

        return response()->json([
            'success' => false,
            'message' => 'Concurrent webhook callback in progress. Please retry.',
        ], 429);
    }

    $orderToDispatch = null;

    try {
        DB::transaction(function () use ($invoice, $status, &$orderToDispatch) {
            // Case 1: Deposit Saldo Member
            if (str_starts_with($invoice, 'DEP-')) {
                $depo = Deposit::where('deposit_number', $invoice)->lockForUpdate()->first();
                if ($depo && $status === 'PAID' && $depo->status !== 'paid') {
                    $user = $depo->user;
                    $balanceBefore = (float) $user->balance;
                    $balanceAfter = $balanceBefore + (float) $depo->amount;

                    $user->update(['balance' => $balanceAfter]);
                    $depo->update(['status' => 'paid']);

                    WalletTransaction::create([
                        'user_id' => $user->id,
                        'reference_id' => $depo->deposit_number,
                        'type' => 'credit',
                        'amount' => $depo->amount,
                        'balance_before' => $balanceBefore,
                        'balance_after' => $balanceAfter,
                        'category' => 'deposit',
                        'description' => "Deposit Saldo via Payment Gateway ({$depo->deposit_number})",
                    ]);

                    Log::info("Tripay Webhook: Deposit {$invoice} sebesar Rp {$depo->amount} berhasil dikreditkan ke User #{$user->id}");
                }

                return;
            }

            // Case 2: Pembelian Produk / PPOB
            $trx = Transaction::where('invoice_number', $invoice)->lockForUpdate()->first();
            if (! $trx) {
                Log::error("Tripay Webhook: Invoice {$invoice} not found in database.");

                return;
            }

            if ($status === 'PAID' && $trx->payment_status !== 'paid') {
                $trx->update([
                    'payment_status' => 'paid',
                    'delivery_status' => 'processing',
                ]);

                // Proses komisi referral afiliasi jika pembeli memiliki referrer
                AffiliateService::processCommission($trx);

                $orderToDispatch = $trx;
                Log::info("Tripay Webhook: Invoice {$invoice} berhasil ditandai LUNAS.");
            }
        });

        // Dispatch Digiflazz setelah database transaction committed (Idempotent: tepat 1 kali)
        if ($orderToDispatch) {
            if (config('services.digiflazz.username') || env('DIGIFLAZZ_USERNAME')) {
                $digiflazz->processTransaction($orderToDispatch);
            } else {
                $orderToDispatch->update([
                    'delivery_status' => 'success',
                    'serial_number' => 'AS-AUTOPAY-'.strtoupper(Str::random(10)),
                ]);
                $wa->sendPaymentSuccess($orderToDispatch->fresh());
            }
        }
    } finally {
        $lock->release();
    }

    return response()->json(['success' => true, 'message' => 'Webhook callback processed successfully']);
})->middleware('webhook.tripay');

// =============================================================================
// WEBHOOK CALLBACK DIGIFLAZZ (STATUS TRANSAKSI ASINKRON)
// =============================================================================
Route::post('/webhook/digiflazz', function (Request $request, WhatsAppService $wa) {
    $data = $request->input('data');
    if (! $data || empty($data['ref_id'])) {
        return response()->json(['success' => false, 'message' => 'Invalid payload format'], 400);
    }

    $refId = $data['ref_id'];
    $status = strtolower($data['status'] ?? '');
    $sn = $data['sn'] ?? null;
    $message = $data['message'] ?? '';

    $trx = Transaction::where('invoice_number', $refId)->first();
    if (! $trx) {
        return response()->json(['success' => false, 'message' => 'Transaction not found'], 404);
    }

    if ($status === 'sukses') {
        $trx->update([
            'delivery_status' => 'success',
            'serial_number' => $sn ?: $trx->serial_number,
            'provider_response' => $data,
        ]);
        $wa->sendPaymentSuccess($trx);
        Log::info("Digiflazz Webhook: Order {$refId} sukses terkirim (SN: {$sn}).");
    } elseif ($status === 'gagal') {
        $trx->update([
            'delivery_status' => 'failed',
            'provider_response' => $data,
        ]);
        $wa->sendOrderFailed($trx, $message ?: 'Ditolak oleh operator.');
        Log::warning("Digiflazz Webhook: Order {$refId} gagal diproses (Pesan: {$message}).");
    }

    return response()->json(['success' => true]);
})->middleware('webhook.digiflazz');
