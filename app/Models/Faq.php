<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasFactory;

    protected $table = 'faqs'; // Khai báo tên bảng (tùy chọn nếu tên đúng chuẩn)

    // Khai báo các cột được phép thêm dữ liệu
    protected $fillable = [
        'cau_hoi',
        'tra_loi',
        'thu_tu',
        'is_active',
    ];
}
