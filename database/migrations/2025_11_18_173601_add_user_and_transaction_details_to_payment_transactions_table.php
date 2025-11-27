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
        Schema::table('payment_transactions', function (Blueprint $table) {
            $table->foreignId('user_id')->after('transfer_id')->constrained('users')->onDelete('cascade');
            $table->enum('transaction_type', ['debit', 'credit'])->after('user_id');
            $table->decimal('amount', 15, 2)->after('transaction_type');
            $table->string('currency', 10)->default('USD')->after('amount');
            $table->text('description')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_transactions', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'transaction_type', 'amount', 'currency', 'description']);
        });
    }
};
