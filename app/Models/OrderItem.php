<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id', 'thuoc_id', 'ten_thuoc', 'so_luong', 'gia_ban', 'thanh_tien'
    ];

    public function thuoc()
    {
        return $this->belongsTo(Thuoc::class);
    }
}
