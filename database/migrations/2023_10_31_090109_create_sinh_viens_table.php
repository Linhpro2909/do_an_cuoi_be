<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::create('sinh_viens', function (Blueprint $table) {
            $table->id();
            $table->string('ten_sinh_vien');
            $table->string('ma_sinh_vien');
            $table->integer('so_dien_thoai');
            $table->float('diem_gpa');
            $table->integer('tinh_trang');
            $table->integer('id_nien_khoa');
            $table->string('password');
            $table->float('diem_mentor')->default(0);
            $table->float('diem_chu_tich')->default(0);
            $table->float('diem_thu_ky')->default(0);
            $table->float('diem_uy_vien')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sinh_viens');
    }
};
