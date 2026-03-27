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
        Schema::table('gio_hang_chi_tiets', function (Blueprint $table) {
            $table->tinyInteger('trang_thai')->default(0)->after('so_luong')->comment('0: Active, 1: Ordered');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gio_hang_chi_tiets', function (Blueprint $table) {
            $table->dropColumn('trang_thai');
        });
    }
};
