<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class TestWhatsAppNotification extends Command
{
    /**
     * Nama dan signature command artisan.
     */
    protected $signature = 'wa:test {phone : Nomor WhatsApp tujuan (contoh: 081234567890)}';

    /**
     * Deskripsi command.
     */
    protected $description = 'Uji coba pengiriman pesan WhatsApp via Fonnte Gateway';

    /**
     * Eksekusi command.
     */
    public function handle(): int
    {
        $rawPhone = (string) $this->argument('phone');
        $token = env('FONNTE_TOKEN', '');

        $this->info('=== Uji Koneksi WhatsApp Gateway (Fonnte) ===');

        if (empty($token)) {
            $this->error('❌ GAGAL: Variabel FONNTE_TOKEN di file .env masih kosong!');
            $this->warn('Silakan isi FONNTE_TOKEN di file .env terlebih dahulu.');

            return self::FAILURE;
        }

        // Normalisasi nomor telepon ke format 62xxx
        $target = preg_replace('/[^0-9]/', '', $rawPhone);
        if (str_starts_with($target, '0')) {
            $target = '62'.substr($target, 1);
        }

        $message = "Halo! 🚀\n\nIni adalah pesan uji coba dari *Absolute Store Console*.\n"
            .'Koneksi gateway WhatsApp via Fonnte berhasil terhubung secara optimal pada: '
            .now()->translatedFormat('d F Y, H:i:s')." WIB.\n\n"
            .'_Command: php artisan wa:test_';

        $this->line("Mengirim pesan ke: <comment>{$target}</comment> ...");

        try {
            $response = Http::timeout(15)
                ->withHeaders([
                    'Authorization' => $token,
                ])
                ->post('https://api.fonnte.com/send', [
                    'target' => $target,
                    'message' => $message,
                    'countryCode' => '62',
                ]);

            $result = $response->json();

            if ($response->successful() && isset($result['status']) && $result['status'] === true) {
                $this->newLine();
                $this->info("✅ BERHASIL: Pesan WhatsApp telah dikirim ke {$target}!");
                $this->line('Detail Response: '.json_encode($result, JSON_PRETTY_PRINT));

                return self::SUCCESS;
            }

            $this->newLine();
            $this->error('❌ GAGAL: Fonnte menolak request pengiriman.');
            $this->line('Response: '.($response->body() ?: 'Tidak ada respon dari gateway.'));

            return self::FAILURE;
        } catch (\Exception $e) {
            $this->newLine();
            $this->error('❌ ERROR KONEKSI: '.$e->getMessage());

            return self::FAILURE;
        }
    }
}
