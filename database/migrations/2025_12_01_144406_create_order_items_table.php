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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('thuoc_id')->nullable()->constrained('thuocs')->nullOnDelete();

            // Lưu lại tên và giá tại thời điểm mua (Snapshot)
            // Để lỡ sau này thuốc bị xóa hoặc đổi giá thì đơn hàng cũ không bị sai
            $table->string('ten_thuoc');
            $table->integer('so_luong');
            $table->decimal('gia_ban', 15, 0);
            $table->decimal('thanh_tien', 15, 0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
