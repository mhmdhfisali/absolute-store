<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tambah saldo dan tier membership pada users
        Schema::table('users', function (Blueprint $table) {
            $table->decimal('balance', 14, 2)->default(0)->after('role');
            $table->enum('tier', ['member', 'reseller', 'vip'])->default('member')->after('balance');
        });

        // 2. Tambah harga reseller pada item SKU
        Schema::table('product_items', function (Blueprint $table) {
            $table->decimal('reseller_price', 12, 2)->nullable()->after('selling_price');
        });

        // 3. Tambah referensi gateway pada transaksi
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('gateway_reference')->nullable()->after('invoice_number');
            $table->string('checkout_source')->default('direct')->after('delivery_status'); // 'direct' atau 'balance'
        });

        // 4. Tabel Deposit Saldo Member
        Schema::create('deposits', function (Blueprint $table) {
            $table->id();
            $table->string('deposit_number')->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('payment_method_id')->constrained();
            $table->decimal('amount', 12, 2);
            $table->decimal('fee_amount', 12, 2)->default(0);
            $table->decimal('total_amount', 12, 2);
            $table->enum('status', ['unpaid', 'paid', 'expired', 'failed'])->default('unpaid');
            $table->string('gateway_reference')->nullable();
            $table->timestamps();
        });

        // 5. Tabel Audit Activity Logs
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('action'); // misal: 'RETRY_ORDER', 'CHANGE_USER_ROLE', 'UPDATE_PRICE'
            $table->string('description');
            $table->ipAddress('ip_address')->nullable();
            $table->json('payload')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('deposits');

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['gateway_reference', 'checkout_source']);
        });

        Schema::table('product_items', function (Blueprint $table) {
            $table->dropColumn('reseller_price');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['balance', 'tier']);
        });
    }
};
