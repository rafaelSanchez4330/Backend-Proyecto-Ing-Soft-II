<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRelHerramientasCategoriasTable extends Migration
{
    public function up()
    {
        Schema::create('rel_herramientas_categorias', function (Blueprint $table) {
            $table->id('id_relacion');
            $table->unsignedBigInteger('id_herramienta');
            $table->unsignedBigInteger('id_categoria');
            
            $table->foreign('id_herramienta')->references('id_herramienta')->on('herramientas');
            $table->foreign('id_categoria')->references('id_categoria')->on('categorias_herramientas');
        });
    }

    public function down()
    {
        Schema::dropIfExists('rel_herramientas_categorias');
    }
}
