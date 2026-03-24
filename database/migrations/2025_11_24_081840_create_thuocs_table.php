<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('thuocs', function (Blueprint $table) {
            $table->id();
            $table->string('ten_thuoc');
            $table->string('slug')->unique();
            $table->string('ma_san_pham', 50)->nullable();
            $table->string('so_dang_ky', 50)->nullable();
            $table->string('hinh_anh')->nullable();

            $table->integer('gia_ban')->nullable();
            $table->integer('gia_cu')->nullable();
            $table->string('don_vi_tinh', 50)->nullable();
            $table->string('quy_cach', 255)->nullable();
            $table->integer('da_ban')->default(0);

            $table->text('hoat_chat')->nullable();
            $table->string('ham_luong')->nullable();
            $table->string('dang_bao_che')->nullable();
            $table->string('nuoc_san_xuat', 100)->nullable();
            $table->string('nha_san_xuat', 255)->nullable();

            $table->text('mo_ta_ngan')->nullable();
            $table->longText('cong_dung')->nullable();
            $table->longText('cach_dung')->nullable();
            $table->longText('tac_dung_phu')->nullable();
            $table->longText('thanh_phan')->nullable();

            $table->tinyInteger('ke_don')->nullable();
            $table->string('nuoc_xuat_xu', 100)->nullable();
            $table->tinyInteger('thuoc_uu_tien')->nullable();

            $table->foreignId('danh_muc_id')->nullable()->constrained('danh_mucs')->nullOnDelete();
            $table->foreignId('brand_id')->nullable()->constrained('brands')->nullOnDelete();
            $table->foreignId('nha_cung_cap_id')->nullable()->constrained('nha_cung_caps')->nullOnDelete();

            $table->integer('so_luong_ton')->default(0);

            $table->tinyInteger('is_active')->default(1);
            $table->softDeletes(); // <--- THÊM DÒNG NÀY
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('thuocs');
    }
};
