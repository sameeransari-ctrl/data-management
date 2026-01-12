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
            'products', 
            function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('client_id')->nullable();
                $table->foreign('client_id')
                    ->references('id')
                    ->on('users')
                    ->onDelete('cascade');
                $table->unsignedBigInteger('basic_udid_id')->nullable();
                $table->foreign('basic_udid_id')
                    ->references('id')
                    ->on('basic_udids')
                    ->onDelete('cascade');
                $table->string('client_name')->nullable()
                    ->comment('Client name will come only if client id is null');
                $table->string('product_name')->nullable();
                $table->text('product_description')->nullable();
                $table->string('udi_number');
                $table->string('plain_udi_number')->nullable();
                $table->unsignedBigInteger('class_id');
                $table->foreign('class_id')
                    ->references('id')
                    ->on('product_classes')
                    ->onDelete('cascade');
                $table->text('image_url')->nullable();
                $table->unsignedTinyInteger('verification_by')->default(1)
                    ->comment('1: UDI.eu Verified, 2: Eudamed Verified Default 1');
                $table->unsignedTinyInteger('is_import')->default(0);
                $table->unsignedTinyInteger('status')->default(1)
                    ->comment('1: active 0: inactive Default 1');
                $table->unsignedBigInteger('added_by')->nullable();
                $table->foreign('added_by')
                    ->references('id')
                    ->on('users')
                    ->onDelete('cascade');
                $table->unsignedBigInteger('updated_by')->nullable();
                $table->foreign('updated_by')
                    ->references('id')
                    ->on('users')
                    ->onDelete('cascade');
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
        Schema::dropIfExists('products');
    }
};
