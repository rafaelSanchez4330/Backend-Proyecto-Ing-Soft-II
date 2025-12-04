<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCasosTable extends Migration
{
    public function up()
    {
        Schema::create('casos', function (Blueprint $table) {
            $table->id('id_caso');
            $table->string('estado', 50);
            $table->string('nombre', 255);
            $table->string('tipo_caso', 255);
            $table->text('descripcion');
            $table->timestampTz('fecha_creacion')->useCurrent();
            $table->timestampTz('fecha_actualizacion')->nullable();
            $table->unsignedBigInteger('id_creador');
            
            $table->foreign('id_creador')->references('id_usuario')->on('usuarios');
        });
    }

    public function down()
    {
        Schema::dropIfExists('casos');
    }
}
