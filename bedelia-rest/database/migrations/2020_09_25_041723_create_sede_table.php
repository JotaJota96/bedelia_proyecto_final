<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSedeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sede', function (Blueprint $table) {
            $table->id();
            $table->string('telefono');
            $table->foreignId('direccion_id');
            $table->timestamps();

            $table->foreign('direccion_id')->references('id')->on('direccion');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sede');
    }
}
