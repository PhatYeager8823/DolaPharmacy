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
        Schema::table('settings', function (Blueprint $table) {
            $table->string('promo_text')->nullable();
            $table->string('promo_code')->nullable();
            $table->dateTime('promo_end_date')->nullable();
            $table->boolean('is_promo_active')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['promo_text', 'promo_code', 'promo_end_date', 'is_promo_active']);
        });
    }
};
