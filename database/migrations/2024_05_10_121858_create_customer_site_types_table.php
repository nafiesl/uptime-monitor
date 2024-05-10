<?php

use App\Models\CustomerSiteType;
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
        Schema::create('customer_site_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->timestamps();
        });

        CustomerSiteType::insert([
            [
                'name' => 'Production',
                'slug' => 'prod',
            ],
            [
                'name' => 'Development',
                'slug' => 'dev',
            ],
            [
                'name' => 'Staging',
                'slug' => 'stg',
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_site_types');
    }
};
