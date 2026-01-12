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
        Schema::table('product_scanners', function (Blueprint $table) {
            $table->string('city')->nullable();
        });
    }

    /**
     * Method down
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('product_scanners', function (Blueprint $table) {
            Schema::drop('city');
        });
    }
};
