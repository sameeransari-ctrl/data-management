<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Method up
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('field_safety_notices', function (Blueprint $table) {
            $table->unsignedBigInteger('client_id');
            $table->foreign('client_id')->references('id')->on('users')->onDelete('cascade')->after('id');
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade')->after('id');
            $table->string('thumbnail')->nullable()->default(null);
        });
    }

    /**
     * Method down
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('field_safety_notices', function (Blueprint $table) {
            Schema::drop('client_id');
            Schema::drop('product_id');
        });
    }
};
