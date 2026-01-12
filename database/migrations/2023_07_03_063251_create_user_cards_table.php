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
        Schema::create(
            'user_cards', 
            function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->foreign('user_id')
                    ->references('id')
                    ->on('users')
                    ->onDelete('cascade');
                $table->string('card_id')->nullable();
                $table->string('card_type')->nullable();
                $table->string('card_number');
                $table->string('card_holder_name');
                $table->string('exp_month', 25)->nullable();
                $table->string('exp_year', 25)->nullable();
                $table->string('ifsc_code', 100)->nullable();
                $table->string('iban_number')->nullable();
                $table->string('srn_number')->nullable();
                $table->string('gtin_number')->nullable();
                $table->string('paypal_id')->nullable();
                $table->unsignedTinyInteger('is_default')->default(0)
                    ->comment('1: selected 0: unselected Default 0');
                $table->timestamps();
                $table->softDeletes();
            }
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_cards');
    }
};
