<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePeriodoInscExamenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('periodo_insc_examen', function (Blueprint $table) {
            $table->foreignId('id');
            $table->timestamps();
            
            $table->primary('id');
            $table->foreign('id')->references('id')->on('periodo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('periodo_insc_examen');
    }
}
