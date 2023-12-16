<?php

namespace App\Http\Controllers;

use App\Models\GiangVien;
use App\Models\HoiDong;
use App\Models\Nhom;
use App\Models\SinhVien;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use stdClass;

class HoiDongController extends Controller
{
    public function createDatta(Request $request)
    {
        // Validate that each lecturer holds a unique position
        $selectedLecturers = [
            $request->ten_chu_tich,
            $request->ten_thu_ky,
            $request->ten_uy_vien,
        ];

        if (count($selectedLecturers) !== count(array_unique($selectedLecturers))) {
            // Nếu có trùng lặp, trả về một phản hồi lỗi
            return response()->json([
                'status'  => 0,
                'message' => "xin lỗi một trong các giảng viên đã giữ vài trò khác rồi.",
            ]);
        }

        $data = $request->all();
        $data['id_chu_tich'] = $request->ten_chu_tich;
        $data['id_thu_ky'] = $request->ten_thu_ky;
        $data['id_uy_vien'] = $request->ten_uy_vien;
        $string = $request->ten_chu_tich . "," . $request->ten_thu_ky . "," . $request->ten_uy_vien;
        $data['list_id_hoi_dong'] = $string;

        HoiDong::create($data);
        return response()->json([
            'status'  => 1,
            'message' => "Đã thêm hội đồng thành công!",
        ]);
    }

    public function getData()
    {
        $data = HoiDong::get();
        foreach ($data as $key => $value) {
            $array = explode(',', $value->list_id_hoi_dong);
            foreach ($array as $k => $v) {
                if ($k == 0) {
                    $giang_vien = GiangVien::where('id', $v)->select('id','ten_giang_vien')->first();
                    $value->ten_chu_tich = $giang_vien->ten_giang_vien;
                    $value->id_chu_tich = $giang_vien->id;
                } else if($k == 1){
                    $giang_vien = GiangVien::where('id', $v)->select('id','ten_giang_vien')->first();
                    $value->ten_thu_ky = $giang_vien->ten_giang_vien;
                    $value->id_thu_ky = $giang_vien->id;
                } else {
                    $giang_vien = GiangVien::where('id', $v)->select('id','ten_giang_vien')->first();
                    $value->ten_uy_vien = $giang_vien->ten_giang_vien;
                    $value->id_uy_vien = $giang_vien->id;
                }
            }
        }
        return response()->json([
            'status'    => 1,
            'data'      => $data,
        ]);
    }

    public function getDataDanhSachNhom()
    {
        $hoi_dong = HoiDong::select('hoi_dongs.*', DB::raw('DATE_FORMAT(hoi_dongs.thoi_gian, "%d/%m/%Y") as thoi_gian'))->get();
        $list = [];

        foreach ($hoi_dong as $key => $value) {
            $array = explode(',', $value['list_ma_nhom']);
            $array_1 = explode(',', $value->list_id_hoi_dong);
            foreach ($array_1 as $k => $v) {
                if ($k == 0) {
                    $giang_vien = GiangVien::where('id', $v)->select('id','ten_giang_vien')->first();
                    $value->ten_chu_tich = $giang_vien->ten_giang_vien;
                    $value->id_chu_tich = $giang_vien->id;
                } else if($k == 1){
                    $giang_vien = GiangVien::where('id', $v)->select('id','ten_giang_vien')->first();
                    $value->ten_thu_ky = $giang_vien->ten_giang_vien;
                    $value->id_thu_ky = $giang_vien->id;
                } else {
                    $giang_vien = GiangVien::where('id', $v)->select('id','ten_giang_vien')->first();
                    $value->ten_uy_vien = $giang_vien->ten_giang_vien;
                    $value->id_uy_vien = $giang_vien->id;
                }
            }

            if($value['list_ma_nhom'] != null) {

                foreach ($array as $v_1) {
                    $modifiedList = [];
                    $list_nhom = Nhom::where('ma_nhom', $v_1)->select('ten_nhom', 'ma_nhom', 'id_sinh_vien')->get();

                    foreach ($list_nhom as $v) {
                        if ($v_1 == $v->ma_nhom) {
                            $sinh_vien = SinhVien::where('id', $v['id_sinh_vien'])->select('ten_sinh_vien')->first();

                            if ($list_nhom) {
                                $object = new stdClass();
                                $object->ten_sinh_vien = $sinh_vien->ten_sinh_vien;
                                $modifiedList[] = $object;
                            }
                        }
                    }

                    $array_tem = $value->toArray();
                    $array_tem['ten_nhom'] = $v_1;
                    $array_tem['list_ma_nhom'] = $modifiedList;

                    $list[] = $array_tem;
                }
            }
        }

        return response()->json([
            'status'   => 1,
            'data'     => $list,
        ]);
    }

