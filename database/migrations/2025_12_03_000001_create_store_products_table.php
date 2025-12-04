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
        Schema::create('store_products', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(); // e.g., "MTC Recharge 10$"
            $table->string('provider')->nullable(); // e.g., "MTC", "Alfa", "Netflix", "Anghami", "Cablevision"
            $table->string('category')->nullable(); // e.g., "mobile_recharge", "streaming", "tv"
            $table->decimal('price', 10, 2); // Product price
            $table->boolean('is_active')->default(true); // Active/Inactive status
            $table->text('description')->nullable(); // Optional description
            $table->timestamps();
            
            // Indexes for faster queries
            $table->index('provider');
            $table->index('category');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_products');
    }
};
