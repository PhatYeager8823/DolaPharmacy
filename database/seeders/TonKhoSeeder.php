<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TonKhoSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('ton_khos')->insert([
            ['id' => 1, 'thuoc_id' => 1, 'so_luong_thay_doi' => 50, 'loai_giao_dich' => 'nhap', 'gia_nhap' => 20000, 'ghi_chu' => 'Nhập lô đầu', 'created_at' => '2025-11-24 07:41:01'],
            ['id' => 2, 'thuoc_id' => 2, 'so_luong_thay_doi' => 50, 'loai_giao_dich' => 'nhap', 'gia_nhap' => 18000, 'ghi_chu' => 'Nhập lô đầu', 'created_at' => '2025-11-24 07:41:01'],
            ['id' => 3, 'thuoc_id' => 3, 'so_luong_thay_doi' => 50, 'loai_giao_dich' => 'nhap', 'gia_nhap' => 15000, 'ghi_chu' => 'Nhập lô đầu', 'created_at' => '2025-11-24 07:41:01'],
            ['id' => 4, 'thuoc_id' => 4, 'so_luong_thay_doi' => 50, 'loai_giao_dich' => 'nhap', 'gia_nhap' => 30000, 'ghi_chu' => 'Nhập lô đầu', 'created_at' => '2025-11-24 07:41:01'],
            ['id' => 5, 'thuoc_id' => 5, 'so_luong_thay_doi' => 50, 'loai_giao_dich' => 'nhap', 'gia_nhap' => 8000, 'ghi_chu' => 'Nhập lô đầu', 'created_at' => '2025-11-24 07:41:01'],
            ['id' => 6, 'thuoc_id' => 6, 'so_luong_thay_doi' => 50, 'loai_giao_dich' => 'nhap', 'gia_nhap' => 50000, 'ghi_chu' => 'Nhập lô đầu', 'created_at' => '2025-11-24 07:41:01'],
            ['id' => 7, 'thuoc_id' => 7, 'so_luong_thay_doi' => 50, 'loai_giao_dich' => 'nhap', 'gia_nhap' => 15000, 'ghi_chu' => 'Nhập lô đầu', 'created_at' => '2025-11-24 07:41:01'],
            ['id' => 8, 'thuoc_id' => 8, 'so_luong_thay_doi' => 50, 'loai_giao_dich' => 'nhap', 'gia_nhap' => 150000, 'ghi_chu' => 'Nhập lô đầu', 'created_at' => '2025-11-24 07:41:01'],
            ['id' => 9, 'thuoc_id' => 9, 'so_luong_thay_doi' => 50, 'loai_giao_dich' => 'nhap', 'gia_nhap' => 50000, 'ghi_chu' => 'Nhập lô đầu', 'created_at' => '2025-11-24 07:41:01'],
            ['id' => 10, 'thuoc_id' => 10, 'so_luong_thay_doi' => 50, 'loai_giao_dich' => 'nhap', 'gia_nhap' => 70000, 'ghi_chu' => 'Nhập lô đầu', 'created_at' => '2025-11-24 07:41:01'],
        ]);
    }
}
