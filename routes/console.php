<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule; // <--- [QUAN TRỌNG] Nhớ thêm dòng này

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// ========================================================
// LẬP LỊCH TỰ ĐỘNG CẬP NHẬT TRẠNG THÁI ĐƠN HÀNG
// ========================================================
// Chạy lệnh mô phỏng mỗi phút 1 lần
Schedule::command('order:simulate')->everyMinute();
