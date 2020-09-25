<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarreraAreaEstudioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carrera_area_estudio', function (Blueprint $table) {
            $table->foreignId('carrera_id');
            $table->foreignId('area_estudio_id');
            $table->integer('creditos');
            $table->timestamps();

            $table->primary(['carrera_id', 'area_estudio_id']);
            $table->foreign('carrera_id')->references('id')->on('carrera');
            $table->foreign('area_estudio_id')->references('id')->on('area_estudio');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('carrera_area_estudio');
    }
}
