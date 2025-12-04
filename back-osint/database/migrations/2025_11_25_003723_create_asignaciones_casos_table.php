<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAsignacionesCasosTable extends Migration
{
    public function up()
    {
        Schema::create('asignaciones_casos', function (Blueprint $table) {
            $table->id('id_asignacion');
            $table->unsignedBigInteger('id_usuario');
            $table->unsignedBigInteger('id_caso');
            $table->timestampTz('fecha_asignacion')->useCurrent();
            
            $table->foreign('id_usuario')->references('id_usuario')->on('usuarios');
            $table->foreign('id_caso')->references('id_caso')->on('casos');
        });
    }

    public function down()
    {
        Schema::dropIfExists('asignaciones_casos');
    }
}
