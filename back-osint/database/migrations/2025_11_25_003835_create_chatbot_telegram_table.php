<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatbotTelegramTable extends Migration
{
    public function up()
    {
        Schema::create('chatbot_telegram', function (Blueprint $table) {
            $table->bigInteger('telegram_user_id')->primary();
            $table->unsignedBigInteger('user_id');
            $table->timestampTz('linked_at')->useCurrent();
            
            $table->foreign('user_id')->references('id_usuario')->on('usuarios');
        });
    }

    public function down()
    {
        Schema::dropIfExists('chatbot_telegram');
    }
}
