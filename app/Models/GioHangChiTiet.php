<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GioHangChiTiet extends Model
{
    use HasFactory;

    protected $table = 'gio_hang_chi_tiets';

    protected $fillable = [
        'gio_hang_id',
        'thuoc_id',
        'so_luong',
    ];

    // Quan hệ: Chi tiết thuộc về 1 Giỏ hàng
    public function gioHang()
    {
        return $this->belongsTo(GioHang::class, 'gio_hang_id');
    }

    // Quan hệ: Chi tiết chứa thông tin của 1 loại Thuốc
    public function thuoc()
    {
        return $this->belongsTo(Thuoc::class, 'thuoc_id');
    }
}
