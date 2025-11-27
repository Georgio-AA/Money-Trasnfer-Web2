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
Schema::create('agents', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
    $table->string('store_name')->nullable();
    $table->string('address')->nullable();
    $table->string('country', 100)->nullable();
    $table->decimal('latitude', 10, 6)->nullable();
    $table->decimal('longitude', 10, 6)->nullable();
    $table->string('working_hours')->nullable();
    $table->decimal('commission_rate', 5, 2)->default(0);
    $table->boolean('approved')->default(false);
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agents');
    }
};
