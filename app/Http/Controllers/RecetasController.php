<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Ingrediente;
use App\Models\Receta;
use Illuminate\Http\Request;

class RecetasController extends Controller
{
    public function index()
    {
        $recetas = Receta::all();
        return view('recetas.index', compact('recetas'));
    }

    public function create()
    {
        $categorias = Categoria::all();
        $ingredientes = Ingrediente::all();
        return view('recetas.create', compact('categorias', 'ingredientes'));
    }

    public function edit($id)
    {
        $receta = Receta::findOrFail($id);
        $categorias = Categoria::all();
        $ingredientes = Ingrediente::all();
        return view('recetas.create', compact('receta', 'categorias', 'ingredientes'));
    }

    public function update(Request $request, $id)
    {
        $receta = Receta::findOrFail($id);
        $receta->nombre = $request->nombre;
        $receta->tipo = $request->tipo;
        $receta->duracion = $request->duracion;
        $receta->instrucciones = $request->instrucciones;
        if ($request->hasFile('imagen')) {
            $receta->imagen = $request->file('imagen')->store('recetasimg', 'public');
        }
        $receta->align_selected = $request->align_selected;

        $receta->save();

        // Actualizar ingredientes
        foreach ($request->ingredientes as $ingrediente) {
            if (is_numeric($ingrediente['nombre']) && ctype_digit(strval($ingrediente['nombre']))) {
                $receta->ingredientes()->updateExistingPivot($ingrediente['nombre'], [
                    'medida' => $ingrediente['medida'],
                    'cantidad' => $ingrediente['cantidad']
                ]);
            } else {
                // Si el ingrediente no existe, crearlo
                $nuevoIngrediente = new Ingrediente();
                $nuevoIngrediente->nombre = $ingrediente['nombre'];
                $nuevoIngrediente->save();

                // Asociar el nuevo ingrediente a la receta
                $receta->ingredientes()->attach($nuevoIngrediente->id, [
                    'medida' => $ingrediente['medida'],
                    'cantidad' => $ingrediente['cantidad']
                ]);
            }
        }

        return redirect()->route('recetas.index')->with('success', 'Receta actualizada con éxito.');
    }

    public function store(Request $request){
        $receta = new Receta();
        $receta->nombre = $request->nombre;
        $receta->tipo = $request->tipo;
        $receta->duracion = $request->duracion;
        $receta->instrucciones = $request->instrucciones;
        if ($request->hasFile('imagen')) {
            $receta->imagen = $request->file('imagen')->store('recetasimg', 'public');
        }
        $receta->align_selected = $request->align_selected;
        $receta->save();

        foreach ($request->ingredientes as $ingrediente) {
            if (is_numeric($ingrediente['nombre']) && ctype_digit(strval($ingrediente['nombre']))) {
                $receta->ingredientes()->attach($ingrediente['nombre'], [
                    'medida' => $ingrediente['medida'],
                    'cantidad' => $ingrediente['cantidad']
                ]);
            } else {
                $nuevoIngrediente = new Ingrediente();
                $nuevoIngrediente->nombre = $ingrediente['nombre'];
                $nuevoIngrediente->save();

                $receta->ingredientes()->attach($nuevoIngrediente->id, [
                    'medida' => $ingrediente['medida'],
                    'cantidad' => $ingrediente['cantidad']
                ]);
            }
        }


        return redirect()->route('recetas.index')->with('success', 'Receta creada con éxito.');

        
    }
}
