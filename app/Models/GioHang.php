<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GioHang extends Model
{
    use HasFactory;

    protected $table = 'gio_hangs'; // Tên bảng trong CSDL

    protected $fillable = [
        'nguoi_dung_id',
    ];

    // Quan hệ: 1 Giỏ hàng thuộc về 1 Người dùng
    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_dung_id');
    }

    // Quan hệ: 1 Giỏ hàng có nhiều Chi tiết (sản phẩm)
    public function chiTiets()
    {
        return $this->hasMany(GioHangChiTiet::class, 'gio_hang_id');
    }
}
