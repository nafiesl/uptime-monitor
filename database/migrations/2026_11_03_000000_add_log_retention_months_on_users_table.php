<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('users', 'log_retention_months')) {
            Schema::table('users', function (Blueprint $table) {
                $table->unsignedTinyInteger('log_retention_months')->default(3)->after('telegram_chat_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('users', 'log_retention_months')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('log_retention_months');
            });
        }
    }
};
