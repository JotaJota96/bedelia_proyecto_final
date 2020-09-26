<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostulacionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('postulacion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('carrera_id');
            $table->foreignId('sede_id');
            $table->foreignId('persona_id');
            $table->text('img_ci');
            $table->text('img_escolaridad');
            $table->text('img_carne_salud');
            $table->timestamps();

            $table->unique(['carrera_id', 'persona_id']);
            $table->foreign('carrera_id')->references('id')->on('carrera');
            $table->foreign('sede_id')->references('id')->on('sede');
            $table->foreign('persona_id')->references('id')->on('persona');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('postulacion');
    }
}
