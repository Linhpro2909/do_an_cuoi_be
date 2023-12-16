<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('de_tai_sinh_viens', function (Blueprint $table) {
            $table->id();
            $table->string('ten_de_tai');
            $table->string('mo_ta');
            $table->string('ngon_ngu_lap_trinh');
            $table->integer('tinh_trang')->default(0);
            $table->string('ma_nhom');
            $table->string('ten_nhom');
            $table->date('thoi_gian_ket_thuc')->nullable();
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('de_tai_sinh_viens');
    }
};
