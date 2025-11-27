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
Schema::create('transfers', function (Blueprint $table) {
    $table->id();
    $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
    $table->foreignId('beneficiary_id')->constrained('beneficiaries')->onDelete('cascade');
    $table->foreignId('agent_id')->nullable()->constrained('users')->nullOnDelete();
    $table->string('source_currency', 10);
    $table->string('target_currency', 10);
    $table->decimal('amount', 15, 2);
    $table->decimal('exchange_rate', 20, 8);
    $table->decimal('transfer_fee', 12, 2);
    $table->decimal('total_paid', 15, 2);
    $table->decimal('payout_amount', 15, 2);
    $table->enum('transfer_speed', ['instant','same_day','standard'])->default('standard');
    $table->enum('status', ['pending','processing','completed','failed','refunded'])->default('pending');
    $table->timestamps();
    $table->timestamp('completed_at')->nullable();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfers');
    }
};
