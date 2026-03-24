<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class NguoiDungFactory extends Factory
{
    public function definition()
    {
        return [
            'ho_ten' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'mat_khau' => Hash::make('123456'),
            'so_dien_thoai' => $this->faker->phoneNumber(),
            'dia_chi' => $this->faker->address(),
            'loai' => 'khach',
        ];
    }
}
