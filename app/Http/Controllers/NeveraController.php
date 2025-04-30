<?php

namespace App\Http\Controllers;

use App\Models\Ingrediente;
use App\Models\IngredienteNevera;
use Illuminate\Http\Request;

class NeveraController extends Controller
{
    public function index()
    {
        $ingrendientes = Ingrediente::all();
        $ingredientes_nevera = IngredienteNevera::with('ingrediente')->get();
        return view('nevera.index', compact('ingrendientes', 'ingredientes_nevera'));
    }

    public function create(Request $request)
    {
        $ingrediente_nevera = new IngredienteNevera();
        // if (is_numeric($ingrediente['nombre']) && ctype_digit(strval($ingrediente['nombre']))) {

        $ingrediente_id = null;
        if (is_numeric($request->ingrediente_id) && ctype_digit(strval($request->ingrediente_id))) {
            $ingrediente_id = $request->ingrediente_id;
        }else{
            // Si el ingrediente no existe, crearlo
            $nuevoIngrediente = new Ingrediente();
            $nuevoIngrediente->nombre = $request->ingrediente_id;
            $nuevoIngrediente->save();
            $ingrediente_id = $nuevoIngrediente->id;
        }
        $ingrediente_nevera->ingrediente_id = $ingrediente_id;
        $ingrediente_nevera->cantidad = $request->cantidad;
        $ingrediente_nevera->medida = $request->medida;
        $ingrediente_nevera->fecha_caducidad = $request->caducidad;
        $ingrediente_nevera->save();
        return redirect()->route('nevera.index')->with('success', 'Ingrediente añadido a la nevera');
    }

    public function update(Request $request)
    {
        $ingrediente_nevera = IngredienteNevera::find($request->ingrediente_nevera_id);
        if ($ingrediente_nevera) {
            $ingrediente_nevera->cantidad = $request->cantidad;
            $ingrediente_nevera->medida = $request->medida;
            $ingrediente_nevera->fecha_caducidad = $request->caducidad;
            $ingrediente_nevera->save();
            return redirect()->route('nevera.index')->with('success', 'Ingrediente actualizado en la nevera');
        } else {
            return redirect()->route('nevera.index')->with('error', 'Ingrediente no encontrado en la nevera');
        }
    }

    public function destroy(Request $request)
    {
        $ingrediente_nevera = IngredienteNevera::find($request->id);
        if ($ingrediente_nevera) {
            $ingrediente_nevera->delete();
            return response()->json(['success' => 'Ingrediente eliminado de la nevera']);
        } else {
            return response()->json(['error' => 'Ingrediente no encontrado en la nevera']);
        }
    }
}
