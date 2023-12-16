<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NienKhoa extends Model
{
    use HasFactory;
    protected $table='nien_khoas';
    protected $fillable=[
        'ten_nien_khoa',
        'thoi_gian_bat_dau',
        'thoi_gian_ket_thuc',
        'tinh_trang',
        'ma_nien_khoa'
    ];
}
