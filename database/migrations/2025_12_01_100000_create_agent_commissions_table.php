<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Creates agent_commissions table to track earnings and commissions per transfer
     */
    public function up(): void
    {
        Schema::create('agent_commissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agent_id')->constrained('agents')->onDelete('cascade');
            $table->foreignId('transfer_id')->nullable()->constrained('transfers')->onDelete('cascade');
            
            // Commission calculation details
            $table->decimal('commission_amount', 15, 2)->comment('Actual commission earned');
            $table->decimal('commission_rate', 5, 2)->comment('Commission percentage or fixed amount');
            $table->enum('calculation_method', ['percentage', 'fixed'])->default('percentage')->comment('How commission was calculated');
            $table->decimal('transfer_amount', 15, 2)->comment('Original transfer amount for reference');
            
            // Status tracking
            $table->enum('status', ['pending', 'approved', 'paid'])->default('pending');
            $table->timestamp('paid_at')->nullable();
            
            $table->timestamps();
            
            // Indexes for performance
            $table->index('agent_id');
            $table->index('transfer_id');
            $table->index('created_at');
            $table->index('status');
            $table->unique(['agent_id', 'transfer_id']); // Prevent duplicate commissions
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agent_commissions');
    }
};
