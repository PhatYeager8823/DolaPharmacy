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
        Schema::create('thong_baos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nguoi_dung_id')->constrained('nguoi_dungs')->cascadeOnDelete();
            $table->string('tieu_de');      // Ví dụ: Đặt hàng thành công
            $table->text('noi_dung');       // Ví dụ: Đơn hàng DH001 đang được xử lý...
            $table->string('loai')->default('don_hang'); // don_hang, khuyen_mai, he_thong
            $table->string('duong_dan')->nullable(); // Link khi bấm vào (VD: /don-hang/DH001)
            $table->tinyInteger('da_xem')->default(0); // 0: Chưa xem, 1: Đã xem
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('thong_baos');
    }
};
