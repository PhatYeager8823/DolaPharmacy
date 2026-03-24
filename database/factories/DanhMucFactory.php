<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DanhMucFactory extends Factory
{
    public function definition()
    {
        return [
            'ten_danh_muc' => $this->faker->word(),
            'mo_ta' => $this->faker->sentence(),
            'trang_thai' => 1,
        ];
    }
}
