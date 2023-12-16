<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hoi_dongs', function (Blueprint $table) {
            $table->id();
            $table->string('ten_hoi_dong');
            $table->string('id_chu_tich');
            $table->string('list_id_hoi_dong');
            $table->string('id_thu_ky');
            $table->string('id_uy_vien');
            $table->date('thoi_gian');
            $table->string('list_ma_nhom')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hoi_dongs');
    }
};
