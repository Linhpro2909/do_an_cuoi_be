<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeTaiSinhVien extends Model
{
    use HasFactory;
    protected $table='de_tai_sinh_viens';
    protected $fillable=[
        'ten_de_tai',
        'mo_ta',
        'ngon_ngu_lap_trinh',
        'tinh_trang',
        'ma_nhom',
        'ten_nhom',
        'thoi_gian_ket_thuc'
    ];
}
