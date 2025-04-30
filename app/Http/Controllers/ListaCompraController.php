<?php

namespace App\Http\Controllers;

use App\Models\Ingrediente;
use App\Models\IngredienteListaCompra;
use Illuminate\Http\Request;

class ListaCompraController extends Controller
{
    public function index()
    {
        // Obtener la lista de ingredientes de la compra
        $listaCompra = IngredienteListaCompra::with('ingrediente')->get();
        $ingredientes = Ingrediente::all();
        return view('lista_compra.index', compact('listaCompra', 'ingredientes'));
    }

    public function create(Request $request)
    {
        // Crear un nuevo ingrediente en la lista de la compra
        IngredienteListaCompra::create([
            'ingrediente_id' => $request->ingrediente_id,
            'cantidad' => $request->cantidad,
        ]);

        return redirect()->route('lista_compra.index')->with('success', 'Ingrediente añadido a la lista de la compra.');
    }

    public function update(Request $request)
    {
        // Actualizar el ingrediente en la lista de la compra
        $ingredienteListaCompra = IngredienteListaCompra::findOrFail($request->lista_compra_id);
        $ingredienteListaCompra->update([
            'ingrediente_id' => $request->ingrediente_id,
            'cantidad' => $request->cantidad,
        ]);

        return redirect()->route('lista_compra.index')->with('success', 'Ingrediente actualizado correctamente.');
    }

    public function destroy($id)
    {
        // Eliminar el ingrediente de la lista de la compra
        $ingredienteListaCompra = IngredienteListaCompra::findOrFail($id);
        $ingredienteListaCompra->delete();

        return response()->json(['success' => 'Ingrediente eliminado correctamente.']);
    }

    public function addCart(Request $request)
    {
        $ingredienteObjeto = Ingrediente::where('nombre', $request->ingrediente)->first();
        $cantidad = $request->cantidad;
        $ingredienteListaCompra = IngredienteListaCompra::where('ingrediente_id', $ingredienteObjeto->id)->first();
        if ($ingredienteListaCompra) {
            $ingredienteListaCompra->cantidad = $ingredienteListaCompra->cantidad + abs($ingredienteListaCompra->cantidad - $cantidad);
            $ingredienteListaCompra->save();
        } else {
            IngredienteListaCompra::create([
                'ingrediente_id' => $ingredienteObjeto->id,
                'cantidad' => $cantidad,
            ]);
        }
        // Actualizar la cantidad del ingrediente en la lista de la compra
        return true;
    }
}