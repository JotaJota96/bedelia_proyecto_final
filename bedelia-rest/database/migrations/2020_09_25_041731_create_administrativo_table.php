<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdministrativoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('administrativo', function (Blueprint $table) {
            $table->foreignId('id');
            $table->foreignId('sede_id')->nullable();
            $table->timestamps();
            
            $table->primary('id');
            $table->foreign('id')->references('id')->on('usuario');
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
        Schema::dropIfExists('administrativo');
    }
}
