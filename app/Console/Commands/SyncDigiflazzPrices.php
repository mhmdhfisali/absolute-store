<?php

namespace App\Console\Commands;

use App\Services\DigiflazzService;
use Illuminate\Console\Command;

class SyncDigiflazzPrices extends Command
{
    protected $signature = 'digiflazz:sync {--margin-flat=1500 : Margin keuntungan tetap dalam rupiah} {--margin-percent=0 : Persentase keuntungan markup}';

    protected $description = 'Sinkronisasi harga modal, harga jual, dan status ketersediaan SKU Digiflazz secara otomatis';

    public function handle(DigiflazzService $digiflazz): int
    {
        $marginFlat = (int) $this->option('margin-flat');
        $marginPercent = (float) $this->option('margin-percent');

        $this->info('=== Memulai Sinkronisasi Harga & SKU Digiflazz ===');
        $this->line('Formula Margin: +Rp '.number_format($marginFlat, 0, ',', '.').' & +'.$marginPercent.'%');

        $result = $digiflazz->syncPriceList($marginFlat, $marginPercent);

        if ($result['success']) {
            $this->info('✅ '.$result['message']);

            return self::SUCCESS;
        }

        $this->error('❌ '.$result['message']);

        return self::FAILURE;
    }
}
