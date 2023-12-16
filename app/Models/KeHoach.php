<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KeHoach extends Model
{
    use HasFactory;

    protected $table = 'ke_hoachs';

    protected $fillable = [
        'ten_ke_hoach',
        'ngay',
        'mo_ta',
        'file',
        'tinh_trang',
    ];
}
