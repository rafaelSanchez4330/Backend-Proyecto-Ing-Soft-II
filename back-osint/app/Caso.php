<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Caso extends Model
{
    use SoftDeletes;

    protected $table = 'casos';
    protected $primaryKey = 'id_caso';
    public $timestamps = false;
    
    const CREATED_AT = 'fecha_creacion';
    const UPDATED_AT = 'fecha_actualizacion';
    
    protected $fillable = [
        'estado', 'nombre', 'tipo_caso', 'descripcion', 'id_creador'
    ];
    
    protected $casts = [
        'fecha_creacion' => 'datetime',
        'fecha_actualizacion' => 'datetime',
    ];
    
    protected $dates = ['deleted_at'];
    
    public function creador()
    {
        return $this->belongsTo(Usuario::class, 'id_creador', 'id_usuario');
    }
    
    public function asignaciones()
    {
        return $this->hasMany(AsignacionCaso::class, 'id_caso');
    }
    
    public function evidencias()
    {
        return $this->hasMany(Evidencia::class, 'id_caso');
    }
    
    public function actividades()
    {
        return $this->hasMany(ActividadCaso::class, 'id_caso');
    }
    
    public function logs()
    {
        return $this->hasMany(LogActividad::class, 'caso_id_relacionado');
    }
}
