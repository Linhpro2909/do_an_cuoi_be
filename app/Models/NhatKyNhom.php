<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NhatKyNhom extends Model
{
    use HasFactory;

    protected $table = "nhat_ky_nhoms";

    protected $fillable = [
        'ten_nhat_ky',
        'thoi_gian',
        'mo_ta',
        'tinh_trang',
        'file',
        'ten_file',
        'id_nhom',
        'ma_nhom',
        'id_sinh_vien'
    ];
}
