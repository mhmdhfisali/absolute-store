<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Kolom Ban / Suspend pada tabel users
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'is_banned')) {
                $table->boolean('is_banned')->default(false)->after('tier');
            }
            if (! Schema::hasColumn('users', 'ban_reason')) {
                $table->text('ban_reason')->nullable()->after('is_banned');
            }
        });

        // 2. Double-Entry Wallet Ledger Mutasi Saldo
        if (! Schema::hasTable('wallet_transactions')) {
            Schema::create('wallet_transactions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->string('reference_id', 64)->unique();
                $table->enum('type', ['credit', 'debit']); // credit = masuk, debit = keluar
                $table->decimal('amount', 16, 2);
                $table->decimal('balance_before', 16, 2);
                $table->decimal('balance_after', 16, 2);
                $table->string('category', 50)->default('general'); // deposit, adjustment, refund, order
                $table->text('description')->nullable();
                $table->timestamps();

                $table->index(['user_id', 'created_at']);
                $table->index('category');
            });
        }

        // 3. Konfigurasi Sistem & Provider Settings Dinamis
        if (! Schema::hasTable('system_settings')) {
            Schema::create('system_settings', function (Blueprint $table) {
                $table->id();
                $table->string('key', 100)->unique();
                $table->text('value')->nullable();
                $table->string('group', 50)->default('general'); // digiflazz, tripay, midtrans, system
                $table->boolean('is_encrypted')->default(false);
                $table->timestamps();

                $table->index('group');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('system_settings');
        Schema::dropIfExists('wallet_transactions');

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'ban_reason')) {
                $table->dropColumn('ban_reason');
            }
            if (Schema::hasColumn('users', 'is_banned')) {
                $table->dropColumn('is_banned');
            }
        });
    }
};
