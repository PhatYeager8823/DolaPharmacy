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
        // 1. Bảng Nhà cung cấp
        Schema::create('nha_cung_cap', function (Blueprint $table) {
            $table->id();
            $table->string('ten_nha_cung_cap');
            $table->string('sdt')->nullable();
            $table->string('email')->nullable();
            $table->string('dia_chi')->nullable();
            $table->timestamps();
        });

        // 2. Bảng Phiếu nhập kho (Lưu thông tin chung)
        Schema::create('phieu_nhap', function (Blueprint $table) {
            $table->id();
            $table->string('ma_phieu')->unique(); // VD: PN001
            $table->unsignedBigInteger('nha_cung_cap_id');
            $table->unsignedBigInteger('nguoi_nhap_id'); // Admin nào nhập
            $table->decimal('tong_tien', 15, 0)->default(0);
            $table->text('ghi_chu')->nullable();
            $table->timestamps();
        });

        // 3. Bảng Chi tiết phiếu nhập (Lưu từng loại thuốc trong phiếu)
        Schema::create('chi_tiet_phieu_nhap', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('phieu_nhap_id');
            $table->unsignedBigInteger('thuoc_id');
            $table->integer('so_luong');
            $table->decimal('gia_nhap', 15, 0); // Giá vốn
            $table->decimal('thanh_tien', 15, 0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_tables');
    }
};
