<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gio_hang_chi_tiets', function (Blueprint $table) {
            $table->id();

            // 1. Liên kết với Giỏ hàng (Cái xe nào?)
            $table->foreignId('gio_hang_id')
                  ->constrained('gio_hangs')
                  ->cascadeOnDelete();

            // 2. Liên kết với Thuốc (Mua món gì?)
            $table->foreignId('thuoc_id')
                  ->constrained('thuocs')
                  ->cascadeOnDelete();

            // 3. Số lượng mua
            $table->integer('so_luong')->default(1);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gio_hang_chi_tiets');
    }
};
