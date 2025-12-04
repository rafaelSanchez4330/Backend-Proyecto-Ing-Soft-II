<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatbotWhatsappTable extends Migration
{
    public function up()
    {
        Schema::create('chatbot_whatsapp', function (Blueprint $table) {
            $table->string('whatsapp_id', 50)->primary();
            $table->unsignedBigInteger('user_id');
            $table->timestampTz('linked_at')->useCurrent();
            
            $table->foreign('user_id')->references('id_usuario')->on('usuarios');
        });
    }

    public function down()
    {
        Schema::dropIfExists('chatbot_whatsapp');
    }
}
