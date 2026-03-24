<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('nguoi_dungs', function (Blueprint $table) {
            // Cho phép email và mật khẩu null
            $table->string('email')->nullable()->change();
            $table->string('mat_khau')->nullable()->change();

            // SĐT bắt buộc và duy nhất (để làm định danh)
            $table->string('sdt', 20)->unique()->change();
        });
    }

    public function down(): void
    {
        Schema::table('nguoi_dungs', function (Blueprint $table) {
            $table->string('email')->nullable(false)->change();
            // Lưu ý: Việc rollback có thể lỗi nếu dữ liệu hiện tại có null
        });
    }
};
