<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // <--- THÊM DÒNG NÀY

class Thuoc extends Model
{
    use HasFactory, SoftDeletes; // <--- THÊM VÀO ĐÂY

    protected $table = 'thuocs';

    protected $fillable = [
        'ten_thuoc',
        'slug',
        'ma_san_pham',
        'so_dang_ky',
        'hinh_anh',
        'gia_ban',
        'gia_cu',
        'don_vi_tinh',
        'quy_cach',
        'da_ban',
        'hoat_chat',
        'ham_luong',
        'dang_bao_che',
        'nuoc_san_xuat',
        'nha_san_xuat',
        'mo_ta_ngan',
        'cong_dung',
        'cach_dung',
        'tac_dung_phu',
        'thanh_phan',
        'ke_don',
        'nuoc_xuat_xu',
        'thuoc_uu_tien',
        'danh_muc_id',
        'brand_id',
        'nha_cung_cap_id',
        'so_luong_ton',
        'is_active',
        'is_new',       // <--- Mới
        'is_exclusive', // <--- Mới
        'is_suggested', // <--- Mới
        'is_featured',
    ];

    // Relationships
    public function danhMuc()
    {
        return $this->belongsTo(DanhMuc::class, 'danh_muc_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function nhaCungCap()
    {
        return $this->belongsTo(NhaCungCap::class, 'nha_cung_cap_id');
    }

    public function tonKho()
    {
        return $this->hasMany(TonKho::class, 'thuoc_id');
    }

    // Thêm quan hệ với chi tiết hóa đơn (để tiện tra cứu sau này)
    public function hoaDonChiTiets()
    {
        return $this->hasMany(HoaDonChiTiet::class, 'thuoc_id');
    }

    // Quan hệ với Đánh giá
    public function danhGias()
    {
        // 1 thuốc có nhiều đánh giá
        return $this->hasMany(DanhGia::class, 'thuoc_id');
    }
}
