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
        Schema::table('nha_cung_caps', function (Blueprint $table) {
            // Thêm cột trạng thái (mặc định là 1: Hoạt động)
            // after('email') để nó nằm sau cột email cho đẹp
            $table->boolean('trang_thai')->default(1)->after('email');
        });
    }

    public function down()
    {
        Schema::table('nha_cung_caps', function (Blueprint $table) {
            $table->dropColumn('trang_thai');
        });
    }
};
