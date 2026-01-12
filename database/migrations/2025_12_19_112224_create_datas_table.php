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
            'datas',
            function (Blueprint $table) {
                $table->id();
                $table->string('company_name');
                $table->string('company_website');
                $table->string('company_industries');
                $table->string('num_of_employees');
                $table->string('company_size');
                $table->string('company_address')->nullable();
                $table->string('company_revenue_range');
                $table->string('company_linkedin_url');
                $table->string('company_phone_number');
                $table->string('first_name');
                $table->string('last_name');
                // $table->string('email');
                 $table->string('email')->collation('utf8mb4_unicode_ci')->unique('datas_email_unique');
                $table->string('title');
                $table->string('person_linkedin_url');
                $table->string('source_url');
                $table->string('person_location');
                $table->string('status', 50)->default('active');
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
        Schema::dropIfExists('datas');
    }
};
