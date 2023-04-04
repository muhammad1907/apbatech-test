<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAntriansoalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('antriansoal', function (Blueprint $table) {
            $table->string('nomorantrean')->nullable();
            $table->integer('angkaantrean')->nullable();
            $table->string('norm')->nullable();
            $table->string('namapoli')->nullable();
            $table->string('kodepoli')->nullable();
            $table->date('tglpriksa')->nullable();
            $table->string('nomorkartu')->primary();
            $table->string('nik')->nullable();
            $table->string('keluhan');
            $table->integer('statusdipanggil')->default(0);
            $table->bigInteger('id')->unsigned();
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
        Schema::dropIfExists('antriansoal');
    }
}
