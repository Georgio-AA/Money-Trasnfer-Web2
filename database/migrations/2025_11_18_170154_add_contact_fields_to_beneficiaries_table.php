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
        Schema::table('beneficiaries', function (Blueprint $table) {
            $table->string('phone_number')->after('full_name');
            $table->string('email')->nullable()->after('phone_number');
            $table->string('relationship')->nullable()->after('email'); // friend, family, business, etc.
            $table->string('address')->nullable()->after('country');
            $table->string('city')->nullable()->after('address');
            $table->string('postal_code', 20)->nullable()->after('city');
            $table->string('mobile_wallet_number')->nullable()->after('bank_account_id');
            $table->string('mobile_wallet_provider')->nullable()->after('mobile_wallet_number'); // e.g., M-Pesa, GCash, etc.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('beneficiaries', function (Blueprint $table) {
            $table->dropColumn([
                'phone_number',
                'email',
                'relationship',
                'address',
                'city',
                'postal_code',
                'mobile_wallet_number',
                'mobile_wallet_provider'
            ]);
        });
    }
};
