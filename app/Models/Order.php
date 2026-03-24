<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'ma_don_hang', 'nguoi_dung_id',
        'ten_nguoi_nhan', 'sdt_nguoi_nhan', 'dia_chi_giao_hang',
        'tong_tien', 'phuong_thuc_thanh_toan', 'trang_thai', 'ghi_chu'
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Trong file app/Models/Order.php
    public function nguoiDung()
    {
        // nguoi_dung_id là tên cột khóa ngoại trong bảng orders
        return $this->belongsTo(NguoiDung::class, 'nguoi_dung_id');
    }
}
