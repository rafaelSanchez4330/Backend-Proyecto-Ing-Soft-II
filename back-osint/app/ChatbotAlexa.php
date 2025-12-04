<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChatbotAlexa extends Model
{
    protected $table = 'chatbot_alexa';
    protected $primaryKey = 'alexa_user_id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
    
    protected $fillable = ['alexa_user_id', 'user_id', 'preferences'];
    
    protected $casts = [
        'linked_at' => 'datetime',
    ];
    
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'user_id', 'id_usuario');
    }
}
