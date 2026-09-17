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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique(); // INV/20260917/AS/XXXXX
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('product_item_id')->constrained()->cascadeOnDelete();
            $table->foreignId('payment_method_id')->constrained()->cascadeOnDelete();
            $table->string('target_account'); // User ID game atau nomor HP
            $table->string('target_zone')->nullable(); // Zone ID (khusus game tertentu)
            $table->string('contact_email_or_phone');
            $table->decimal('amount', 12, 2);
            $table->decimal('fee_amount', 10, 2)->default(0);
            $table->decimal('total_amount', 12, 2);
            $table->enum('payment_status', ['unpaid', 'paid', 'expired', 'failed'])->default('unpaid');
            $table->enum('delivery_status', ['pending', 'processing', 'success', 'failed'])->default('pending');
            $table->string('serial_number')->nullable(); // SN pengisian / Token listrik 20 digit
            $table->text('provider_response')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
