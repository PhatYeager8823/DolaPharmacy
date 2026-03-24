<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    protected $table = 'brands'; // <--- Nên thêm dòng này cho chuẩn

    protected $fillable = [
        'ten',
        'slug',
        'hinh_anh',
        'xuat_xu',
    ];

    public function thuocs() { return $this->hasMany(Thuoc::class); }
}
