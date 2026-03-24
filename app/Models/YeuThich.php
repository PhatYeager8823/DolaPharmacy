<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YeuThich extends Model
{
    use HasFactory;

    protected $table = 'yeu_thichs';

    protected $fillable = [
        'nguoi_dung_id',
        'thuoc_id',
    ];

    // Quan hệ: Yêu thích thuộc về 1 sản phẩm thuốc
    public function thuoc()
    {
        return $this->belongsTo(Thuoc::class, 'thuoc_id');
    }

    // Quan hệ: Yêu thích thuộc về 1 người dùng
    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_dung_id');
    }
}
