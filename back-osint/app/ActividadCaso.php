<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ActividadCaso extends Model
{
    use SoftDeletes;

    protected $table = 'actividades_caso';
    protected $primaryKey = 'id_actividad';
    public $timestamps = false;
    
    protected $fillable = ['id_caso', 'actividad'];
    
    protected $casts = [
        'fecha' => 'datetime',
    ];
    
    protected $dates = ['deleted_at'];
    
    public function caso()
    {
        return $this->belongsTo(Caso::class, 'id_caso', 'id_caso');
    }
}
