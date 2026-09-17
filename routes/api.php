<?php

use App\Http\Controllers\Api\PaymentWebhookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Webhook Callback Tripay (Payment Status)
Route::post('/webhook/tripay', [PaymentWebhookController::class, 'handleTripay']);

// Webhook Callback Digiflazz (Delivery SN / Status)
Route::post('/webhook/digiflazz', [PaymentWebhookController::class, 'handleDigiflazz']); // Callback Webhook Payment Gateway Tripay
Route::post('/webhook/tripay', [PaymentWebhookController::class, 'handleTripay'])->name('webhook.tripay');
