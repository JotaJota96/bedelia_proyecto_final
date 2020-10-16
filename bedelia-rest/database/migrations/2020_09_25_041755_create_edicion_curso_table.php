<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEdicionCursoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edicion_curso', function (Blueprint $table) {
            $table->id();
            $table->foreignId('curso_id');
            $table->foreignId('sede_id');
            $table->foreignId('periodo_lectivo_id');
            $table->boolean('acta_confirmada');
            $table->foreignId('docente_id')->nullable();
            $table->timestamps();
            
            $table->unique(['curso_id', 'sede_id', 'periodo_lectivo_id']);
            $table->foreign('curso_id')->references('id')->on('curso');
            $table->foreign('sede_id')->references('id')->on('sede');
            $table->foreign('periodo_lectivo_id')->references('id')->on('periodo_lectivo');
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
        Schema::dropIfExists('edicion_curso');
    }
}
