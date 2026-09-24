<?php

namespace App\Services;

use App\Models\AffiliateEarning;
use App\Models\Transaction;
use App\Models\User;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AffiliateService
{
    /**
     * Persentase standar komisi afiliasi (0.5%)
     */
    public const COMMISSION_PERCENT = 0.50;

    /**
     * Memproses komisi referral dari transaksi yang telah dibayar (PAID)
     * Menggunakan Double-Entry Bookkeeping dan Lock For Update
     */
    public static function processCommission(Transaction $trx): ?AffiliateEarning
    {
        // Transaksi harus ada user_id dan berstatus paid
        if (! $trx->user_id || $trx->payment_status !== 'paid') {
            return null;
        }

        // Cek apakah pembeli memiliki upline / referrer
        $buyer = User::find($trx->user_id);
        if (! $buyer || ! $buyer->referred_by_id) {
            return null;
        }

        // Idempotency: Cegah duplikasi komisi untuk transaksi yang sama
        $existing = AffiliateEarning::where('transaction_id', $trx->id)->first();
        if ($existing) {
            return $existing;
        }

        $commissionPercent = self::COMMISSION_PERCENT;
        $totalAmount = (float) $trx->total_amount;

        if ($totalAmount <= 0) {
            return null;
        }

        $commissionAmount = round(($totalAmount * $commissionPercent) / 100, 2);
        if ($commissionAmount < 1) {
            $commissionAmount = 1.00;
        }

        return DB::transaction(function () use ($buyer, $trx, $commissionAmount, $commissionPercent) {
            // Lock akun referrer untuk mencegah race condition mutasi saldo
            $referrer = User::where('id', $buyer->referred_by_id)->lockForUpdate()->first();
            if (! $referrer) {
                return null;
            }

            $balanceBefore = (float) $referrer->balance;
            $balanceAfter = $balanceBefore + $commissionAmount;

            // Update saldo referrer
            $referrer->update([
                'balance' => $balanceAfter,
            ]);

            // Catat Double-Entry Ledger ke wallet_transactions
            WalletTransaction::create([
                'user_id' => $referrer->id,
                'reference_id' => 'COMM-'.$trx->invoice_number,
                'type' => 'credit',
                'amount' => $commissionAmount,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'category' => 'referral_commission',
                'description' => "Komisi Referral {$commissionPercent}% dari transaksi {$trx->invoice_number} ({$buyer->name})",
            ]);

            // Catat ke tabel riwayat komisi afiliasi
            $earning = AffiliateEarning::create([
                'referrer_id' => $referrer->id,
                'buyer_id' => $buyer->id,
                'transaction_id' => $trx->id,
                'commission_amount' => $commissionAmount,
                'commission_percent' => $commissionPercent,
                'status' => 'credited',
            ]);

            Log::info("Affiliate Commission: Rp {$commissionAmount} dikreditkan ke User #{$referrer->id} dari transaksi #{$trx->invoice_number}");

            return $earning;
        });
    }
}
