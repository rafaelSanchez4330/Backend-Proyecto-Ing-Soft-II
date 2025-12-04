<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatbotAlexaTable extends Migration
{
    public function up()
    {
        Schema::create('chatbot_alexa', function (Blueprint $table) {
            $table->string('alexa_user_id', 255)->primary();
            $table->unsignedBigInteger('user_id');
            $table->timestampTz('linked_at')->useCurrent();
            $table->text('preferences')->nullable();
            
            $table->foreign('user_id')->references('id_usuario')->on('usuarios');
        });
    }

    public function down()
    {
        Schema::dropIfExists('chatbot_alexa');
    }
}
