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
            $table->dropColumn('payment_method');
        });
        
        Schema::table('payment_transactions', function (Blueprint $table) {
            $table->enum('payment_method', ['credit_card','debit_card','e_wallet','bank_account','balance'])->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_transactions', function (Blueprint $table) {
            $table->dropColumn('payment_method');
        });
        
        Schema::table('payment_transactions', function (Blueprint $table) {
            $table->enum('payment_method', ['credit_card','debit_card','e_wallet','bank_account'])->after('status');
        });
    }
};
