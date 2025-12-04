<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsuariosTable extends Migration
{
    public function up()
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id('id_usuario');
            $table->string('nombre', 100);
            $table->string('usuario', 50)->unique();
            $table->string('mail', 100)->unique();
            $table->string('contrasena', 255);
            $table->enum('rol', ['admin', 'capturista', 'consultor']);
            $table->boolean('activo')->default(true);
            $table->timestampTz('fecha_creacion')->useCurrent();
            $table->timestampTz('fecha_actualizacion')->nullable();
            $table->timestampTz('ultima_conexion')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('usuarios');
    }
}
