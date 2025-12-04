<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LogActividad extends Model
{
    protected $table = 'log_actividad';
    protected $primaryKey = 'id_log';
    public $timestamps = false;
    
    protected $fillable = [
        'id_usuario', 'tipo_accion', 'descripcion', 'caso_id_relacionado'
    ];
    
    protected $casts = [
        'fecha_hora' => 'datetime',
    ];
    
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }
    
    public function caso()
    {
        return $this->belongsTo(Caso::class, 'caso_id_relacionado', 'id_caso');
    }
}
