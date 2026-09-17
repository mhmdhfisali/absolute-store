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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->string('name'); // Mobile Legends, Free Fire, PLN Prabayar
            $table->string('slug')->unique();
            $table->string('thumbnail')->nullable();
            $table->enum('input_type', ['id_only', 'id_and_zone', 'phone_number', 'meter_number'])->default('id_only');
            $table->string('provider_code')->nullable(); // Kode Aggregator API (Digiflazz/VIP)
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
