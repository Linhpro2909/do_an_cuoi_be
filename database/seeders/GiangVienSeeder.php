<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GiangVienSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('giang_viens')->delete();

        DB::table('giang_viens')->truncate();

        DB::table('giang_viens')->insert([
            [
                'ma_giang_vien'         => 001,
                'ten_giang_vien'        => "Nguyễn Văn A",
                'ngay_thang_nam_sinh'   => "2023-12-16",
                'dia_chi'               => "ĐN",
                'dia_chi_cong_tac'      => "Duy Tân",
                'email'                 => "123@gmail.com",
                'password'              => bcrypt(123123),
            ],
            [
                'ma_giang_vien'         => 002,
                'ten_giang_vien'        => "Nguyễn Văn B",
                'ngay_thang_nam_sinh'   => "2023-12-16",
                'dia_chi'               => "ĐN",
                'dia_chi_cong_tac'      => "Duy Tân",
                'email'                 => "124@gmail.com",
                'password'              => bcrypt(123123),
            ],
            [
                'ma_giang_vien'         => 003,
                'ten_giang_vien'        => "Nguyễn Văn C",
                'ngay_thang_nam_sinh'   => "2023-12-16",
                'dia_chi'               => "ĐN",
                'dia_chi_cong_tac'      => "Duy Tân",
                'email'                 => "125@gmail.com",
                'password'              => bcrypt(123123),
            ],
            [
                'ma_giang_vien'         => 004,
                'ten_giang_vien'        => "Nguyễn Văn D",
                'ngay_thang_nam_sinh'   => "2023-12-16",
                'dia_chi'               => "ĐN",
                'dia_chi_cong_tac'      => "Duy Tân",
                'email'                 => "126@gmail.com",
                'password'              => bcrypt(123123),
            ],
        ]);
    }
}
