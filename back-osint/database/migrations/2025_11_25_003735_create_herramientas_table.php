<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHerramientasTable extends Migration
{
    public function up()
    {
        Schema::create('herramientas', function (Blueprint $table) {
            $table->id('id_herramienta');
            $table->string('nombre', 100);
            $table->text('enlace');
        });
    }

    public function down()
    {
        Schema::dropIfExists('herramientas');
    }
}
