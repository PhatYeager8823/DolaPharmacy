<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TonKho extends Model
{
    use HasFactory;

    protected $table = 'ton_khos';

    protected $fillable = [
        'thuoc_id',
        'so_luong_thay_doi',
        'loai_giao_dich', // 'nhap' hoặc 'xuat'
        'gia_nhap',
        'ghi_chu',
    ];

    // Quan hệ với Thuốc
    public function thuoc()
    {
        return $this->belongsTo(Thuoc::class, 'thuoc_id');
    }
}
