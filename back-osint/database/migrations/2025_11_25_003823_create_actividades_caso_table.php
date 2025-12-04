<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActividadesCasoTable extends Migration
{
    public function up()
    {
        Schema::create('actividades_caso', function (Blueprint $table) {
            $table->id('id_actividad');
            $table->unsignedBigInteger('id_caso');
            $table->text('actividad')->nullable();
            $table->timestampTz('fecha')->useCurrent();
            
            $table->foreign('id_caso')->references('id_caso')->on('casos');
        });
    }

    public function down()
    {
        Schema::dropIfExists('actividades_caso');
    }
}
