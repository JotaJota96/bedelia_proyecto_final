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
            $table->id();
            $table->foreignId('carrera_id');
            $table->foreignId('sede_id');
            $table->foreignId('estudiante_id');
            $table->timestamps();

            $table->unique(['carrera_id', 'estudiante_id']);
            $table->foreign('carrera_id')->references('id')->on('carrera');
            $table->foreign('sede_id')->references('id')->on('sede');
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
