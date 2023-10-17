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
        Schema::create('monitoring_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_site_id')->constrained();
            $table->string('url');
            $table->integer('response_time')->nullable();
            $table->integer('status_code')->nullable();
            $table->timestamps();

            $table->index(['customer_site_id', 'created_at'], 'logs_customer_site_id_created_at_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monitoring_logs');
    }
};
