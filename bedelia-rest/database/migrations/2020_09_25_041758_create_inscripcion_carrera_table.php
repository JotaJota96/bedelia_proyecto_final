<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInscripcionCarreraTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inscripcion_carrera', function (Blueprint $table) {
            $table->foreignId('carrera_id');
            $table->foreignId('sede_id');
            $table->foreignId('estudiante_id');
            $table->timestamps();

            $table->primary(['carrera_id', 'sede_id', 'estudiante_id']);
            $table->foreign(['carrera_id', 'sede_id'])->references(['carrera_id', 'sede_id'])->on('carrera_sede');
            $table->foreign('estudiante_id')->references('id')->on('estudiante');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inscripcion_carrera');
    }
}
