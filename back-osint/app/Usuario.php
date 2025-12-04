<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use SoftDeletes, Notifiable;

    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario';
    public $timestamps = false;
    
    const CREATED_AT = 'fecha_creacion';
    const UPDATED_AT = 'fecha_actualizacion';
    
    protected $fillable = [
        'nombre', 'usuario', 'mail', 'contrasena', 'rol', 'activo', 'ultima_conexion'
    ];
    
    protected $hidden = ['contrasena'];
    
    protected $casts = [
        'activo' => 'boolean',
        'fecha_creacion' => 'datetime',
        'fecha_actualizacion' => 'datetime',
        'ultima_conexion' => 'datetime',
    ];
    
    protected $dates = ['deleted_at'];

    public function getAuthPassword()
    {
        return $this->contrasena;
    }
    
    public function casosCreados()
    {
        return $this->hasMany(Caso::class, 'id_creador');
    }
    
    public function asignaciones()
    {
        return $this->hasMany(AsignacionCaso::class, 'id_usuario');
    }
    
    public function logsActividad()
    {
        return $this->hasMany(LogActividad::class, 'id_usuario');
    }
    
    public function chatbotTelegram()
    {
        return $this->hasOne(ChatbotTelegram::class, 'user_id');
    }
    
    public function chatbotAlexa()
    {
        return $this->hasOne(ChatbotAlexa::class, 'user_id');
    }
    
    public function chatbotWhatsapp()
    {
        return $this->hasOne(ChatbotWhatsapp::class, 'user_id');
    }
}
