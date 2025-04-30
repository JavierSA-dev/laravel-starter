<?php

namespace App\Http\Controllers;

use App\Models\Ingrediente;
use Illuminate\Http\Request;

class IngredientesController extends Controller
{

    public function index()
    {
        $ingredientes = Ingrediente::all();
        return view('ingredientes.index', compact('ingredientes'));
    }

    public function create(Request $request)
    {
        $ingrediente = new Ingrediente();
        $ingrediente->nombre = $request->input('nombre');
        $ingrediente->save();

        return redirect()->route('ingredientes.index')->with('success', 'Ingrediente creado con éxito.');
    }

    public function update(Request $request)
    {
        $ingrediente = Ingrediente::findOrFail($request->input('ingrediente_id'));
        $ingrediente->nombre = $request->input('nombre');
        $ingrediente->save();

        return redirect()->route('ingredientes.index')->with('success', 'Ingrediente actualizado con éxito.');
    }

    public function destroy($id)
    {
        $ingrediente = Ingrediente::findOrFail($id);
        $ingrediente->delete();

        return redirect()->route('ingredientes.index')->with('success', 'Ingrediente eliminado con éxito.');
    }



}
