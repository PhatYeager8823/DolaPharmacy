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
        Schema::create('sliders', function (Blueprint $table) {
            $table->id();
            $table->string('tieu_de')->nullable(); // Tiêu đề banner
            $table->string('hinh_anh');            // Đường dẫn ảnh
            $table->string('link')->nullable();    // Bấm vào ảnh thì nhảy đi đâu (VD: link khuyến mãi)
            $table->string('mo_ta')->nullable();   // Dòng chữ nhỏ mô tả
            $table->integer('thu_tu')->default(0); // Sắp xếp hiển thị
            $table->boolean('is_active')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sliders');
    }
};
