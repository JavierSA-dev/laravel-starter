<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receta extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'duracion',
        'tipo',
        'instrucciones',
        'imagen',
        'align_selected'
    ];

    public function ingredientes()
    {
        return $this->belongsToMany(Ingrediente::class, 'receta_ingredientes');
    }

    // receta_ingredientes
    public function recetaIngredientes()
    {
        return $this->hasMany(RecetaIngredientes::class);
    }

    public function categorias()
    {
        return $this->belongsToMany(Categoria::class);
    }

    public function comprobarIngredientes()
    {
        $ingredientes = $this->recetaIngredientes()->get();
        $nevera = IngredienteNevera::all();
        // array [ingrediente_id, cantidad ]
        $nevera_arr = [];
        foreach ($nevera as $item) {
            if (isset($nevera_arr[$item->ingrediente_id])) {
                $nevera_arr[$item->ingrediente_id] += $item->cantidad;
            } else {
                $nevera_arr[$item->ingrediente_id] = (float)$item->cantidad;
            }
        }

        $ingredientes_arr = [];
        foreach ($ingredientes as $item) {
            if (isset($ingredientes_arr[$item->ingrediente_id])) {
                $ingredientes_arr[$item->ingrediente_id] += $item->cantidad;
            } else {
                $ingredientes_arr[$item->ingrediente_id] = (float)$item->cantidad;
            }
        }

        // order
        ksort($ingredientes_arr);
        ksort($nevera_arr);
        // numero de ingredientes  que tengo en la nevera
        $tengo_ingredientes = 0;
        foreach ($ingredientes_arr as $ingrediente_id => $cantidad) {
            if (isset($nevera_arr[$ingrediente_id])) {
                if ($nevera_arr[$ingrediente_id] >= $cantidad) {
                    $tengo_ingredientes++;
                }
            }            
        }

        if ($tengo_ingredientes == count($ingredientes_arr)) {
            return 1;
        }elseif ($tengo_ingredientes == 0) {
            return 0;
        } else {
            return 2;
        }


    }

    public function ingredientesTengo(){
        // devolver un array con los ingredientes que tengo y la cantidad, por ejemplo si en la nevera tengo 2 huevos y en la receta necesito 3 huevos, devolver 2
        $ingredientes = $this->recetaIngredientes()->get();
        $nevera = IngredienteNevera::all();
        // array [ingrediente_id, cantidad ]
        $nevera_arr = [];
        foreach ($nevera as $item) {
            if (isset($nevera_arr[$item->ingrediente_id])) {
                $nevera_arr[$item->ingrediente_id] += $item->cantidad;
            } else {
                $nevera_arr[$item->ingrediente_id] = (float)$item->cantidad;
            }
        }
        $ingredientes_arr = [];
        foreach ($ingredientes as $item) {
            if (isset($ingredientes_arr[$item->ingrediente_id])) {
                $ingredientes_arr[$item->ingrediente_id] += $item->cantidad;
            } else {
                $ingredientes_arr[$item->ingrediente_id] = (float)$item->cantidad;
            }
        }
        // order
        ksort($ingredientes_arr);
        ksort($nevera_arr);
        // numero de ingredientes  que tengo en la nevera
        $tengo_ingredientes = 0;
        $ingredientes_tengo = [];

        foreach ($ingredientes_arr as $ingrediente_id => $cantidad) {
            if (isset($nevera_arr[$ingrediente_id])) {
                if ($nevera_arr[$ingrediente_id] >= $cantidad) {
                    $tengo_ingredientes++;
                    $ingredientes_tengo[$ingrediente_id] = $nevera_arr[$ingrediente_id];
                } else {
                    $ingredientes_tengo[$ingrediente_id] = $nevera_arr[$ingrediente_id];
                }
            }            
        }
        $arrayToReturn = [];
        // intercambia el ingrediente_id por el nombre del ingrediente
        foreach ($ingredientes_tengo as $ingrediente_id => $cantidad) {
            $ingrediente = Ingrediente::find($ingrediente_id);
            if ($ingrediente) {
                $arrayToReturn[$ingrediente->nombre] = $cantidad;
            }
        }
        // ordena el array por nombre
        ksort($arrayToReturn);

        return $arrayToReturn;
    }
    public function ingredientesFaltan(){
        // devolver un array con los ingredientes que me faltan y la cantidad, por ejemplo si en la nevera tengo 2 huevos y en la receta necesito 3 huevos, devolver 1
        $ingredientes = $this->recetaIngredientes()->get();
        $nevera = IngredienteNevera::all();
        // array [ingrediente_id, cantidad ]
        $nevera_arr = [];
        foreach ($nevera as $item) {
            if (isset($nevera_arr[$item->ingrediente_id])) {
                $nevera_arr[$item->ingrediente_id] += $item->cantidad;
            } else {
                $nevera_arr[$item->ingrediente_id] = (float)$item->cantidad;
            }
        }
        $ingredientes_arr = [];
        foreach ($ingredientes as $item) {
            if (isset($ingredientes_arr[$item->ingrediente_id])) {
                $ingredientes_arr[$item->ingrediente_id] += $item->cantidad;
            } else {
                $ingredientes_arr[$item->ingrediente_id] = (float)$item->cantidad;
            }
        }
        // order
        ksort($ingredientes_arr);
        ksort($nevera_arr);
        // numero de ingredientes  que tengo en la nevera
        $tengo_ingredientes = 0;
        $ingredientes_faltan = [];

        foreach ($ingredientes_arr as $ingrediente_id => $cantidad) {
            if (isset($nevera_arr[$ingrediente_id])) {
                if ($nevera_arr[$ingrediente_id] >= $cantidad) {
                    $tengo_ingredientes++;
                } else {
                    $diferencia = abs($nevera_arr[$ingrediente_id] - $cantidad);
                    if ($diferencia > 0) {
                        $ingredientes_faltan[$ingrediente_id] = $diferencia;
                    }
                }
            } else {
                // no tengo el ingrediente en la nevera
                if ($cantidad > 0) {
                    $ingredientes_faltan[$ingrediente_id] = abs($cantidad);
                }
            }            
        }
        // intercambia el ingrediente_id por el nombre del ingrediente
        $arrayToReturn = [];
        foreach ($ingredientes_faltan as $ingrediente_id => $cantidad) {
            $ingrediente = Ingrediente::find($ingrediente_id);
            if($ingrediente) {
                $arrayToReturn[$ingrediente->nombre] = $cantidad;
            }
        }
        return $arrayToReturn;
    }
}
