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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('ma_don_hang')->unique();

            // === SỬA DÒNG NÀY: Thêm ->nullable() ===
            // Cho phép null để khách vãng lai (không có tài khoản) vẫn lưu được đơn
            $table->foreignId('nguoi_dung_id')->nullable()->constrained('nguoi_dungs')->nullOnDelete();
            // =======================================

            $table->string('ten_nguoi_nhan');
            $table->string('sdt_nguoi_nhan');
            $table->text('dia_chi_giao_hang');
            $table->decimal('tong_tien', 15, 0);
            $table->string('phuong_thuc_thanh_toan')->default('cod');
            $table->string('trang_thai')->default('cho_xac_nhan');
            $table->text('ghi_chu')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
