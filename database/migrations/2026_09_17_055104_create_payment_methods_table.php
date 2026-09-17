<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // QRIS, BCA VA, Dana, GoPay
            $table->string('code')->unique(); // qris, bca_va, dana
            $table->string('channel_category'); // ewallet, va, qris, retail
            $table->decimal('fee_flat', 10, 2)->default(0);
            $table->decimal('fee_percent', 5, 2)->default(0);
            $table->string('logo')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
