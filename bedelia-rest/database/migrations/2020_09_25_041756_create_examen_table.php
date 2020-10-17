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
            $table->foreignId('curso_id');
            $table->foreignId('sede_id');
            $table->foreignId('periodo_examen_id');
            $table->boolean('acta_confirmada')->default(false);
            $table->date('fecha')->nullable();
            $table->foreignId('docente_id')->nullable();
            $table->timestamps();

            $table->unique(['curso_id', 'sede_id', 'periodo_examen_id']);
            $table->foreign('curso_id')->references('id')->on('curso');
            $table->foreign('sede_id')->references('id')->on('sede');
            $table->foreign('periodo_examen_id')->references('id')->on('periodo_examen');
            $table->foreign('docente_id')->references('id')->on('docente');
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
