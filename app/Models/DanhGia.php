<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DanhGia extends Model
{
    use HasFactory;

    protected $table = 'danh_gias';

    protected $fillable = [
        'thuoc_id',
        'nguoi_dung_id',
        'so_sao',
        'noi_dung',
        'trang_thai'
    ];

    // Ai đánh giá (Người dùng)
    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_dung_id');
    }

    // Đánh giá thuốc nào
    public function thuoc()
    {
        return $this->belongsTo(Thuoc::class, 'thuoc_id');
    }
}
