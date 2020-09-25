<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('examen', function (Blueprint $table) {
            $table->id();
            $table->boolean('acta_confirmada');
            $table->date('fecha');
            $table->foreignId('periodo_examen_id');
            $table->foreignId('docente_id');
            $table->foreignId('curso_id');
            $table->timestamps();

            $table->unique(['periodo_examen_id', 'curso_id']);
            $table->foreign('periodo_examen_id')->references('id')->on('periodo_examen');
            $table->foreign('docente_id')->references('id')->on('docente');
            $table->foreign('curso_id')->references('id')->on('curso');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('examen');
    }
}
