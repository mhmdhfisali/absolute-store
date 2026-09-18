<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('saved_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('account_name')->nullable(); // Misal: Akun Utama / Akun Smurf
            $table->string('target_account'); // User ID / Nomor Meter / No HP
            $table->string('target_zone')->nullable(); // Zone ID / Server
            $table->string('nickname')->nullable(); // In-game nickname hasil validasi
            $table->timestamps();

            // Mencegah duplikasi akun game yang sama persis untuk satu user
            $table->unique(['user_id', 'product_id', 'target_account', 'target_zone'], 'unique_user_game_account');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saved_accounts');
    }
};
