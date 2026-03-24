<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class HoaDonChiTietFactory extends Factory
{
    public function definition()
    {
        $sl = rand(1, 5);
        $gia = rand(10000, 200000);

        return [
            'id_hoa_don' => rand(1, 10),
            'id_thuoc' => rand(1, 20),
            'so_luong' => $sl,
            'don_gia' => $gia,
            'thanh_tien' => $sl * $gia,
        ];
    }
}
