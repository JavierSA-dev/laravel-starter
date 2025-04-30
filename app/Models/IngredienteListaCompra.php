<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IngredienteListaCompra extends Model
{
    use HasFactory;

    protected $table = 'lista_compra';


    protected $fillable = [
        'ingrediente_id',
        'cantidad',
    ];

    public function ingrediente()
    {
        return $this->belongsTo(Ingrediente::class);
    }

}
