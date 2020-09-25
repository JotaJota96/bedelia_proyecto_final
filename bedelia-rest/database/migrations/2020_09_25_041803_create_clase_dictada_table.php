<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClaseDictadaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clase_dictada', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->foreignId('edicion_curso_id');
            $table->timestamps();

            $table->foreign('edicion_curso_id')->references('id')->on('edicion_curso');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clase_dictada');
    }
}
