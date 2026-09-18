<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promo_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // Contoh: ABSOLUTEHEMAT
            $table->enum('type', ['flat', 'percentage'])->default('flat'); // Potongan Rupiah atau Persen
            $table->decimal('discount_amount', 12, 2); // Nominal Rp 5.000 atau 10 (%)
            $table->decimal('max_discount', 12, 2)->nullable(); // Batas maksimal diskon jika tipe persentase
            $table->decimal('min_transaction', 12, 2)->default(0); // Syarat minimal belanja
            $table->integer('usage_limit')->nullable(); // Kuota kupon (misal: 100x pakai)
            $table->integer('used_count')->default(0); // Berapa kali sudah dipakai
            $table->dateTime('valid_until')->nullable(); // Tanggal kedaluwarsa
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Tambahkan kolom discount_amount & promo_code_id ke tabel transactions
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreignId('promo_code_id')->nullable()->after('payment_method_id')->constrained('promo_codes')->nullOnDelete();
            $table->decimal('discount_amount', 12, 2)->default(0)->after('fee_amount');
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['promo_code_id']);
            $table->dropColumn(['promo_code_id', 'discount_amount']);
        });

        Schema::dropIfExists('promo_codes');
    }
};
