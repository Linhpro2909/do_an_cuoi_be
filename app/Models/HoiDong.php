<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HoiDong extends Model
{
    use HasFactory;
    protected $table='hoi_dongs';
    protected $fillable=[
        'ten_hoi_dong',
        'list_id_hoi_dong',
        'thanh_vien_hoi_dong',
        'thoi_gian',
        'list_ma_nhom',
        'id_chu_tich',
        'id_thu_ky',
        'id_uy_vien',

    ];
}
