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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id(); // Quan trọng: Mặc định là BIGINT UNSIGNED
            $table->string('code')->unique(); // Mã giảm giá
            $table->enum('type', ['fixed', 'percent']); // Loại: Tiền mặt hoặc %
            $table->decimal('value', 10, 2); // Giá trị
            $table->dateTime('expiry_date')->nullable(); // Hạn sử dụng
            $table->boolean('is_active')->default(true); // Trạng thái
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
