<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SinhVienSeeder extends Seeder
{
    public function run()
    {
        DB::table('sinh_viens')->delete();

        DB::table('sinh_viens')->truncate();

        DB::table('sinh_viens')->insert([
            [
                'ten_sinh_vien'         => "Sinh Viên A",
                'ma_sinh_vien'          => "2721234561",
                'so_dien_thoai'         => "123123123",
                'id_nien_khoa'          => 1,
                'diem_gpa'              => 5,
                'tinh_trang'            => 0,
                'password'              => bcrypt(123123),
            ],
            [
                'ten_sinh_vien'         => "Sinh Viên B",
                'ma_sinh_vien'          => "2721234562",
                'so_dien_thoai'         => "123123123",
                'id_nien_khoa'          => 1,
                'diem_gpa'              => 5,
                'tinh_trang'            => 0,
                'password'              => bcrypt(123123),
            ],
            [
                'ten_sinh_vien'         => "Sinh Viên C",
                'ma_sinh_vien'          => "2721234563",
                'so_dien_thoai'         => "123123123",
                'id_nien_khoa'          => 1,
                'diem_gpa'              => 5,
                'tinh_trang'            => 0,
                'password'              => bcrypt(123123),
            ],
            [
                'ten_sinh_vien'         => "Sinh Viên D",
                'ma_sinh_vien'          => "2721234564",
                'so_dien_thoai'         => "123123123",
                'id_nien_khoa'          => 1,
                'diem_gpa'              => 5,
                'tinh_trang'            => 0,
                'password'              => bcrypt(123123),
            ],
            [
                'ten_sinh_vien'         => "Sinh Viên E",
                'ma_sinh_vien'          => "2721234565",
                'so_dien_thoai'         => "123123123",
                'id_nien_khoa'          => 1,
                'diem_gpa'              => 5,
                'tinh_trang'            => 0,
                'password'              => bcrypt(123123),
            ],
            [
                'ten_sinh_vien'         => "Sinh Viên G",
                'ma_sinh_vien'          => "2721234566",
                'so_dien_thoai'         => "123123123",
                'id_nien_khoa'          => 1,
                'diem_gpa'              => 5,
                'tinh_trang'            => 0,
                'password'              => bcrypt(123123),
            ],
            [
                'ten_sinh_vien'         => "Sinh Viên 7",
                'ma_sinh_vien'          => "2721234567",
                'so_dien_thoai'         => "123123123",
                'id_nien_khoa'          => 1,
                'diem_gpa'              => 5,
                'tinh_trang'            => 0,
                'password'              => bcrypt(123123),
            ],
            [
                'ten_sinh_vien'         => "Sinh Viên 8",
                'ma_sinh_vien'          => "2721234568",
                'so_dien_thoai'         => "123123123",
                'id_nien_khoa'          => 1,
                'diem_gpa'              => 5,
                'tinh_trang'            => 0,
                'password'              => bcrypt(123123),
            ],
        ]);
    }
}
