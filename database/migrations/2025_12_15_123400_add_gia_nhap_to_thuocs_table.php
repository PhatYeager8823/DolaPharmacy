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
        Schema::table('thuocs', function (Blueprint $table) {
            // Thêm cột giá nhập, nằm sau cột giá bán
            $table->decimal('gia_nhap', 15, 0)->default(0)->after('gia_ban');
        });
    }

    public function down()
    {
        Schema::table('thuocs', function (Blueprint $table) {
            $table->dropColumn('gia_nhap');
        });
    }
};
