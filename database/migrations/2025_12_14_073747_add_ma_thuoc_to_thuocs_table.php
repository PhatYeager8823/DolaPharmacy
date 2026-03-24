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
            // Thêm cột ma_thuoc, cho phép null (để không lỗi dữ liệu cũ)
            // Nằm sau cột id cho dễ nhìn
            $table->string('ma_thuoc')->nullable()->after('id');
        });
    }

    public function down()
    {
        Schema::table('thuocs', function (Blueprint $table) {
            $table->dropColumn('ma_thuoc');
        });
    }
};
