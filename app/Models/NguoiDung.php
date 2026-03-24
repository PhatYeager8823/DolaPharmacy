<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Coupon;
use App\Models\Order; // <--- [MỚI] Thêm dòng này để gọi Model Đơn hàng

class NguoiDung extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'nguoi_dungs';

    protected $fillable = [
        'ten',
        'email',
        'mat_khau',
        'sdt',
        'dia_chi',
        'vai_tro',      // 'user', 'admin'
        'trang_thai',   // 1: Active, 0: Block

        // === CÁC CỘT MỚI THÊM ===
        'avatar',       // Ảnh đại diện
        'ngay_sinh',
        'gioi_tinh',    // 0: Khác, 1: Nam, 2: Nữ
        'is_guest',     // 1: Khách vãng lai (tự tạo), 0: Đăng ký chuẩn
        'ma_xac_thuc',  // OTP
    ];

    // Ẩn mật khẩu khi trả về JSON (bảo mật)
    protected $hidden = [
        'mat_khau',
        'remember_token',
    ];

    // Chỉ định tên cột mật khẩu cho Laravel Auth biết
    public function getAuthPassword()
    {
        return $this->mat_khau;
    }

    // 1. Quan hệ với Mã giảm giá (Code cũ của bạn)
    public function coupons()
    {
        return $this->belongsToMany(Coupon::class, 'coupon_user', 'user_id', 'coupon_id')
                    ->wherePivot('used_at', null)
                    ->withPivot('id');
    }

    // ========================================================
    //         CÁC HÀM MỚI CHO TÍNH NĂNG THÀNH VIÊN
    // ========================================================

    // 2. [MỚI] Quan hệ: Một người dùng có nhiều Đơn hàng
    public function orders()
    {
        // 'nguoi_dung_id' là khóa ngoại trong bảng orders
        return $this->hasMany(Order::class, 'nguoi_dung_id');
    }

    // Quan hệ lịch sử đánh giá
    public function danhGias()
    {
        return $this->hasMany(DanhGia::class, 'nguoi_dung_id');
    }

    // 3. [MỚI] Hàm tính toán Hạng & Tiến độ
    public function getMembershipData()
    {
        // A. Tính tổng tiền các đơn ĐÃ GIAO THÀNH CÔNG
        $totalSpent = $this->orders()
                           ->where('trang_thai', 'da_giao')
                           ->sum('tong_tien');

        // B. Cấu hình các mốc hạng (Mặc định là Bạc)
        $rank = 'Thành viên BẠC';
        $nextRank = 'VÀNG';
        $target = 5000000;       // Mốc lên Vàng (5 triệu)
        $color = 'bg-secondary'; // Màu xám

        // C. Logic xét hạng
        if ($totalSpent >= 10000000) { // Trên 10 triệu
            $rank = 'Thành viên KIM CƯƠNG';
            $nextRank = 'MAX'; // Đã max cấp
            $target = 10000000;
            $color = 'bg-info'; // Màu xanh dương (hoặc tím than)
        } elseif ($totalSpent >= 5000000) { // Trên 5 triệu
            $rank = 'Thành viên VÀNG';
            $nextRank = 'KIM CƯƠNG';
            $target = 10000000; // Mốc lên Kim Cương
            $color = 'bg-warning'; // Màu vàng
        }

        // D. Tính phần trăm thanh tiến độ (Progress Bar)
        if ($nextRank == 'MAX') {
            $percent = 100;
            $remaining = 0;
        } else {
            // Ví dụ: Đã tiêu 2tr, Mốc 5tr => (2/5)*100 = 40%
            $percent = ($totalSpent / $target) * 100;
            $remaining = $target - $totalSpent;
        }

        // E. Trả về dữ liệu để hiển thị bên View
        return [
            'current_rank' => $rank,
            'total_spent'  => $totalSpent,
            'next_rank'    => $nextRank,
            'remaining'    => $remaining,
            'percent'      => round($percent),
            'color'        => $color
        ];
    }
}
