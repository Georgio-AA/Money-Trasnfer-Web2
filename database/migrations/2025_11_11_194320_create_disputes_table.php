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
Schema::create('disputes', function (Blueprint $table) {
    $table->id();
    $table->foreignId('transfer_id')->constrained('transfers')->onDelete('cascade');
    $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
    $table->text('reason');
    $table->enum('status', ['open','under_review','resolved','rejected'])->default('open');
    $table->timestamps();
    $table->timestamp('resolved_at')->nullable();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disputes');
    }
};
