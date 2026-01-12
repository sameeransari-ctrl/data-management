<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'users',
            function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('user_type');
                $table->string('email');
                $table->timestamp('email_verified_at')->nullable();
                $table->timestamp('phone_number_verified_at')->nullable();
                $table->string('password')->nullable();
                $table->integer('phone_code');
                $table->string('phone_number', 25);
                $table->string('profile_image')->nullable();
                $table->string('address')->nullable();
                $table->foreignId('country_id')->nullable()->default(null)->constrained('countries');
                $table->foreignId('state_id')->nullable()->default(null)->constrained('states');
                $table->foreignId('city_id')->nullable()->default(null)->constrained('cities');
                $table->string('zip_code', 25)->nullable();
                $table->string('latitude')->nullable();
                $table->string('longitude')->nullable();
                $table->string('timezone')->nullable();
                $table->string('otp', 6)->nullable();
                $table->string('temp_contact')->nullable();
                $table->timestamp('otp_expires_at')->nullable();
                $table->unsignedTinyInteger('is_profile_completed')->default(1)
                    ->comment('1: complete 0: incomplete Default 1');
                $table->unsignedTinyInteger('notification_alert')->default(1)
                    ->comment('1: on 2: off Default 1');
                $table->string('status', 50)->nullable();
                $table->boolean('should_re_login')->default(0);
                $table->timestamp('last_login_date')->nullable();
                $table->timestamp('change_password_at')->nullable();
                $table->rememberToken();
                $table->timestamps();
                $table->softDeletes();
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
