<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KhuyenMaiSeeder extends Seeder
{
    public function run(): void
    {
        // Lấy ngẫu nhiên 10 sản phẩm để làm "Sản phẩm khuyến mãi"
        $products = DB::table('thuocs')
                      ->inRandomOrder()
                      ->take(10)
                      ->get();

        foreach ($products as $product) {
            // Giả sử giá cũ cao hơn giá bán khoảng 20% - 50%
            $giaBan = $product->gia_ban;
            $giaCu  = $giaBan * rand(120, 150) / 100;

            // Làm tròn giá cũ cho đẹp (VD: 125400 -> 125000)
            $giaCu = round($giaCu / 1000) * 1000;

            DB::table('thuocs')
              ->where('id', $product->id)
              ->update(['gia_cu' => $giaCu]);
        }

        $this->command->info('Đã tạo giả 10 sản phẩm khuyến mãi thành công!');
    }
}
