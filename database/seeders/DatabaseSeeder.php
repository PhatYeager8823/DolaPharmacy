<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NguoiDung; // Nhớ import Model
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. TẠO TÀI KHOẢN ADMIN (QUAN TRỌNG)
        // Kiểm tra nếu chưa có thì mới tạo để tránh lỗi trùng email
        if (!NguoiDung::where('email', 'admin@gmail.com')->exists()) {
            NguoiDung::create([
                'ten'        => 'Quản Trị Viên',
                'email'      => 'admin@gmail.com',
                'mat_khau'   => Hash::make('123456'), // Mật khẩu: 123456
                'sdt'        => '0909000111',
                'dia_chi'    => 'Văn phòng Dola Pharmacy',
                'vai_tro'    => 'admin', // <--- QUAN TRỌNG NHẤT: Cấp quyền Admin
                'trang_thai' => 1,
            ]);
        }

        $this->call([
            BrandSeeder::class,
            DanhMucSeeder::class,
            NhaCungCapSeeder::class,
            ThuocSeeder::class,
            TonKhoSeeder::class,
            BaiVietSeeder::class,
            VideoSeeder::class,
            FaqSeeder::class,
        ]);
    }
}
