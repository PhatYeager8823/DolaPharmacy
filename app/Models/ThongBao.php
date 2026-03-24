<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThongBao extends Model
{
    use HasFactory;

    // 1. Tên bảng (Khai báo rõ ràng để tránh lỗi)
    protected $table = 'thong_baos';

    // 2. Các cột được phép thêm dữ liệu (Mass Assignment)
    protected $fillable = [
        'nguoi_dung_id', // ID người nhận thông báo
        'tieu_de',       // Tiêu đề ngắn gọn
        'noi_dung',      // Nội dung chi tiết
        'loai',          // Phân loại: 'don_hang', 'khuyen_mai', 'he_thong'...
        'duong_dan',     // Link khi click vào (Route name hoặc URL)
        'da_xem'         // Trạng thái: 0 (chưa xem), 1 (đã xem)
    ];

    // 3. Định dạng dữ liệu đầu ra
    protected $casts = [
        'da_xem' => 'boolean',     // Tự động chuyển 0/1 thành false/true
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // =================================================================
    // RELATIONSHIPS (MỐI QUAN HỆ)
    // =================================================================

    /**
     * Thông báo này thuộc về người dùng nào?
     */
    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_dung_id');
    }

    // =================================================================
    // SCOPES (CÁC CÂU TRUY VẤN CÓ SẴN)
    // =================================================================

    /**
     * Lọc lấy các thông báo chưa xem
     * Cách dùng: ThongBao::chuaXem()->get();
     */
    public function scopeChuaXem($query)
    {
        return $query->where('da_xem', false);
    }

    /**
     * Sắp xếp mới nhất lên đầu
     * Cách dùng: ThongBao::moiNhat()->get();
     */
    public function scopeMoiNhat($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    // =================================================================
    // ACCESSORS (HÀM PHỤ TRỢ CHO VIEW)
    // =================================================================

    /**
     * Lấy Icon tương ứng với loại thông báo (Dùng FontAwesome)
     * Cách dùng trong View: <i class="{{ $notification->icon_class }}"></i>
     */
    public function getIconClassAttribute()
    {
        return match ($this->loai) {
            'don_hang'   => 'fas fa-box-open text-primary',   // Icon hộp hàng (Màu xanh)
            'khuyen_mai' => 'fas fa-gift text-danger',        // Icon quà tặng (Màu đỏ)
            'he_thong'   => 'fas fa-cogs text-secondary',     // Icon cài đặt (Màu xám)
            'bao_mat'    => 'fas fa-shield-alt text-warning', // Icon khiên (Màu vàng)
            default      => 'fas fa-bell text-info',          // Mặc định cái chuông
        };
    }

    /**
     * Format thời gian thân thiện (Ví dụ: "5 phút trước")
     * Cách dùng trong View: {{ $notification->thoi_gian }}
     */
    public function getThoiGianAttribute()
    {
        return $this->created_at->diffForHumans();
    }
}
