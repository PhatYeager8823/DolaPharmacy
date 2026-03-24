<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('nguoi_dungs', function (Blueprint $table) {
            // 1 = Là khách vãng lai (tự tạo), 0 = Đăng ký chính thức
            $table->boolean('is_guest')->default(0)->after('trang_thai');
        });
    }

    public function down(): void
    {
        Schema::table('nguoi_dungs', function (Blueprint $table) {
            $table->dropColumn('is_guest');
        });
    }
};
