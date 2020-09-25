<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInscripcionCursoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inscripcion_curso', function (Blueprint $table) {
            $table->foreignId('edicion_curso_id');
            $table->foreignId('estudiante_id');
            $table->decimal('nota', 3, 2);
            $table->timestamps();

            $table->primary(['edicion_curso_id', 'estudiante_id']);
            $table->foreign('edicion_curso_id')->references('id')->on('edicion_curso');
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
        Schema::dropIfExists('inscripcion_curso');
    }
}
