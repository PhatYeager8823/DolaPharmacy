<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hoa_dons', function (Blueprint $table) {
            $table->id();
            $table->string('ma_don_hang', 50)->nullable();
            $table->foreignId('nguoi_dung_id')->nullable()
                ->constrained('nguoi_dungs')
                ->nullOnDelete();
            $table->string('ten_nguoi_nhan')->nullable();
            $table->string('sdt_nguoi_nhan', 20)->nullable();
            $table->text('dia_chi_giao')->nullable();
            $table->integer('tong_tien')->nullable();
            $table->string('trang_thai', 50)->default('cho_xu_ly');
            $table->text('ghi_chu')->nullable();
            $table->string('phuong_thuc_thanh_toan', 50)->nullable();
            $table->integer('phi_ship')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hoa_dons');
    }
};
