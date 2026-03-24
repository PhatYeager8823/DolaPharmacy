<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Trigger Cập nhật tồn kho (Giữ nguyên của bạn)
        DB::unprepared("
            CREATE TRIGGER trg_capnhap_tonkho
            AFTER INSERT ON ton_khos -- Đổi thành AFTER cho chuẩn logic sau khi chốt phiếu
            FOR EACH ROW
            BEGIN
                IF NEW.loai_giao_dich = 'nhap' THEN
                    UPDATE thuocs
                    SET so_luong_ton = so_luong_ton + NEW.so_luong_thay_doi
                    WHERE id = NEW.thuoc_id;
                ELSEIF NEW.loai_giao_dich = 'xuat' THEN
                    UPDATE thuocs
                    SET so_luong_ton = so_luong_ton - NEW.so_luong_thay_doi
                    WHERE id = NEW.thuoc_id;
                END IF;
            END
        ");

        // 2. Trigger Tự động tính thành tiền (Mới thêm)
        DB::unprepared("
            CREATE TRIGGER trg_tinh_thanh_tien_hdct
            BEFORE INSERT ON hoa_don_chi_tiets
            FOR EACH ROW
            BEGIN
                SET NEW.thanh_tien = NEW.so_luong * NEW.don_gia;
            END
        ");
    }

    public function down(): void
    {
        DB::unprepared("DROP TRIGGER IF EXISTS trg_capnhap_tonkho");
        DB::unprepared("DROP TRIGGER IF EXISTS trg_tinh_thanh_tien_hdct");
    }
};