    public function getDataNhom() {
        $data = Nhom::join('de_tai_sinh_viens', 'de_tai_sinh_viens.ma_nhom', 'nhoms.ma_nhom')->where('de_tai_sinh_viens.tinh_trang', 1)->select('nhoms.ten_nhom','nhoms.ma_nhom','de_tai_sinh_viens.tinh_trang')->groupby('nhoms.ten_nhom','nhoms.ma_nhom','de_tai_sinh_viens.tinh_trang')->get();
        return response()->json([
            'data'   => $data,
        ]);
    }

    public function deleteNhom(Request $request)
    {
        $ten_nhom = $request['ten_nhom'];
        $data     = $request->all();
        $data['thoi_gian'] = Carbon::createFromFormat('d/m/Y', $data['thoi_gian'])->format('Y-m-d');
        $hoi_dong = HoiDong::where('id', $request['id'])->first();
        $newListMaNhom = "";
        if ($hoi_dong) {
            $array = explode(',', $hoi_dong->list_ma_nhom);
            if (count($array) > 1) {
                if (in_array($ten_nhom, $array)) {
                    $newListMaNhom = implode(',', array_diff($array, [$ten_nhom]));
                    $data['list_ma_nhom'] = rtrim($newListMaNhom, ',');
                    $hoi_dong->update($data);
                }
            } else {
                $hoi_dong->delete();
            }
        }

        return response()->json([
            'message'   => 'Đã xóa thành công!',
        ]);

    }

    public function chiaNhom(Request $request)
    {
        $hoi_dong = HoiDong::where('id', $request->id)->first();
        $array = explode(',', $hoi_dong->list_id_hoi_dong);
        $string =  $hoi_dong->list_ma_nhom . ",";
        $data = $request->all();
        $array_list_nhom = explode(',', $hoi_dong->list_ma_nhom);
        if ($hoi_dong) {
            foreach ($request->list_nhom as $key => $value) {
                if(isset($value["check"]) && $value["check"] == true) {
                    $giang_vien = Nhom::where('ma_nhom', $value["ma_nhom"])->first();
                    $id_giang_vien = $giang_vien->id_giang_vien;
                    $ten_giang_vien = Nhom::where('ma_nhom', $value["ma_nhom"])->join('giang_viens', 'giang_viens.id', 'nhoms.id_giang_vien')->select('giang_viens.ten_giang_vien', 'nhoms.ten_nhom')->first();
                    $nhom = Nhom::where('ma_nhom', $value["ma_nhom"])->get();
                    foreach ($nhom as $k_1 => $v_1) {
                        $v_1->id_hoi_dong =  $hoi_dong->id;
                        $v_1->save();
                    }

                    if(in_array($value["ma_nhom"], $array_list_nhom)) {
                        return response()->json([
                            'status'    => 0,
                            'message'   => "Nhóm " .$value["ten_nhom"]. " đã được phân công!!",
                        ]);
                    }

                    if (in_array($id_giang_vien, $array)) {
                        return response()->json([
                            'status'    => 0,
                            'message'   => 'Giảng viên ' . $ten_giang_vien->ten_giang_vien . " đang hướng dẫn lớp " . $ten_giang_vien->ten_nhom . " vui lòng chọn lại!",
                        ]);
                    } else {
                        $string .= $value["ma_nhom"] . ",";
                    }
                }
            }

            $data['list_ma_nhom'] = trim($string, ',');
            $hoi_dong->update($data);
            return response()->json([
                'status'    => 1,
                'message'   => 'Đã phân nhóm thành công!',
            ]);
        } else {
            return response()->json([
                'status'    => 0,
                'message'   => 'Hội đồng không tồn tại!',
            ]);
        }
    }

