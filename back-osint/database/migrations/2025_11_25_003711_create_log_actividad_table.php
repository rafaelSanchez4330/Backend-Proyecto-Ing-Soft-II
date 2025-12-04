<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogActividadTable extends Migration
{
    public function up()
    {
        Schema::create('log_actividad', function (Blueprint $table) {
            $table->id('id_log');
            $table->unsignedBigInteger('id_usuario');
            $table->timestampTz('fecha_hora')->useCurrent();
            $table->string('tipo_accion', 100);
            $table->text('descripcion');
            $table->unsignedBigInteger('caso_id_relacionado')->nullable();
            
            $table->foreign('id_usuario')->references('id_usuario')->on('usuarios');
            $table->foreign('caso_id_relacionado')->references('id_caso')->on('casos');
        });
    }

    public function down()
    {
        Schema::dropIfExists('log_actividad');
    }
}
