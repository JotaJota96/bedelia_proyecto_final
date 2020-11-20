<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCursoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('curso', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('descripcion');
            $table->integer('max_inasistencias');
            $table->integer('cant_creditos');
            $table->integer('cant_clases');
            $table->foreignId('tipo_curso_id');
            $table->foreignId('area_estudio_id');
            $table->timestamps();

            $table->foreign('tipo_curso_id')->references('id')->on('tipo_curso');
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
        Schema::dropIfExists('curso');
    }
}
