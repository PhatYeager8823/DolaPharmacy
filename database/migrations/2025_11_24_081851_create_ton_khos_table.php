<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ton_khos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('thuoc_id')->constrained('thuocs')->cascadeOnDelete();
            $table->integer('so_luong_thay_doi');
            $table->enum('loai_giao_dich', ['nhap', 'xuat']);
            $table->integer('gia_nhap')->nullable();
            $table->text('ghi_chu')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ton_khos');
    }
};
