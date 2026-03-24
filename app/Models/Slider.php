<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use HasFactory;

    protected $table = 'sliders';

    protected $fillable = [
        'tieu_de',      // Tiêu đề banner (hiển thị đè lên ảnh hoặc dùng cho SEO)
        'hinh_anh',     // Đường dẫn file ảnh
        'link',         // Đường dẫn khi bấm vào banner
        'mo_ta',        // Mô tả ngắn
        'thu_tu',       // Sắp xếp thứ tự hiển thị (số nhỏ lên trước)
        'is_active',    // 1: Hiện, 0: Ẩn
    ];
}
