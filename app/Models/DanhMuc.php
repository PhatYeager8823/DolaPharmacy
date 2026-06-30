<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DanhMuc extends Model
{
    use HasFactory;

    protected $table = 'danh_mucs';

    protected $fillable = [
        'ten_danh_muc',
        'slug',
        'hinh_anh',
        'danh_muc_cha_id',
    ];

    // Danh mục cha
    public function parent()
    {
        return $this->belongsTo(DanhMuc::class, 'danh_muc_cha_id');
    }

    // Danh mục con
    public function children()
    {
        return $this->hasMany(DanhMuc::class, 'danh_muc_cha_id');
    }

    // Các thuốc thuộc danh mục
    public function thuocs()
    {
        return $this->hasMany(Thuoc::class, 'danh_muc_id');
    }

    // [MỚI] Lấy tên đầy đủ theo phân cấp: Thuốc > Kê đơn > Tiêu hóa
    public function getFullHierarchyAttribute()
    {
        $path = [$this->ten_danh_muc];
        $parent = $this->parent;
        while ($parent) {
            array_unshift($path, $parent->ten_danh_muc);
            $parent = $parent->parent;
        }
        return implode(' > ', $path);
    }
}
