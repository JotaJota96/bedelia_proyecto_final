<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInscripcionExamenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inscripcion_examen', function (Blueprint $table) {
            $table->foreignId('examen_id');
            $table->foreignId('estudiante_id');
            $table->decimal('nota', 3, 2);
            $table->timestamps();

            $table->primary(['examen_id', 'estudiante_id']);
            $table->foreign('examen_id')->references('id')->on('examen');
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
        Schema::dropIfExists('inscripcion_examen');
    }
}
