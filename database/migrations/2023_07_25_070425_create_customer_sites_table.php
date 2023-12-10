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
        Schema::create('customer_sites', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('url');
            $table->unsignedTinyInteger('is_active')->default(1);
            $table->foreignId('owner_id')->constrained('users');
            $table->unsignedTinyInteger('check_periode')->default(1);
            $table->string('priority_code', 10)->default('normal');
            $table->unsignedInteger('warning_threshold')->default(5000);
            $table->unsignedInteger('down_threshold')->default(10000);
            $table->boolean('notify_user')->default(true);
            $table->timestamp('last_check_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_sites');
    }
};
