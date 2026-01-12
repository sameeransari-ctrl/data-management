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
            'field_safety_notices',
            function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->text('notice_description')->nullable()->default(null);
                $table->string('attachment_type', 25)->nullable()->default(null);
                $table->text('upload_file')->nullable()->default(null);
                $table->unsignedTinyInteger('status')->default(1)
                    ->comment('1: active 0: inactive Default 1');
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
        Schema::dropIfExists('field_safety_notices');
    }
};
