<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Add commission_type column to agents table
     */
    public function up(): void
    {
        Schema::table('agents', function (Blueprint $table) {
            // Add commission_type if it doesn't exist
            if (!Schema::hasColumn('agents', 'commission_type')) {
                $table->enum('commission_type', ['percentage', 'fixed'])
                    ->default('percentage')
                    ->after('commission_rate')
                    ->comment('Type of commission calculation for this agent');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agents', function (Blueprint $table) {
            if (Schema::hasColumn('agents', 'commission_type')) {
                $table->dropColumn('commission_type');
            }
        });
    }
};
