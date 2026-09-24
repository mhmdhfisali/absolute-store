<?php

namespace App\Console\Commands;

use App\Services\WhatsAppService;
use Illuminate\Console\Command;

class TestLowBalanceAlert extends Command
{
    protected $signature = 'wa:test-low-balance {balance=45000 : Simulasi nominal saldo saat ini}';

    protected $description = 'Simulasi pengiriman WhatsApp peringatan saldo Digiflazz menipis ke STORE_ADMIN_WHATSAPP';

    public function handle(WhatsAppService $waService): int
    {
        $simulatedBalance = (int) $this->argument('balance');
        $adminPhone = env('STORE_ADMIN_WHATSAPP', '');

        $this->info('=== Simulasi WhatsApp Alert: Saldo Digiflazz Rendah ===');
        $this->line("Target Admin: <comment>{$adminPhone}</comment>");
        $this->line('Simulasi Saldo: <comment>Rp '.number_format($simulatedBalance, 0, ',', '.').'</comment>');

        $waService->sendLowBalanceAlert($simulatedBalance, 100000);

        $this->info('✅ Pesan alert darurat telah dikirim ke WhatsApp Admin.');

        return self::SUCCESS;
    }
}
