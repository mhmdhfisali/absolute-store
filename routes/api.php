<?php

use App\Models\Transaction;
use App\Services\DigiflazzService;
use App\Services\TripayService;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

Route::post('/webhook/tripay', function (Request $request, TripayService $tripay, DigiflazzService $digiflazz, WhatsAppService $wa) {
  $rawJson = $request->getContent();
  $signature = $request->header('X-Callback-Signature', '');

  if (!$tripay->validateCallbackSignature($rawJson, $signature)) {
    return response()->json(['success' => false, 'message' => 'Invalid signature'], 403);
  }

  $data = json_decode($rawJson, true);
  $status = strtoupper($data['status'] ?? '');
  $invoice = $data['merchant_ref'] ?? null;

  $trx = Transaction::where('invoice_number', $invoice)->first();
  if (!$trx) {
    return response()->json(['success' => false, 'message' => 'Invoice not found'], 404);
  }

  if ($status === 'PAID' && $trx->payment_status !== 'paid') {
    $trx->update([
      'payment_status'  => 'paid',
      'delivery_status' => 'processing',
    ]);

    // Otomatis kirim ke Digiflazz
    if (env('DIGIFLAZZ_USERNAME')) {
      $digiflazz->processTransaction($trx);
    } else {
      $trx->update([
        'delivery_status' => 'success',
        'serial_number'   => 'AS-AUTOPAY-' . strtoupper(Str::random(10)),
      ]);
      $wa->sendPaymentSuccess($trx->fresh());
    }

    Log::info("Webhook Success: Order {$trx->invoice_number} lunas dan diproses otomatis.");
  }

  return response()->json(['success' => true]);
});
