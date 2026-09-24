<?php

namespace App\Services;

use App\Models\Transaction;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected string $token;

    protected string $endpoint;

    public function __construct()
    {
        $this->token = env('FONNTE_TOKEN', '');
        $this->endpoint = 'https://api.fonnte.com/send';
    }

    /**
     * Konversi format nomor lokal Indonesia (08xxx -> 628xxx)
     */
    protected function formatPhoneNumber(string $number): string
    {
        $cleaned = preg_replace('/[^0-9]/', '', $number);

        if (str_starts_with($cleaned, '0')) {
            return '62'.substr($cleaned, 1);
        }

        return $cleaned;
    }

    /**
     * Kirim pesan notifikasi pembuatan invoice baru (Menunggu Pembayaran)
     */
    public function sendInvoiceCreated(Transaction $trx): void
    {
        $target = $this->formatPhoneNumber($trx->contact_email_or_phone);
        $invoiceUrl = route('order.invoice', $trx->invoice_number);

        $message = "*INVOICE PESANAN BARU - ABSOLUTE STORE*\n\n"
          ."Halo, pesanan Anda telah berhasil dibuat dengan rincian:\n"
          ."• *No. Invoice:* `{$trx->invoice_number}`\n"
          ."• *Layanan:* {$trx->productItem->product->name}\n"
          ."• *Item:* {$trx->productItem->name}\n"
          ."• *Target Akun:* {$trx->target_account}".($trx->target_zone ? " ({$trx->target_zone})" : '')."\n"
          ."• *Metode Bayar:* {$trx->paymentMethod->name}\n"
          .'• *Total Tagihan:* *Rp '.number_format($trx->total_amount, 0, ',', '.')."*\n\n"
          ."Selesaikan pembayaran Anda melalui tautan resmi berikut:\n"
          ."🔗 {$invoiceUrl}\n\n"
          .'_Pesan ini dibuat otomatis oleh sistem. Abaikan jika Anda sudah membayar._';

        $this->dispatchMessage($target, $message);
    }

    /**
     * Kirim pesan notifikasi pembayaran berhasil & Serial Number / Voucher terbit
     */
    public function sendPaymentSuccess(Transaction $trx): void
    {
        $target = $this->formatPhoneNumber($trx->contact_email_or_phone);
        $invoiceUrl = route('order.invoice', $trx->invoice_number);

        $message = "*PEMBAYARAN BERHASIL - ABSOLUTE STORE* 🚀\n\n"
          ."Terima kasih! Pembayaran untuk pesanan `{$trx->invoice_number}` telah kami terima dan diproses.\n"
          ."• *Layanan:* {$trx->productItem->product->name}\n"
          ."• *Item:* {$trx->productItem->name}\n"
          ."• *Tujuan:* {$trx->target_account}".($trx->target_zone ? " ({$trx->target_zone})" : '')."\n"
          .'• *Total Lunas:* Rp '.number_format($trx->total_amount, 0, ',', '.')."\n";

        if (! empty($trx->serial_number)) {
            $message .= "• *Kode SN / Token:* `{$trx->serial_number}`\n\n";
        } else {
            $message .= "\n";
        }

        $message .= "Lihat invoice resmi Anda:\n"
          ."🔗 {$invoiceUrl}\n\n"
          .'Terima kasih telah berbelanja di Absolute Store!';

        $this->dispatchMessage($target, $message);
    }

    /**
     * Kirim notifikasi jika proses fulfillment produk gagal ke pembeli
     */
    public function sendOrderFailed(Transaction $trx, string $reason = 'Gangguan sistem operator/provider'): void
    {
        $target = $this->formatPhoneNumber($trx->contact_email_or_phone);
        $invoiceUrl = route('order.invoice', $trx->invoice_number);
        $adminPhone = env('STORE_ADMIN_WHATSAPP', '');

        $message = "❌ *PENGIRIMAN PESANAN GAGAL - ABSOLUTE STORE*\n\n"
          ."Mohon maaf, pesanan dengan No. Invoice `{$trx->invoice_number}` gagal diproses oleh sistem provider.\n"
          ."• *Layanan:* {$trx->productItem->product->name}\n"
          ."• *Item:* {$trx->productItem->name}\n"
          ."• *Target:* {$trx->target_account}".($trx->target_zone ? " ({$trx->target_zone})" : '')."\n"
          ."• *Keterangan:* {$reason}\n\n"
          .'Dana Anda tetap aman. Silakan hubungi Admin WhatsApp ('.($adminPhone ?: 'Customer Service').") dengan melampirkan invoice untuk proses bantuan atau pengembalian saldo.\n\n"
          ."🔗 Cek Status Invoice: {$invoiceUrl}";

        $this->dispatchMessage($target, $message);
    }

    /**
     * Kirim peringatan saldo tipis ke nomor WhatsApp Admin Toko
     */
    public function sendLowBalanceAlert(int $currentBalance, int $threshold): void
    {
        $adminPhone = env('STORE_ADMIN_WHATSAPP', '');
        if (empty($adminPhone)) {
            Log::warning('Low Balance Alert batal dikirim: STORE_ADMIN_WHATSAPP di .env belum disetel.');

            return;
        }

        $target = $this->formatPhoneNumber($adminPhone);
        $waktu = now()->translatedFormat('d F Y, H:i:s').' WIB';

        $message = "⚠️ *PERINGATAN: SALDO DIGIFLAZZ MENIPIS!* ⚠️\n\n"
          ."Halo Admin Absolute Store,\n"
          ."Saldo deposit Digiflazz Anda saat ini telah berada di bawah batas minimum operasional.\n\n"
          .'• *Sisa Saldo:* *Rp '.number_format($currentBalance, 0, ',', '.')."*\n"
          .'• *Batas Minimum:* Rp '.number_format($threshold, 0, ',', '.')."\n"
          ."• *Waktu Pengecekan:* {$waktu}\n\n"
          ."Segera lakukan top up / transfer deposit di panel Digiflazz agar pesanan pelanggan dapat terus diproses secara otomatis tanpa kegagalan.\n\n"
          .'_Sistem Notifikasi Otomatis Absolute Store_';

        $this->dispatchMessage($target, $message);
    }

    /**
     * Kirim peringatan darurat ke Admin jika ada transaksi user yang gagal kirim
     */
    public function sendAdminOrderFailedAlert(Transaction $trx, string $errorMsg): void
    {
        $adminPhone = env('STORE_ADMIN_WHATSAPP', '');
        if (empty($adminPhone)) {
            return;
        }

        $target = $this->formatPhoneNumber($adminPhone);

        $message = "🚨 *ALERT: ORDER GAGAL TERKIRIM (MANUAL ACTION REQUIRED)* 🚨\n\n"
          ."Ada transaksi lunas yang gagal diproses ke provider:\n"
          ."• *Invoice:* `{$trx->invoice_number}`\n"
          ."• *Layanan:* {$trx->productItem->product->name} ({$trx->productItem->name})\n"
          ."• *SKU:* `{$trx->productItem->sku_code}`\n"
          ."• *Akun Tujuan:* {$trx->target_account}\n"
          ."• *Pesan Error:* {$errorMsg}\n\n"
          .'Silakan periksa admin panel atau lakukan pengisian manual untuk pelanggan ini.';

        $this->dispatchMessage($target, $message);
    }

    /**
     * Eksekusi pengiriman payload ke endpoint Fonnte
     */
    protected function dispatchMessage(string $target, string $message): void
    {
        if (empty($this->token)) {
            Log::info("WA Notifier (Dev Mode - FONNTE_TOKEN Kosong):\nKe: {$target}\nPesan:\n{$message}");

            return;
        }

        try {
            $response = Http::timeout(10)
                ->withHeaders([
                    'Authorization' => $this->token,
                ])
                ->post($this->endpoint, [
                    'target' => $target,
                    'message' => $message,
                    'countryCode' => '62',
                ]);

            if ($response->failed()) {
                Log::error('Fonnte API Error: '.$response->body());
            }
        } catch (\Exception $e) {
            Log::error('Gagal mengirim WhatsApp via Fonnte: '.$e->getMessage());
        }
    }
}
