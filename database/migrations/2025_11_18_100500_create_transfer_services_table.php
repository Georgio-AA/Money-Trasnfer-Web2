<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transfer_services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->json('destination_countries'); // ISO codes e.g. ["US","GB","FR"]
            $table->decimal('fee_fixed', 10, 2)->default(0);
            $table->decimal('fee_percent', 5, 2)->default(0); // percent of send amount
            $table->integer('fx_margin_bps')->default(0); // basis points over mid-rate
            $table->string('transfer_speed'); // instant|same_day|next_day|standard
            $table->json('payout_methods'); // ["bank_deposit","cash_pickup","mobile_wallet"]
            $table->boolean('has_promotions')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transfer_services');
    }
};
