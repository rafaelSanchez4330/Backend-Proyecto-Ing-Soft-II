<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSoftDeletesToTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Agregar soft deletes a usuarios
        Schema::table('usuarios', function (Blueprint $table) {
            $table->softDeletes();
        });

        // Agregar soft deletes a casos
        Schema::table('casos', function (Blueprint $table) {
            $table->softDeletes();
        });

        // Agregar soft deletes a herramientas
        Schema::table('herramientas', function (Blueprint $table) {
            $table->softDeletes();
        });

        // Agregar soft deletes a categorias_herramientas
        Schema::table('categorias_herramientas', function (Blueprint $table) {
            $table->softDeletes();
        });

        // Agregar soft deletes a evidencias
        Schema::table('evidencias', function (Blueprint $table) {
            $table->softDeletes();
        });

        // Agregar soft deletes a actividades_caso
        Schema::table('actividades_caso', function (Blueprint $table) {
            $table->softDeletes();
        });

        // Agregar soft deletes a asignaciones_casos
        Schema::table('asignaciones_casos', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('casos', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('herramientas', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('categorias_herramientas', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('evidencias', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('actividades_caso', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('asignaciones_casos', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
}
