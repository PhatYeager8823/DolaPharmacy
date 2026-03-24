<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NhaCungCap extends Model
{
    use HasFactory;

    protected $table = 'nha_cung_caps'; // Đảm bảo tên bảng đúng

    protected $fillable = [
        'ten_nha_cung_cap', // Hoặc 'ten' tùy db của bạn
        'dia_chi',
        'sdt',
        'email',
        'trang_thai', // 1: Hoạt động, 0: Ngừng
    ];

    // Quan hệ với Thuốc (1 Nhà cung cấp có nhiều Thuốc)
    public function thuocs()
    {
        return $this->hasMany(Thuoc::class, 'nha_cung_cap_id');
    }
}
