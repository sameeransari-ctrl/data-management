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
        Schema::table('product_scanners', function (Blueprint $table) {
            $table->string('lat')->nullable();
            $table->string('long')->nullable();
            $table->string('formatted_address')->nullable();
            $table->unsignedBigInteger('country_id')->nullable();
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
        });
        

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_scanners', function (Blueprint $table) {
            Schema::drop('lat');
            Schema::drop('long');
            Schema::drop('formatted_address');
            Schema::drop('country_id');
        });
    }
};
