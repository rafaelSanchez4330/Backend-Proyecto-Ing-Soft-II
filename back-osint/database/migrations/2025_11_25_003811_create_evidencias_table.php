<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEvidenciasTable extends Migration
{
    public function up()
    {
        Schema::create('evidencias', function (Blueprint $table) {
            $table->id('id_evidencia');
            $table->unsignedBigInteger('id_caso');
            $table->string('tipo', 50)->nullable();
            $table->text('descripcion')->nullable();
            $table->timestampTz('fecha_creacion')->useCurrent();
            
            $table->foreign('id_caso')->references('id_caso')->on('casos');
        });
    }

    public function down()
    {
        Schema::dropIfExists('evidencias');
    }
}
