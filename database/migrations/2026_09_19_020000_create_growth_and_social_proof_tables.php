<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tambah referral code pada tabel users
        Schema::table('users', function (Blueprint $table) {
            $table->string('referral_code', 30)->nullable()->unique()->after('tier');
            $table->foreignId('referred_by_id')->nullable()->after('referral_code')->constrained('users')->nullOnDelete();
        });

        // 2. Tabel Reviews & Star Rating
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->tinyInteger('rating')->unsigned(); // 1 - 5
            $table->text('comment')->nullable();
            $table->boolean('is_approved')->default(true);
            $table->timestamps();

            $table->index(['product_id', 'is_approved']);
        });

        // 3. Tabel Komisi Afiliasi (Affiliate Earnings)
        Schema::create('affiliate_earnings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('referrer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('buyer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('transaction_id')->nullable()->constrained('transactions')->nullOnDelete();
            $table->decimal('commission_amount', 12, 2);
            $table->decimal('commission_percent', 5, 2)->default(0.50); // misal 0.5%
            $table->enum('status', ['credited', 'cancelled'])->default('credited');
            $table->timestamps();
        });

        // 4. Tabel Wishlist / Produk Favorit
        Schema::create('wishlists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['user_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wishlists');
        Schema::dropIfExists('affiliate_earnings');
        Schema::dropIfExists('reviews');

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['referred_by_id']);
            $table->dropColumn(['referral_code', 'referred_by_id']);
        });
    }
};
