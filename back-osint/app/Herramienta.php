<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Herramienta extends Model
{
    use SoftDeletes;

    protected $table = 'herramientas';
    protected $primaryKey = 'id_herramienta';
    public $timestamps = false;
    
    protected $fillable = ['nombre', 'enlace'];
    
    protected $dates = ['deleted_at'];
    
    public function categorias()
    {
        return $this->belongsToMany(
            CategoriaHerramienta::class,
            'rel_herramientas_categorias',
            'id_herramienta',
            'id_categoria'
        );
    }
}
