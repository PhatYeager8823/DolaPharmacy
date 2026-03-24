<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. Bảng Phiếu Nhập (Lưu thông tin chung: ai nhập, nhập của ai, ngày nào)
        Schema::create('phieu_nhaps', function (Blueprint $table) {
            $table->id();
            $table->string('ma_phieu')->unique(); // Ví dụ: PN25122023-01

            // Khóa ngoại liên kết với bảng nha_cung_caps CÓ SẴN của bạn
            $table->unsignedBigInteger('nha_cung_cap_id');

            $table->unsignedBigInteger('nguoi_nhap_id'); // Liên kết với bảng users
            $table->decimal('tong_tien', 15, 0)->default(0);
            $table->text('ghi_chu')->nullable();
            $table->timestamps();
        });

        // 2. Bảng Chi Tiết Phiếu Nhập (Lưu từng món thuốc trong phiếu)
        Schema::create('chi_tiet_phieu_nhaps', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('phieu_nhap_id');
            $table->unsignedBigInteger('thuoc_id'); // Liên kết với bảng thuocs
            $table->integer('so_luong');
            $table->decimal('gia_nhap', 15, 0);
            $table->decimal('thanh_tien', 15, 0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('chi_tiet_phieu_nhaps');
        Schema::dropIfExists('phieu_nhaps');
    }
};
