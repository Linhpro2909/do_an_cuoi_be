<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;//1
use Laravel\Sanctum\HasApiTokens;//2

class GiangVien extends Authenticatable
{
    use HasFactory;
    use HasApiTokens;//2

    protected $table='giang_viens';
    protected $fillable=[
        'ma_giang_vien',
        'ten_giang_vien',
        'ngay_thang_nam_sinh',
        'dia_chi',
        'dia_chi_cong_tac',
        'email',
        'password'
    ];
}
