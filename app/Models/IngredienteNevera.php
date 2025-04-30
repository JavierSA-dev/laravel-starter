<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IngredienteNevera extends Model
{
    use HasFactory;
    protected $table = 'nevera';

    protected $fillable = [
        'ingrediente_id',
        'cantidad',
        'medida',
        'fecha_caducidad',
    ];

    public function ingrediente()
    {
        return $this->belongsTo(Ingrediente::class);
    }


}   
