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
        Schema::table('transfers', function (Blueprint $table) {
            $table->dropColumn('transfer_speed');
        });
        
        Schema::table('transfers', function (Blueprint $table) {
            $table->enum('transfer_speed', ['instant','same_day','next_day','standard'])->default('standard')->after('payout_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transfers', function (Blueprint $table) {
            $table->dropColumn('transfer_speed');
        });
        
        Schema::table('transfers', function (Blueprint $table) {
            $table->enum('transfer_speed', ['instant','same_day','standard'])->default('standard')->after('payout_amount');
        });
    }
};
