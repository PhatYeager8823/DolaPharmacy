<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiaChi extends Model
{
    use HasFactory;

    // Khai báo tên bảng
    protected $table = 'dia_chis';

    // Khai báo các cột được phép thêm dữ liệu (Mass Assignment)
    protected $fillable = [
        'nguoi_dung_id',
        'ten_nguoi_nhan',
        'sdt_nguoi_nhan',
        'dia_chi_cu_the',
        'mac_dinh',
    ];

    /**
     * Quan hệ: Mỗi địa chỉ thuộc về một người dùng
     */
    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_dung_id');
    }
}
