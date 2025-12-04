<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoriaHerramienta extends Model
{
    use SoftDeletes;

    protected $table = 'categorias_herramientas';
    protected $primaryKey = 'id_categoria';
    public $timestamps = false;
    
    protected $fillable = ['nombre'];
    
    protected $dates = ['deleted_at'];
    
    public function herramientas()
    {
        return $this->belongsToMany(
            Herramienta::class,
            'rel_herramientas_categorias',
            'id_categoria',
            'id_herramienta'
        );
    }
}
