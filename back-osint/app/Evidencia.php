<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Evidencia extends Model
{
    use SoftDeletes;

    protected $table = 'evidencias';
    protected $primaryKey = 'id_evidencia';
    public $timestamps = false;
    
    protected $fillable = ['id_caso', 'tipo', 'descripcion'];
    
    protected $casts = [
        'fecha_creacion' => 'datetime',
    ];
    
    protected $dates = ['deleted_at'];
    
    public function caso()
    {
        return $this->belongsTo(Caso::class, 'id_caso', 'id_caso');
    }
}
