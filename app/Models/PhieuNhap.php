<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhieuNhap extends Model
{
    use HasFactory;

    protected $table = 'phieu_nhaps';
    protected $guarded = []; // Cho phép lưu mọi trường

    // Liên kết với Nhà cung cấp (Cái model bạn đã có)
    public function nhaCungCap()
    {
        return $this->belongsTo(NhaCungCap::class, 'nha_cung_cap_id');
    }

    // Liên kết với Người nhập (User)
    public function nguoiNhap()
    {
        // Lưu ý: Nếu model User của bạn tên là NguoiDung thì sửa 'User::class' thành 'NguoiDung::class'
        return $this->belongsTo(\App\Models\NguoiDung::class, 'nguoi_nhap_id');
    }

    // Liên kết với Chi tiết phiếu nhập
    public function chiTiet()
    {
        return $this->hasMany(ChiTietPhieuNhap::class, 'phieu_nhap_id');
    }
}
