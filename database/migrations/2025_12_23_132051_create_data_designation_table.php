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
            'data_designation',
            function (Blueprint $table) {
                $table->id();

                // Foreign keys
                $table->unsignedBigInteger('data_id');
                $table->unsignedBigInteger('designation_id');

                // Store the exact matched phrase from datas.title
                $table->string('phrase');

                $table->timestamps();

                // Indexes for fast filtering
                $table->index('data_id', 'idx_data_designation_data_id');
                $table->index('designation_id', 'idx_data_designation_designation_id');
                $table->index('phrase', 'idx_data_designation_phrase');

                // Ensure uniqueness per combination of data_id + designation_id + phrase
                $table->unique(['data_id', 'designation_id', 'phrase'], 'uniq_data_designation_phrase');

                // Foreign key constraints
                $table->foreign('data_id')->references('id')->on('datas')->onDelete('cascade');
                $table->foreign('designation_id')->references('id')->on('designations')->onDelete('cascade');
            }
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_designation');
    }
};
