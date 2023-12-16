<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;//1
use Laravel\Sanctum\HasApiTokens;//2
class SinhVien extends Authenticatable
{
    use HasFactory;
    use HasApiTokens;//2

    protected $table='sinh_viens';

    protected $fillable=[
        'ten_sinh_vien',
        'ma_sinh_vien',
        'so_dien_thoai',
        'id_nien_khoa',
        'diem_gpa',
        'tinh_trang',
        'password',
        'diem_mentor',
        'diem_chu_tich',
        'diem_thu_ky',
        'diem_uy_vien',
    ];
}
