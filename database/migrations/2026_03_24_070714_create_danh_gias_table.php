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
        Schema::create('danh_gias', function (Blueprint $table) {
            $table->id();
            // Khóa ngoại đến bảng thuocs
            $table->unsignedBigInteger('thuoc_id');
            // Khóa ngoại đến bảng nguoi_dungs (người đánh giá)
            $table->unsignedBigInteger('nguoi_dung_id');
            
            // Xếp hạng (1 - 5 sao)
            $table->tinyInteger('so_sao')->default(5);
            // Nội dung review
            $table->text('noi_dung')->nullable();
            
            // Trạng thái = 1 (Hiển thị ngay), = 0 (Chờ duyệt ẩn đi)
            $table->boolean('trang_thai')->default(1);
            
            $table->timestamps();

            // Khóa ngoại
            $table->foreign('thuoc_id')->references('id')->on('thuocs')->onDelete('cascade');
            $table->foreign('nguoi_dung_id')->references('id')->on('nguoi_dungs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('danh_gias');
    }
};
