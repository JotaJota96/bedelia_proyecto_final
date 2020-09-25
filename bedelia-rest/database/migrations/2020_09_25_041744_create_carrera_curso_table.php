<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarreraCursoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carrera_curso', function (Blueprint $table) {
            $table->foreignId('carrera_id');
            $table->foreignId('curso_id');
            $table->string('semestre');
            $table->boolean('optativo');
            $table->timestamps();

            $table->primary(['carrera_id', 'curso_id']);
            $table->foreign('carrera_id')->references('id')->on('carrera');
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
        Schema::dropIfExists('carrera_curso');
    }
}
