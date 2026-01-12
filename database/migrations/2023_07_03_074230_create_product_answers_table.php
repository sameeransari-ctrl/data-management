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
            'product_answers',
            function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->foreign('user_id')
                    ->references('id')
                    ->on('users')
                    ->onDelete('cascade');
                $table->unsignedBigInteger('product_id');
                $table->foreign('product_id')
                    ->references('id')
                    ->on('products')
                    ->onDelete('cascade');
                $table->unsignedBigInteger('product_question_id');
                $table->foreign('product_question_id')
                    ->references('id')
                    ->on('product_questions')
                    ->onDelete('cascade');
                $table->unsignedTinyInteger('question_type')
                    ->comment('1: question for review, 2: question for product');
                $table->string('question_title');
                $table->unsignedTinyInteger('answer_type')
                    ->comment('1: checkbox, 2: radio button, 3: textbox');
                $table->json('answer_options')->nullable()->default(null);
                $table->text('answer')->nullable()->default(null);
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
        Schema::dropIfExists('product_answers');
    }
};
