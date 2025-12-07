<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoriaHerramienta extends Model
{
    protected $table = 'categorias_herramientas';
    protected $primaryKey = 'id_categoria';

    public $timestamps = false;

    protected $fillable = ['nombre'];

    public function herramientas()
    {
        return $this->hasMany(Herramienta::class, 'id_categoria');
    }
}
