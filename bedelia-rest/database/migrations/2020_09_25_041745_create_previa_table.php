<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePreviaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('previa', function (Blueprint $table) {
            $table->foreignId('carrera_id');
            $table->foreignId('curso_id');
            $table->foreignId('carrera_id_previa');
            $table->foreignId('curso_id_previa');
            $table->enum('tipo', ['curso', 'examen']);
            $table->timestamps();

            $table->primary(['carrera_id', 'curso_id', 'carrera_id_previa', 'curso_id_previa'], 'previa_primary');
            $table->foreign(['carrera_id', 'curso_id'])->references(['carrera_id', 'curso_id'])->on('carrera_curso');
            $table->foreign(['carrera_id_previa', 'curso_id_previa'])->references(['carrera_id', 'curso_id'])->on('carrera_curso');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('previa');
    }
}