    public function updateHoiDong(Request $request)
    {
        $hoi_dong = HoiDong::where('id', $request->id)->first();
        if ($hoi_dong) {
            $data = $request->all();
            $string = $request->id_chu_tich . "," . $request->id_thu_ky . "," . $request->id_uy_vien;
            $data['list_id_hoi_dong'] = $string;
            $array = explode(',', $data['list_id_hoi_dong']);
            if($data['list_ma_nhom'] != null) {
                foreach (explode(",", $data['list_ma_nhom']) as $key => $value) {
                    $giang_vien = Nhom::where('ma_nhom', $value)->first();
                    $id_giang_vien = $giang_vien->id_giang_vien;
                    $ten_giang_vien = Nhom::where('ma_nhom', $value)->join('giang_viens', 'giang_viens.id', 'nhoms.id_giang_vien')->select('giang_viens.ten_giang_vien', 'nhoms.ten_nhom')->first();
                    if (in_array($id_giang_vien, $array)) {
                        return response()->json([
                            'status'    => 0,
                            'message'   => 'Giảng viên ' . $ten_giang_vien->ten_giang_vien . " đang hướng dẫn lớp " . $ten_giang_vien->ten_nhom . " vui lòng chọn lại!",
                        ]);
                    }
                }
                $hoi_dong->update($data);
                return response()->json([
                    'status'    => 1,
                    'message'   => 'Đã cập nhật thành công!',
                ]);
            } else {
                $hoi_dong->update($data);
                return response()->json([
                    'status'    => 1,
                    'message'   => 'Đã cập nhật thành công!',
                ]);
            }
        } else {
            return response()->json([
                'status'    => 0,
                'message'   => 'Hội đồng không tồn tại!',
            ]);
        }
    }

    public function xoaHoiDong(Request $request)
    {
        $hoi_dong = HoiDong::find($request->id);
        if ($hoi_dong) {
            $hoi_dong->delete();

            return response()->json([
                'status'    => 1,
                'message'   => 'Đã xóa hội đồng ' . $hoi_dong->ten_hoi_dong . ' thành công!',
            ]);
        } else {
            return response()->json([
                'status'    => 0,
                'message'   => 'Hội đồng không tồn tại!',
            ]);
        }

    }

    public function updateNhom(Request $request)
    {
        $hoi_dong = HoiDong::where('id', $request->id)->first();
        $array = explode(',', $hoi_dong->list_id_hoi_dong);
        $string =  "";
        $data = $request->all();
        $array_list_nhom = explode(',', $hoi_dong->list_ma_nhom);
        if ($hoi_dong) {
            foreach ($request->list_nhom as $key => $value) {
                if(isset($value["check"]) && $value["check"] == true) {
                    $giang_vien = Nhom::where('ma_nhom', $value["ma_nhom"])->first();
                    $id_giang_vien = $giang_vien->id_giang_vien;
                    $ten_giang_vien = Nhom::where('ma_nhom', $value["ma_nhom"])->join('giang_viens', 'giang_viens.id', 'nhoms.id_giang_vien')->select('giang_viens.ten_giang_vien', 'nhoms.ten_nhom')->first();
                    if(in_array($value["ma_nhom"], $array_list_nhom)) {
                        return response()->json([
                            'status'    => 0,
                            'message'   => "Nhóm " .$value["ten_nhom"]. " đã được phân công!!",
                        ]);
                    }

                    if (in_array($id_giang_vien, $array)) {
                        return response()->json([
                            'status'    => 0,
                            'message'   => 'Giảng viên ' . $ten_giang_vien->ten_giang_vien . " đang hướng dẫn lớp " . $ten_giang_vien->ten_nhom . " vui lòng chọn lại!",
                        ]);
                    } else {
                        $string .= $value["ma_nhom"] . ",";
                    }
                }
            }

            $data['list_ma_nhom'] = trim($string, ',');
            $hoi_dong->update($data);
            return response()->json([
                'status'    => 1,
                'message'   => 'Đã phân nhóm thành công!',
            ]);
        } else {
            return response()->json([
                'status'    => 0,
                'message'   => 'Hội đồng không tồn tại!',
            ]);
        }
    }
}
