<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaiViet extends Model
{
    use HasFactory;

    // Tên bảng trong database (nếu bạn đặt tên khác thì sửa lại)
    protected $table = 'bai_viets';

    protected $fillable = [
        'tieu_de',
        'slug',         // Thường bài viết sẽ cần slug (đường dẫn đẹp)
        'mo_ta_ngan',
        'noi_dung',
        'hinh_anh',
        'is_active',    // Trạng thái hiển thị
    ];
}
