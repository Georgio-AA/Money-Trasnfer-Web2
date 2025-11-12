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
Schema::create('payment_transactions', function (Blueprint $table) {
    $table->id();
    $table->foreignId('transfer_id')->constrained('transfers')->onDelete('cascade');
    $table->enum('payment_method', ['credit_card','debit_card','e_wallet','bank_account']);
    $table->string('payment_reference')->nullable();
    $table->enum('status', ['pending','successful','failed'])->default('pending');
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_transactions');
    }
};
