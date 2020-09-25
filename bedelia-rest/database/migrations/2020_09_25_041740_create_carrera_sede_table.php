<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarreraSedeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carrera_sede', function (Blueprint $table) {
            $table->foreignId('carrera_id');
            $table->foreignId('sede_id');
            $table->timestamps();

            $table->primary(['carrera_id', 'sede_id']);
            $table->foreign('carrera_id')->references('id')->on('carrera');
            $table->foreign('sede_id')->references('id')->on('sede');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('carrera_sede');
    }
}
