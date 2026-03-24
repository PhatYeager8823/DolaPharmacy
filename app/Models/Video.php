<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    protected $table = 'videos'; // Tên bảng

    // Thêm slug và video_url vào đây
    protected $fillable = [
        'tieu_de',
        'slug',        // <--- Bổ sung cái này
        'youtube_id',   // <--- Bổ sung cái này (vì SQL log của bạn đang bị thiếu nó)
        'thumbnail',
        'is_active',
    ];
}
