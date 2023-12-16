<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\KhoaDaoTaoController;
use App\Http\Controllers\KhoaHocController;
use App\Http\Controllers\NienKhoaController;
use App\Http\Controllers\SinhVienController;
use App\Http\Controllers\GiangVienController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CheckLoginController;
use App\Http\Controllers\DeTaiSinhVienController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\HoiDongController;
use App\Http\Controllers\KeHoachController;
use App\Http\Controllers\NhatKyNhomController;
use App\Http\Controllers\NhomController;
use App\Http\Controllers\TienDoController;
use App\Http\Controllers\TmpNhomController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/sinh-vien/login', [SinhVienController::class, 'actionApiLoginSinhVien']);
Route::post('/giang-vien/login', [GiangVienController::class, 'actionApiLoginGiangVien']);
Route::post('/login', [AdminController::class, 'login']);

Route::post('/check-login', [CheckLoginController::class, 'checklogin']); //4

Route::post('/sinh-vien/logout', [CheckLoginController::class, 'Logout']); //4
Route::post('/giang-vien/logout', [GiangVienController::class, 'Logout']); //4
Route::post('/admin/logout', [AdminController::class, 'Logout']); //4

Route::get('/download/{filename}', [FileController::class, 'downloadFile']);
Route::group(['prefix'  => '/admin'], function () {

    Route::group(['prefix'  => '/hoi-dong'], function () {
        Route::get('/data-danh-sach-nhom', [HoiDongController::class, 'getDataDanhSachNhom']);
        Route::get('/data-nhom', [HoiDongController::class, 'getDataNhom']);
        Route::get('/data', [HoiDongController::class, 'getData']);
        Route::post('/create', [HoiDongController::class, 'createDatta']);
        Route::post('/delete-nhom', [HoiDongController::class, 'deleteNhom']);
        Route::post('/chia-nhom', [HoiDongController::class, 'chiaNhom']);
        Route::post('/update-hoi-dong', [HoiDongController::class, 'updateHoiDong']);
        Route::post('/xoa-hoi-dong', [HoiDongController::class, 'xoaHoiDong']);
        Route::post('/update-nhom', [HoiDongController::class, 'updateNhom']);
    });
    Route::group(['prefix'  => '/nien-khoa'], function () {
        Route::get('/data', [NienKhoaController::class, 'getData']);
        Route::post('/create', [NienKhoaController::class, 'createData']);
        Route::post('/update', [NienKhoaController::class, 'updateData']);
        Route::post('/delete', [NienKhoaController::class, 'deleteData']);
        Route::post('/search', [NienKhoaController::class, 'searchData']);
    });
    Route::group(['prefix'  => '/sinh-vien'], function () {
        Route::get('/data', [SinhVienController::class, 'getData']);
        Route::post('/create', [SinhVienController::class, 'createData']);
        Route::post('/update', [SinhVienController::class, 'updateData']);
        Route::post('/delete', [SinhVienController::class, 'deleteData']);
        Route::post('/search', [SinhVienController::class, 'searchData']);
        Route::post('/trang-thai', [SinhVienController::class, 'duyet']);
    });
    Route::group(['prefix' => '/giang-vien'], function () {
        Route::get('/data', [GiangVienController::class, 'getData']);
        Route::post('/create', [GiangVienController::class, 'createData']);
        Route::post('/update', [GiangVienController::class, 'updateData']);
        Route::post('/delete', [GiangVienController::class, 'deleteData']);
        Route::post('/search', [GiangVienController::class, 'searchData']);
    });
    Route::group(['prefix'  => '/de-tai-sinh-vien'], function () {
        Route::get('/data', [DeTaiSinhVienController::class, 'getData']);
        Route::post('/update', [DeTaiSinhVienController::class, 'updateData']);
        Route::post('/delete', [DeTaiSinhVienController::class, 'deleteData']);
        Route::post('/search', [DeTaiSinhVienController::class, 'searchData']);
        Route::post('/trang-thai', [DeTaiSinhVienController::class, 'trangthai']);
        Route::post('/cap-nhat-thoi-gian', [DeTaiSinhVienController::class, 'capNhatTime']);
        Route::post('/trang-thai-1', [DeTaiSinhVienController::class, 'huyDeTai']);
        Route::post('/xoa-de-tai', [DeTaiSinhVienController::class, 'xoaDeTai']);
    });
    Route::group(['prefix' => '/tmp-nhom'], function () {
        Route::get('/data', [TmpNhomController::class, "getData"]);
        Route::post('/create', [TmpNhomController::class, "createData"]);
        Route::post('/delete', [TmpNhomController::class, "deleteData"]);
    });
    Route::group(['prefix' => '/nhom'], function () {
        Route::get('/data', [NhomController::class, "getDataNhom"]);
        Route::get('/data-sinh-vien-nhom', [NhomController::class, "getSinhVienNhom"]);
        Route::post('/create', [NhomController::class, "createData"]);
        Route::post('/thay-doi-sinh-vien', [NhomController::class, "thayDoiSinhVienNhom"]);
        Route::post('/delete', [NhomController::class, "deleteData"]);
    });
    Route::group(['prefix' => '/tien-do'], function () {
        Route::get('/data', [TienDoController::class, "getDataTienDo"]);
        Route::post('/chi-tiet-data', [TienDoController::class, "chiTietNhom"]);

    });
});
Route::group(['prefix' => '/ke-hoach-tot-nghiep'], function () {
    Route::get('/data', [KeHoachController::class, "getKeHoach"]);
    Route::post('/create', [FileController::class, 'uploadFile']);
    Route::post('/update', [FileController::class, 'updateFile']);
    Route::post('/status', [FileController::class, 'statusKeHoach']);
    Route::post('/delete', [FileController::class, 'delete_plan']);

});

Route::group(['prefix' => '/sinh-vien'], function () {
    Route::group(['prefix' => '/nhat-ky'], function () {
        Route::post('/create', [NhatKyNhomController::class, 'createNhatKy']);
        Route::post('/get-data', [NhatKyNhomController::class, 'getData']);
        Route::post('/get-data-nhom-do-an', [NhatKyNhomController::class, 'getNhomDoAn']);
        Route::post('/upload-file', [NhatKyNhomController::class, 'uploadFile']);
        Route::get('/download/{filename}', [NhatKyNhomController::class, 'downloadFile']);
        Route::post('/delete', [NhatKyNhomController::class, 'deleteNhatKy']);
    });
    Route::group(['prefix'  => '/de-tai-sinh-vien'], function () {
        Route::get('/data', [DeTaiSinhVienController::class, 'getData']);
        Route::post('/create', [DeTaiSinhVienController::class, 'createData']);
        Route::post('/edit-de-tai', [DeTaiSinhVienController::class, 'editDeTai']);
    });
});

Route::group(['prefix' => '/giang-vien'], function () {
    Route::group(['prefix' => '/do-an'], function () {
        Route::post('/get-data-nhom-do-an', [GiangVienController::class, 'getNhomDoAn']);
        Route::post('/update-diem-mentor', [GiangVienController::class, 'updateDiemMentor']);
        Route::post('/get-data', [GiangVienController::class, 'getDataChiTiet']);
        Route::get('/download/{filename}', [GiangVienController::class, 'downloadFile']);
    });
    Route::group(['prefix' => '/nhom-do-an'], function () {
        Route::post('/get-data', [GiangVienController::class, 'getNhom']);
        Route::post('/cho-diem', [GiangVienController::class, 'updateDiem']);
    });
});
