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
            // Drop the old enum and recreate with new values
            $table->dropColumn('status');
        });
        
        Schema::table('payment_transactions', function (Blueprint $table) {
            $table->enum('status', ['pending', 'completed', 'successful', 'failed'])->default('pending')->after('currency');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_transactions', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        
        Schema::table('payment_transactions', function (Blueprint $table) {
            $table->enum('status', ['pending','successful','failed'])->default('pending')->after('currency');
        });
    }
};
