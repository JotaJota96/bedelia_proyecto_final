<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAsistenciaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asistencia', function (Blueprint $table) {
            $table->foreignId('clase_dictada_id');
            $table->foreignId('estudiante_id');
            $table->float('asistencia', 2, 1);
            $table->timestamps();

            $table->primary(['clase_dictada_id', 'estudiante_id']);
            $table->foreign('clase_dictada_id')->references('id')->on('clase_dictada');
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
        Schema::dropIfExists('asistencia');
    }
}
