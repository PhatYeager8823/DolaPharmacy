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
        Schema::table('thuocs', function (Blueprint $table) {
            $table->boolean('is_new')->default(0)->after('is_active'); // Sản phẩm mới (Gán thủ công)
            $table->boolean('is_exclusive')->default(0)->after('is_new'); // Ưu đãi độc quyền
            $table->boolean('is_suggested')->default(0)->after('is_exclusive'); // Gợi ý hôm nay
        });
    }

    public function down(): void
    {
        Schema::table('thuocs', function (Blueprint $table) {
            $table->dropColumn(['is_new', 'is_exclusive', 'is_suggested']);
        });
    }
};
