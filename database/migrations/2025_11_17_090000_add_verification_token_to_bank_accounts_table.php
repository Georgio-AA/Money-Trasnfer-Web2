<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bank_accounts', function (Blueprint $table) {
            if (!Schema::hasColumn('bank_accounts', 'verification_token')) {
                $table->string('verification_token', 128)->nullable()->after('verification_document');
            }
            if (!Schema::hasColumn('bank_accounts', 'verification_sent_at')) {
                $table->timestamp('verification_sent_at')->nullable()->after('verification_token');
            }
        });
    }

    public function down(): void
    {
        Schema::table('bank_accounts', function (Blueprint $table) {
            if (Schema::hasColumn('bank_accounts', 'verification_token')) {
                $table->dropColumn('verification_token');
            }
            if (Schema::hasColumn('bank_accounts', 'verification_sent_at')) {
                $table->dropColumn('verification_sent_at');
            }
        });
    }
};
