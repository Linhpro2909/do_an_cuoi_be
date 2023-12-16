<?php

namespace App\Http\Controllers;

use App\Models\KeHoach;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KeHoachController extends Controller
{
    public function getKeHoach()
    {
        $data = KeHoach::select('ke_hoachs.*', DB::raw ("DATE_FORMAT(ngay,'%d/%m/%Y') as ngay"))->get();
        return response()->json([
            'status'    => 1,
            'data'   => $data,
        ]);
    }
}
