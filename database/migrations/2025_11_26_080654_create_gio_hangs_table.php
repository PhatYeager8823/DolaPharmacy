<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gio_hangs', function (Blueprint $table) {
            $table->id();

            // Khóa ngoại liên kết với bảng nguoi_dungs
            // cascadeOnDelete: Nếu xóa User thì xóa luôn Giỏ hàng của họ
            $table->foreignId('nguoi_dung_id')
                  ->unique() // Đảm bảo 1 người chỉ có 1 giỏ hàng active
                  ->constrained('nguoi_dungs')
                  ->cascadeOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gio_hangs');
    }
};
