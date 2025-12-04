<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChatbotWhatsapp extends Model
{
    protected $table = 'chatbot_whatsapp';
    protected $primaryKey = 'whatsapp_id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
    
    protected $fillable = ['whatsapp_id', 'user_id'];
    
    protected $casts = [
        'linked_at' => 'datetime',
    ];
    
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'user_id', 'id_usuario');
    }
}
