<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class HoaDonFactory extends Factory
{
    public function definition()
    {
        return [
            'id_nguoi_dung' => rand(1, 10),
            'tong_tien' => $this->faker->numberBetween(50000, 2000000),
            'dia_chi_giao' => $this->faker->address(),
            'trang_thai' => 'cho_xu_ly',
            'ghi_chu' => $this->faker->sentence(6),
        ];
    }
}
