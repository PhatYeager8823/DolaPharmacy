<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'type',
        'value',
        'expiry_date',
        'is_active',
        'image',
    ];

    // Định nghĩa quan hệ ngược lại với User (nếu cần dùng sau này)
    public function users()
    {
        return $this->belongsToMany(NguoiDung::class, 'coupon_user', 'coupon_id', 'user_id')
                    ->withPivot('used_at')
                    ->withTimestamps();
    }
}
