<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('monitoring_logs', 'response_message')) {
            Schema::table('monitoring_logs', function (Blueprint $table) {
                $table->text('response_message')->nullable()->after('status_code');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('monitoring_logs', 'response_message')) {
            Schema::table('monitoring_logs', function (Blueprint $table) {
                $table->dropColumn('response_message');
            });
        }
    }
};
