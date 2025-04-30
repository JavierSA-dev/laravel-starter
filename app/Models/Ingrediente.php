<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingrediente extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre'
    ];

    public function recetas()
    {
        return $this->belongsToMany(Receta::class, 'receta_ingredientes');
    }

    // receta_ingredientes
    public function recetaIngredientes()
    {
        return $this->hasMany(RecetaIngredientes::class);
    }

    public function listaCompra()
    {
        return $this->hasMany(IngredienteListaCompra::class);
    }

}
