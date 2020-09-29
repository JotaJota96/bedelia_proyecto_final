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
            $table->id();
            $table->foreignId('carrera_id');
            $table->foreignId('curso_id');
            $table->foreignId('curso_id_previa');
            $table->enum('tipo', ['curso', 'examen']);
            $table->timestamps();

            $table->unique(['carrera_id', 'curso_id', 'curso_id_previa'], 'previa_unique');
            $table->foreign('carrera_id')->references('id')->on('carrera');
            $table->foreign('curso_id')->references('id')->on('curso');
            $table->foreign('curso_id_previa')->references('id')->on('curso');
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