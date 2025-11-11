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
Schema::create('exchange_rates', function (Blueprint $table) {
    $table->id();
    $table->string('base_currency', 10);
    $table->string('target_currency', 10);
    $table->decimal('rate', 20, 8);
    $table->timestamps(); // use updated_at as last_updated
    $table->unique(['base_currency','target_currency']);
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exchange_rates');
    }
};
