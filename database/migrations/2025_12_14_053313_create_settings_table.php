<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            // 1. Thông tin chung
            $table->string('ten_website')->nullable();
            $table->string('logo')->nullable();
            $table->string('favicon')->nullable();

            // 2. Liên hệ
            $table->string('hotline')->nullable();
            $table->string('email')->nullable();
            $table->string('dia_chi')->nullable();
            $table->text('maps')->nullable(); // Mã nhúng bản đồ iframe

            // 3. Mạng xã hội
            $table->string('facebook')->nullable();
            $table->string('zalo')->nullable();
            $table->string('youtube')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
