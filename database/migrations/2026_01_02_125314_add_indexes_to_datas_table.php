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
        Schema::table('datas', function (Blueprint $table) {
            $table->index('company_name', 'idx_datas_company_name');
            $table->index('company_website', 'idx_datas_company_website');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('datas', function (Blueprint $table) {
            $table->dropIndex('idx_datas_company_name');
            $table->dropIndex('idx_datas_company_website');
        });
    }
};
