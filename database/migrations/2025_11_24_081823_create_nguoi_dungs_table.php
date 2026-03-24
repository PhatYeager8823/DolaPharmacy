<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nguoi_dungs', function (Blueprint $table) {
            $table->id();
            $table->string('ten');
            $table->string('email')->unique();
            $table->string('mat_khau');
            $table->string('sdt', 20)->nullable();

            // === THÊM 2 DÒNG NÀY VÀO ĐÂY ===
            $table->date('ngay_sinh')->nullable();      // Thêm ngày sinh
            $table->tinyInteger('gioi_tinh')->default(0); // Thêm giới tính (0: Khác, 1: Nam, 2: Nữ)
            // ===============================

            $table->text('dia_chi')->nullable();
            $table->string('vai_tro', 50)->default('user');
            $table->tinyInteger('trang_thai')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nguoi_dungs');
    }
};
