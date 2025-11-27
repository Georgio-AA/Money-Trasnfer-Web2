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
        Schema::table('bank_accounts', function (Blueprint $table) {
            // Only add columns that don't exist yet
            if (!Schema::hasColumn('bank_accounts', 'verification_document')) {
                $table->string('verification_document')->nullable()->after('is_verified');
            }
            if (!Schema::hasColumn('bank_accounts', 'micro_amount_1')) {
                $table->decimal('micro_amount_1', 4, 2)->nullable()->after('is_verified');
            }
            if (!Schema::hasColumn('bank_accounts', 'micro_amount_2')) {
                $table->decimal('micro_amount_2', 4, 2)->nullable()->after('is_verified');
            }
            if (!Schema::hasColumn('bank_accounts', 'micro_transfer_sent_at')) {
                $table->timestamp('micro_transfer_sent_at')->nullable()->after('is_verified');
            }
            if (!Schema::hasColumn('bank_accounts', 'verification_expires_at')) {
                $table->timestamp('verification_expires_at')->nullable()->after('is_verified');
            }
            if (!Schema::hasColumn('bank_accounts', 'verification_attempts')) {
                $table->integer('verification_attempts')->default(0)->after('is_verified');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bank_accounts', function (Blueprint $table) {
            $table->dropColumn([
                'verification_document',
                'micro_amount_1',
                'micro_amount_2',
                'micro_transfer_sent_at',
                'verification_expires_at',
                'verification_attempts'
            ]);
        });
    }
};
