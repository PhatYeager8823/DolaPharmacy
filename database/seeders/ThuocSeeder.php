<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ThuocSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Xóa dữ liệu cũ
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('thuocs')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. Lấy ID danh mục (Map theo slug trong DanhMucSeeder)
        $cats = DB::table('danh_mucs')->pluck('id', 'slug');

        // Mặc định Brand và NCC
        $brandDefault = DB::table('brands')->value('id') ?? 1;
        $nccDefault = DB::table('nha_cung_caps')->value('id') ?? 1;

        // 3. DANH SÁCH SẢN PHẨM (Đầy đủ 21 nhóm)
        $products = [

            // =========================================================
            // GROUP 1: THUỐC
            // =========================================================

            // 1. Thuốc tiêu hóa, Gan mật
            [
                'ten_thuoc' => 'Men vi sinh Enterogermina (Pháp)', 'slug' => 'enterogermina', 'ma_san_pham' => 'TH001',
                'gia_ban' => 160000, 'gia_cu' => 180000, 'ke_don' => 0,
                'danh_muc_id' => $cats['tieu-hoa-gan-mat'] ?? 1,
                'is_new' => 0, 'thuoc_uu_tien' => 1, 'hinh_anh' => 'enterogermina.png'
            ],
            [
                'ten_thuoc' => 'Thuốc dạ dày Nexium 40mg', 'slug' => 'nexium-40mg', 'ma_san_pham' => 'TH-RX-01',
                'gia_ban' => 350000, 'gia_cu' => null, 'ke_don' => 1, // Kê đơn
                'danh_muc_id' => $cats['tieu-hoa-gan-mat'] ?? 1,
                'hinh_anh' => 'nexium.png'
            ],

            // 2. Thuốc thần kinh, Não bộ
            [
                'ten_thuoc' => 'Hoạt huyết dưỡng não Traphaco', 'slug' => 'hoat-huyet-duong-nao', 'ma_san_pham' => 'TK001',
                'gia_ban' => 95000, 'gia_cu' => 105000, 'ke_don' => 0,
                'danh_muc_id' => $cats['than-kinh-nao-bo'] ?? 1,
                'is_suggested' => 1, 'hinh_anh' => 'hoat-huyet.png'
            ],
            [
                'ten_thuoc' => 'Stilux-60 (Rotunda) - An thần', 'slug' => 'stilux-60', 'ma_san_pham' => 'TK002',
                'gia_ban' => 45000, 'gia_cu' => null, 'ke_don' => 0,
                'danh_muc_id' => $cats['than-kinh-nao-bo'] ?? 1,
                'hinh_anh' => 'stilux.png'
            ],

            // 3. Thuốc cơ xương khớp, Gout
            [
                'ten_thuoc' => 'Salonpas (Hộp 20 miếng)', 'slug' => 'salonpas', 'ma_san_pham' => 'XK001',
                'gia_ban' => 30000, 'gia_cu' => null, 'ke_don' => 0,
                'danh_muc_id' => $cats['co-xuong-khop-gout'] ?? 1,
                'is_new' => 0, 'is_suggested' => 1, 'hinh_anh' => 'salonpas.png'
            ],
            [
                'ten_thuoc' => 'Celebrex 200mg (Giảm đau khớp)', 'slug' => 'celebrex-200', 'ma_san_pham' => 'XK-RX-01',
                'gia_ban' => 380000, 'gia_cu' => null, 'ke_don' => 1, // Kê đơn
                'danh_muc_id' => $cats['co-xuong-khop-gout'] ?? 1,
                'hinh_anh' => 'celebrex.png'
            ],

            // 4. Thuốc tim mạch, Huyết áp
            [
                'ten_thuoc' => 'Concor 5mg (Bisoprolol)', 'slug' => 'concor-5mg', 'ma_san_pham' => 'TM-RX-01',
                'gia_ban' => 120000, 'gia_cu' => null, 'ke_don' => 1,
                'danh_muc_id' => $cats['tim-mach-huyet-ap'] ?? 1,
                'hinh_anh' => 'concor.png'
            ],
            [
                'ten_thuoc' => 'Amlodipin 5mg (Huyết áp)', 'slug' => 'amlodipin-5mg', 'ma_san_pham' => 'TM-RX-02',
                'gia_ban' => 35000, 'gia_cu' => null, 'ke_don' => 1,
                'danh_muc_id' => $cats['tim-mach-huyet-ap'] ?? 1,
                'hinh_anh' => 'amlodipin.png'
            ],

            // 5. Thuốc giảm đau, Hạ sốt
            [
                'ten_thuoc' => 'Panadol Extra (Đỏ)', 'slug' => 'panadol-extra', 'ma_san_pham' => 'GD001',
                'gia_ban' => 180000, 'gia_cu' => 210000, 'ke_don' => 0,
                'danh_muc_id' => $cats['giam-dau-ha-sot'] ?? 1,
                'is_new' => 1, 'thuoc_uu_tien' => 1, 'hinh_anh' => 'panadol-extra.png'
            ],
            [
                'ten_thuoc' => 'Efferalgan 500mg Sủi', 'slug' => 'efferalgan-500mg', 'ma_san_pham' => 'GD002',
                'gia_ban' => 85000, 'gia_cu' => 90000, 'ke_don' => 0,
                'danh_muc_id' => $cats['giam-dau-ha-sot'] ?? 1,
                'hinh_anh' => 'efferalgan.png'
            ],

            // 6. Hô hấp, Ho, Cảm cúm
            [
                'ten_thuoc' => 'Siro Ho Prospan Đức 100ml', 'slug' => 'siro-ho-prospan', 'ma_san_pham' => 'HH001',
                'gia_ban' => 85000, 'gia_cu' => 95000, 'ke_don' => 0,
                'danh_muc_id' => $cats['ho-hap-cam-cum'] ?? 1,
                'is_new' => 1, 'hinh_anh' => 'prospan.png'
            ],
            [
                'ten_thuoc' => 'Augmentin 625mg (Kháng sinh)', 'slug' => 'augmentin-625mg', 'ma_san_pham' => 'HH-RX-01',
                'gia_ban' => 210000, 'gia_cu' => null, 'ke_don' => 1, // Kê đơn
                'danh_muc_id' => $cats['ho-hap-cam-cum'] ?? 1,
                'hinh_anh' => 'augmentin.png'
            ],

            // 7. Da liễu, Dị ứng
            [
                'ten_thuoc' => 'Kem bôi da 7 màu Silkron', 'slug' => 'silkron', 'ma_san_pham' => 'DL001',
                'gia_ban' => 15000, 'gia_cu' => null, 'ke_don' => 0,
                'danh_muc_id' => $cats['da-lieu-di-ung'] ?? 1,
                'hinh_anh' => 'silkron.png'
            ],
            [
                'ten_thuoc' => 'Fucidin H (Viêm da)', 'slug' => 'fucidin-h', 'ma_san_pham' => 'DL-RX-02',
                'gia_ban' => 95000, 'gia_cu' => null, 'ke_don' => 1,
                'danh_muc_id' => $cats['da-lieu-di-ung'] ?? 1,
                'hinh_anh' => 'fucidin.png'
            ],

            // 8. Mắt - Tai - Mũi - Họng
            [
                'ten_thuoc' => 'Nước muối sinh lý Vshine', 'slug' => 'nuoc-muoi-vshine', 'ma_san_pham' => 'TMH001',
                'gia_ban' => 5000, 'gia_cu' => null, 'ke_don' => 0,
                'danh_muc_id' => $cats['mat-tai-mui-hong'] ?? 1,
                'hinh_anh' => 'nuoc-muoi.png'
            ],
            [
                'ten_thuoc' => 'Thuốc nhỏ mắt V.Rohto', 'slug' => 'v-rohto', 'ma_san_pham' => 'TMH002',
                'gia_ban' => 48000, 'gia_cu' => 55000, 'ke_don' => 0,
                'danh_muc_id' => $cats['mat-tai-mui-hong'] ?? 1,
                'hinh_anh' => 'rohto.png'
            ],

            // 9. Dầu gió, Cao xoa
            [
                'ten_thuoc' => 'Dầu gió xanh Thiên Thảo', 'slug' => 'dau-gio-thien-thao', 'ma_san_pham' => 'DG001',
                'gia_ban' => 12000, 'gia_cu' => null, 'ke_don' => 0,
                'danh_muc_id' => $cats['dau-gio-cao-xoa'] ?? 1,
                'hinh_anh' => 'dau-gio.png'
            ],
            [
                'ten_thuoc' => 'Cao sao vàng (Hộp thiếc)', 'slug' => 'cao-sao-vang', 'ma_san_pham' => 'DG002',
                'gia_ban' => 8000, 'gia_cu' => null, 'ke_don' => 0,
                'danh_muc_id' => $cats['dau-gio-cao-xoa'] ?? 1,
                'hinh_anh' => 'cao-sao-vang.png'
            ],

            // =========================================================
            // GROUP 2: THỰC PHẨM CHỨC NĂNG
            // =========================================================

            // 10. Vitamin & Khoáng chất
            [
                'ten_thuoc' => 'Enervon C (Lọ 100 viên)', 'slug' => 'enervon-c', 'ma_san_pham' => 'VT001',
                'gia_ban' => 220000, 'gia_cu' => 250000, 'ke_don' => 0,
                'danh_muc_id' => $cats['vitamin-khoang-chat'] ?? 2,
                'thuoc_uu_tien' => 1, 'hinh_anh' => 'enervon.png'
            ],
            [
                'ten_thuoc' => 'Vitamin E Enat 400', 'slug' => 'enat-400', 'ma_san_pham' => 'VT005',
                'gia_ban' => 110000, 'gia_cu' => 130000, 'ke_don' => 0,
                'danh_muc_id' => $cats['vitamin-khoang-chat'] ?? 2,
                'hinh_anh' => 'enat400.png'
            ],

            // 11. Hỗ trợ làm đẹp
            [
                'ten_thuoc' => 'Collagen Youtheory Mỹ (390 viên)', 'slug' => 'collagen-youtheory', 'ma_san_pham' => 'LD001',
                'gia_ban' => 550000, 'gia_cu' => 650000, 'ke_don' => 0,
                'danh_muc_id' => $cats['ho-tro-lam-dep'] ?? 2,
                'is_new' => 1, 'hinh_anh' => 'collagen.png'
            ],
            [
                'ten_thuoc' => 'Sữa ong chúa Healthy Care', 'slug' => 'sua-ong-chua', 'ma_san_pham' => 'LD004',
                'gia_ban' => 450000, 'gia_cu' => 500000, 'ke_don' => 0,
                'danh_muc_id' => $cats['ho-tro-lam-dep'] ?? 2,
                'hinh_anh' => 'sua-ong-chua.png'
            ],

            // 12. Sức khỏe tim mạch
            [
                'ten_thuoc' => 'Viên uống CoQ10 150mg', 'slug' => 'coq10', 'ma_san_pham' => 'TPCN-TM01',
                'gia_ban' => 550000, 'gia_cu' => 600000, 'ke_don' => 0,
                'danh_muc_id' => $cats['suc-khoe-tim-mach'] ?? 2,
                'hinh_anh' => 'coq10.png'
            ],
            [
                'ten_thuoc' => 'Dầu cá Omega 3-6-9', 'slug' => 'omega-369', 'ma_san_pham' => 'TPCN-TM02',
                'gia_ban' => 250000, 'gia_cu' => 300000, 'ke_don' => 0,
                'danh_muc_id' => $cats['suc-khoe-tim-mach'] ?? 2,
                'hinh_anh' => 'omega369.png'
            ],

            // 13. Dinh dưỡng & Mẹ bé
            [
                'ten_thuoc' => 'Sữa bột PediaSure 850g', 'slug' => 'pediasure-850', 'ma_san_pham' => 'MB001',
                'gia_ban' => 650000, 'gia_cu' => 680000, 'ke_don' => 0,
                'danh_muc_id' => $cats['dinh-duong-me-be'] ?? 2,
                'is_new' => 1, 'hinh_anh' => 'pediasure.png'
            ],
            [
                'ten_thuoc' => 'Vitamin bầu Elevit', 'slug' => 'elevit-bau', 'ma_san_pham' => 'MB004',
                'gia_ban' => 1150000, 'gia_cu' => 1250000, 'ke_don' => 0,
                'danh_muc_id' => $cats['dinh-duong-me-be'] ?? 2,
                'is_exclusive' => 1, 'hinh_anh' => 'elevit.png'
            ],

            // =========================================================
            // GROUP 3: THIẾT BỊ Y TẾ
            // =========================================================

            // 14. Máy đo
            [
                'ten_thuoc' => 'Máy đo huyết áp Omron HEM-8712', 'slug' => 'may-do-huyet-ap-omron', 'ma_san_pham' => 'TB001',
                'gia_ban' => 750000, 'gia_cu' => 890000, 'ke_don' => 0,
                'danh_muc_id' => $cats['may-do-y-te'] ?? 3,
                'is_suggested' => 1, 'hinh_anh' => 'omron.png'
            ],
            [
                'ten_thuoc' => 'Máy đo đường huyết Accu-Chek', 'slug' => 'accu-chek', 'ma_san_pham' => 'TB002',
                'gia_ban' => 1200000, 'gia_cu' => 1350000, 'ke_don' => 0,
                'danh_muc_id' => $cats['may-do-y-te'] ?? 3,
                'hinh_anh' => 'accu-chek.png'
            ],

            // 15. Nhiệt kế
            [
                'ten_thuoc' => 'Nhiệt kế điện tử Microlife', 'slug' => 'nhiet-ke-microlife', 'ma_san_pham' => 'TB003',
                'gia_ban' => 85000, 'gia_cu' => 100000, 'ke_don' => 0,
                'danh_muc_id' => $cats['nhiet-ke'] ?? 3,
                'hinh_anh' => 'nhiet-ke.png'
            ],
            [
                'ten_thuoc' => 'Nhiệt kế hồng ngoại Omron', 'slug' => 'nhiet-ke-hong-ngoai', 'ma_san_pham' => 'TB004',
                'gia_ban' => 650000, 'gia_cu' => 700000, 'ke_don' => 0,
                'danh_muc_id' => $cats['nhiet-ke'] ?? 3,
                'hinh_anh' => 'nhiet-ke-hong-ngoai.png'
            ],

            // 16. Khẩu trang y tế
            [
                'ten_thuoc' => 'Khẩu trang y tế 4 lớp (Hộp 50 cái)', 'slug' => 'khau-trang-4-lop', 'ma_san_pham' => 'TB005',
                'gia_ban' => 35000, 'gia_cu' => null, 'ke_don' => 0,
                'danh_muc_id' => $cats['khau-trang-y-te'] ?? 3,
                'hinh_anh' => 'khau-trang.png'
            ],
            [
                'ten_thuoc' => 'Khẩu trang 4D Mask (Hộp 10 cái)', 'slug' => 'khau-trang-4d', 'ma_san_pham' => 'TB006',
                'gia_ban' => 20000, 'gia_cu' => null, 'ke_don' => 0,
                'danh_muc_id' => $cats['khau-trang-y-te'] ?? 3,
                'hinh_anh' => 'khau-trang-4d.png'
            ],

            // 17. Dụng cụ sơ cứu
            [
                'ten_thuoc' => 'Băng cá nhân Urgo (Hộp 100 miếng)', 'slug' => 'bang-ca-nhan-urgo', 'ma_san_pham' => 'TB007',
                'gia_ban' => 45000, 'gia_cu' => null, 'ke_don' => 0,
                'danh_muc_id' => $cats['dung-cu-so-cuu'] ?? 3,
                'hinh_anh' => 'urgo.png'
            ],
            [
                'ten_thuoc' => 'Bông y tế Bạch Tuyết 1kg', 'slug' => 'bong-bach-tuyet', 'ma_san_pham' => 'TB008',
                'gia_ban' => 120000, 'gia_cu' => null, 'ke_don' => 0,
                'danh_muc_id' => $cats['dung-cu-so-cuu'] ?? 3,
                'hinh_anh' => 'bong-y-te.png'
            ],

            // =========================================================
            // GROUP 4: CHĂM SÓC CÁ NHÂN
            // =========================================================

            // 18. Chăm sóc cơ thể
            [
                'ten_thuoc' => 'Sữa tắm Cetaphil 500ml', 'slug' => 'cetaphil-500', 'ma_san_pham' => 'MP001',
                'gia_ban' => 350000, 'gia_cu' => 390000, 'ke_don' => 0,
                'danh_muc_id' => $cats['cham-soc-co-the'] ?? 4,
                'is_new' => 1, 'hinh_anh' => 'cetaphil.png'
            ],
            [
                'ten_thuoc' => 'Lăn khử mùi Nivea', 'slug' => 'lan-khu-mui-nivea', 'ma_san_pham' => 'MP002',
                'gia_ban' => 65000, 'gia_cu' => 75000, 'ke_don' => 0,
                'danh_muc_id' => $cats['cham-soc-co-the'] ?? 4,
                'hinh_anh' => 'nivea.png'
            ],

            // 19. Chăm sóc da mặt
            [
                'ten_thuoc' => 'Kem chống nắng Anessa', 'slug' => 'anessa-milk', 'ma_san_pham' => 'MP003',
                'gia_ban' => 450000, 'gia_cu' => 500000, 'ke_don' => 0,
                'danh_muc_id' => $cats['cham-soc-da-mat'] ?? 4,
                'thuoc_uu_tien' => 1, 'hinh_anh' => 'anessa.png'
            ],
            [
                'ten_thuoc' => 'Bông tẩy trang Silcot', 'slug' => 'bong-tay-trang-silcot', 'ma_san_pham' => 'MP004',
                'gia_ban' => 35000, 'gia_cu' => null, 'ke_don' => 0,
                'danh_muc_id' => $cats['cham-soc-da-mat'] ?? 4,
                'hinh_anh' => 'silcot.png'
            ],

            // 20. Vệ sinh phụ nữ
            [
                'ten_thuoc' => 'Dung dịch vệ sinh Dạ Hương', 'slug' => 'da-huong', 'ma_san_pham' => 'MP005',
                'gia_ban' => 25000, 'gia_cu' => null, 'ke_don' => 0,
                'danh_muc_id' => $cats['ve-sinh-phu-nu'] ?? 4,
                'hinh_anh' => 'dahuong.png'
            ],
            [
                'ten_thuoc' => 'Dung dịch Lactacyd 250ml', 'slug' => 'lactacyd', 'ma_san_pham' => 'MP006',
                'gia_ban' => 65000, 'gia_cu' => 72000, 'ke_don' => 0,
                'danh_muc_id' => $cats['ve-sinh-phu-nu'] ?? 4,
                'hinh_anh' => 'lactacyd.png'
            ],

            // 21. Chăm sóc răng miệng
            [
                'ten_thuoc' => 'Nước súc miệng Listerine', 'slug' => 'listerine', 'ma_san_pham' => 'MP007',
                'gia_ban' => 85000, 'gia_cu' => 95000, 'ke_don' => 0,
                'danh_muc_id' => $cats['cham-soc-rang-mieng'] ?? 4,
                'hinh_anh' => 'listerine.png'
            ],
            [
                'ten_thuoc' => 'Kem đánh răng Sensodyne', 'slug' => 'sensodyne', 'ma_san_pham' => 'MP008',
                'gia_ban' => 55000, 'gia_cu' => 60000, 'ke_don' => 0,
                'danh_muc_id' => $cats['cham-soc-rang-mieng'] ?? 4,
                'hinh_anh' => 'sensodyne.png'
            ],
        ];

        foreach ($products as $p) {
            // Logic xử lý: Nếu kê đơn thì xóa hết cờ quảng cáo
            if (isset($p['ke_don']) && $p['ke_don'] == 1) {
                $p['gia_cu'] = null;
                $p['is_new'] = 0;
                $p['thuoc_uu_tien'] = 0;
                $p['is_exclusive'] = 0;
                $p['is_suggested'] = 0;
            }

            // Fallback ID danh mục nếu chưa có trong DB (Tránh lỗi)
            $catId = $p['danh_muc_id'] != 1 ? $p['danh_muc_id'] : DB::table('danh_mucs')->value('id');

            DB::table('thuocs')->insert([
                'ten_thuoc' => $p['ten_thuoc'],
                'slug' => $p['slug'],
                'ma_san_pham' => $p['ma_san_pham'],
                'gia_ban' => $p['gia_ban'],
                'gia_cu' => $p['gia_cu'] ?? null,
                'danh_muc_id' => $catId,
                'ke_don' => $p['ke_don'] ?? 0,

                'brand_id' => $brandDefault,
                'nha_cung_cap_id' => $nccDefault,
                'hinh_anh' => $p['hinh_anh'],
                'hoat_chat' => 'Đang cập nhật',
                'cong_dung' => '<p>Công dụng tốt cho sức khỏe...</p>',
                'mo_ta_ngan' => 'Sản phẩm chất lượng cao...',
                'don_vi_tinh' => 'Hộp',
                'quy_cach' => 'Hộp 1 lọ',
                'so_luong_ton' => 50,
                'is_active' => 1,

                'thuoc_uu_tien' => $p['thuoc_uu_tien'] ?? 0,
                'is_new'        => $p['is_new'] ?? 0,
                'is_exclusive'  => $p['is_exclusive'] ?? 0,
                'is_suggested'  => $p['is_suggested'] ?? 0,

                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
