<?php

namespace App\Jobs;

use App\Models\Transaction;
use App\Services\DigiflazzService;
use App\Services\WhatsAppService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessDigiflazzOrder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Transaction $transaction) {}

    public function handle(DigiflazzService $digiflazz, WhatsAppService $waService): void
    {
        $res = $digiflazz->topUp($this->transaction);
        $status = strtolower($res['status'] ?? 'pending');

        if ($status === 'sukses') {
            $this->transaction->update([
                'delivery_status' => 'success',
                'serial_number' => $res['sn'] ?? 'SN-GENERATED',
                'provider_response' => $res,
            ]);

            // Kirim notifikasi WhatsApp berisi SN / Bukti kirim
            $waService->sendPaymentSuccess($this->transaction);
        } elseif ($status === 'gagal') {
            $this->transaction->update([
                'delivery_status' => 'failed',
                'provider_response' => $res,
            ]);
        } else {
            // Status Masih Pending (Menunggu Webhook Callback dari Digiflazz)
            $this->transaction->update([
                'delivery_status' => 'processing',
                'provider_response' => $res,
            ]);
        }
    }
}
