<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Herramienta extends Model
{
    protected $table = 'herramientas';
    protected $primaryKey = 'id_herramienta';

    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'link',
        'id_categoria'
    ];

    public function categoria()
    {
        return $this->belongsTo(CategoriaHerramienta::class, 'id_categoria');
    }
}