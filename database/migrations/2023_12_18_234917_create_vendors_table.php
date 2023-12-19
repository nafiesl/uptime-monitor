<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorsTable extends Migration
{
    public function up()
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 60);
            $table->string('description')->nullable();
            $table->foreignId('creator_id')->constrained('users')->onDelete('restrict');
            $table->timestamps();
        });

        if (!Schema::hasColumn('customer_sites', 'vendor_id')) {
            Schema::table('customer_sites', function (Blueprint $table) {
                $table->unsignedBigInteger('vendor_id')->nullable()->after('owner_id');

                $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('restrict');
            });
        } else {
            Schema::table('customer_sites', function (Blueprint $table) {
                $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('restrict');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('vendors');
    }
}
