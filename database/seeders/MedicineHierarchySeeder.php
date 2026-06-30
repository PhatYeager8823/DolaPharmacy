<?php

namespace Database\Seeders;

use App\Models\DanhMuc;
use App\Models\Thuoc;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MedicineHierarchySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Tạo danh mục gốc "Thuốc"
        $root = DanhMuc::firstOrCreate(
            ['slug' => 'thuoc'],
            ['ten_danh_muc' => 'Thuốc']
        );

        // 2. Tạo 2 danh mục cấp 1: Kê đơn và Không kê đơn
        $etc = DanhMuc::firstOrCreate(
            ['slug' => 'thuoc-ke-don'],
            [
                'ten_danh_muc' => 'Thuốc kê đơn',
                'danh_muc_cha_id' => $root->id
            ]
        );

        $otc = DanhMuc::firstOrCreate(
            ['slug' => 'thuoc-khong-ke-don'],
            [
                'ten_danh_muc' => 'Thuốc không kê đơn',
                'danh_muc_cha_id' => $root->id
            ]
        );

        // 3. Lấy danh sách các danh mục thuốc hiện tại (trừ các danh mục mới tạo)
        $currentCategories = DanhMuc::whereNotIn('id', [$root->id, $etc->id, $otc->id])
            ->where(function($q) use ($root) {
                $q->whereNull('danh_muc_cha_id') 
                  ->orWhere('danh_muc_cha_id', $root->id);
            })
            ->get();

        foreach ($currentCategories as $cat) {
            // Nhảy qua nếu slug bắt đầu bằng 'old-'
            if (str_starts_with($cat->slug, 'old-')) continue;

            // Tạo bản sao cho nhánh Kê đơn
            $etcSub = DanhMuc::firstOrCreate(
                ['slug' => $cat->slug . '-ke-don'],
                [
                    'ten_danh_muc' => $cat->ten_danh_muc,
                    'danh_muc_cha_id' => $etc->id,
                    'hinh_anh' => $cat->hinh_anh
                ]
            );

            // Tạo bản sao cho nhánh Không kê đơn
            $otcSub = DanhMuc::firstOrCreate(
                ['slug' => $cat->slug . '-khong-ke-don'],
                [
                    'ten_danh_muc' => $cat->ten_danh_muc,
                    'danh_muc_cha_id' => $otc->id,
                    'hinh_anh' => $cat->hinh_anh
                ]
            );

            // 4. Di chuyển thuốc của danh mục này vào đúng nhánh mới
            $products = Thuoc::where('danh_muc_id', $cat->id)->get();
            foreach ($products as $product) {
                if ($product->ke_don == 1) {
                    $product->update(['danh_muc_id' => $etcSub->id]);
                } else {
                    $product->update(['danh_muc_id' => $otcSub->id]);
                }
            }
            
            // Ẩn danh mục cũ
            $cat->update(['slug' => 'old-' . $cat->slug]);
        }
    }
}
