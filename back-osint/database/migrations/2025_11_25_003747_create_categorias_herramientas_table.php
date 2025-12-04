<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriasHerramientasTable extends Migration
{
    public function up()
    {
        Schema::create('categorias_herramientas', function (Blueprint $table) {
            $table->id('id_categoria');
            $table->string('nombre', 100);
        });
    }

    public function down()
    {
        Schema::dropIfExists('categorias_herramientas');
    }
}
