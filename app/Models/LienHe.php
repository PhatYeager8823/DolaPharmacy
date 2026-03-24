<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LienHe extends Model
{
    use HasFactory;

    protected $table = 'lien_hes';

    protected $fillable = [
        'ho_ten',
        'email',
        'sdt',
        'tieu_de',
        'noi_dung',
        'trang_thai', // 0: Mới, 1: Đã xử lý
    ];
}
