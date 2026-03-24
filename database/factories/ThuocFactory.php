<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ThuocFactory extends Factory
{
    public function definition()
    {
        return [
            'ten_thuoc' => $this->faker->word() . " " . $this->faker->numberBetween(100, 1000),
            'ten_khoa_hoc' => $this->faker->word(),
            'ham_luong' => $this->faker->randomElement(['250mg', '500mg', '5mg', '10mg']),
            'don_vi_tinh' => 'Hộp',
            'gia_ban' => $this->faker->numberBetween(20000, 300000),
            'yeu_cau_ke_don' => $this->faker->boolean(),
            'hinh_anh' => null,

            'id_danh_muc' => rand(1, 5),
            'id_brand' => rand(1, 10),

            'thanh_phan' => $this->faker->sentence(10),
            'cong_dung' => $this->faker->sentence(12),
            'lieu_dung' => $this->faker->sentence(8),
            'chong_chi_dinh' => $this->faker->sentence(8),
            'tac_dung_phu' => $this->faker->sentence(10),
            'bao_quan' => $this->faker->sentence(8),

            'trang_thai' => 1,
        ];
    }
}
