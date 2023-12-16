<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KhoaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('nien_khoas')->delete();

        DB::table('nien_khoas')->truncate();

        DB::table('nien_khoas')->insert([
            [
                'ten_nien_khoa'         => "Khóa 26",
                'thoi_gian_bat_dau'     => "2019",
                'thoi_gian_ket_thuc'    => "2023",
                'tinh_trang'            => 1,
                'ma_nien_khoa'          => "K26",
            ],
        ]);
    }
}
