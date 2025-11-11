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
Schema::create('beneficiaries', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade'); // owner of beneficiary
    $table->string('full_name');
    $table->string('country', 100);
    $table->foreignId('bank_account_id')->nullable()->constrained('bank_accounts')->nullOnDelete();
    $table->enum('preferred_payout_method', ['bank_deposit','cash_pickup','mobile_wallet'])->default('bank_deposit');
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('beneficiaries');
    }
};
