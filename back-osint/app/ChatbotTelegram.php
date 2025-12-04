<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChatbotTelegram extends Model
{
    protected $table = 'chatbot_telegram';
    protected $primaryKey = 'telegram_user_id';
    public $incrementing = false;
    public $timestamps = false;
    
    protected $fillable = ['telegram_user_id', 'user_id'];
    
    protected $casts = [
        'linked_at' => 'datetime',
    ];
    
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'user_id', 'id_usuario');
    }
}
