<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChiTietPhieuNhap extends Model
{
    use HasFactory;

    protected $table = 'chi_tiet_phieu_nhaps';
    protected $guarded = [];

    // Liên kết ngược về Phiếu nhập
    public function phieuNhap()
    {
        return $this->belongsTo(PhieuNhap::class, 'phieu_nhap_id');
    }

    // Liên kết với Thuốc
    public function thuoc()
    {
        return $this->belongsTo(Thuoc::class, 'thuoc_id');
    }
}
