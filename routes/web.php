<?php

use App\Http\Controllers\IngredientesController;
use App\Http\Controllers\ListaCompraController;
use App\Http\Controllers\NeveraController;
use App\Http\Controllers\RecetasController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('index');
})->name('index');

Route::post('/addCart', [ListaCompraController::class, 'addCart']);

Route::prefix('recetas')->group(function () {
    Route::get('/', [RecetasController::class, 'index'])->name('recetas.index');
    Route::get('/crear', [RecetasController::class, 'create'])->name('recetas.create');
    Route::post('/', [RecetasController::class, 'store'])->name('recetas.store');
    Route::get('/{receta}/editar', [RecetasController::class, 'edit'])->name('recetas.edit');
    Route::get('/{receta}', [RecetasController::class, 'show'])->name('recetas.show');
    Route::post('/{receta}', [RecetasController::class, 'update'])->name('recetas.update');
    Route::delete('/{receta}', [RecetasController::class, 'destroy'])->name('recetas.destroy');
})->name('recetas');

Route::prefix('nevera')->group(function () {
    Route::get('/', [NeveraController::class, 'index'])->name('nevera.index');
    Route::post('/create', [NeveraController::class, 'create'])->name('nevera.create');
    Route::post('/update', [NeveraController::class, 'update'])->name('nevera.update');
    Route::post('/delete/{id}', [NeveraController::class, 'destroy'])->name('nevera.delete');
})->name('nevera');

Route::prefix('ingredientes')->group(function () {
    Route::get('/', [IngredientesController::class, 'index'])->name('ingredientes.index');
    Route::post('/create,', [IngredientesController::class, 'create'])->name('ingredientes.create');
    Route::post('/update', [IngredientesController::class, 'update'])->name('ingredientes.update');
    Route::post('/delete/{id}', [IngredientesController::class, 'destroy'])->name('ingredientes.destroy');
})->name('ingredientes');

Route::prefix('lista_compra')->group(function () {
    Route::get('/', [ListaCompraController::class, 'index'])->name('lista_compra.index');
    Route::post('/create', [ListaCompraController::class, 'create'])->name('lista_compra.create');
    Route::post('/update', [ListaCompraController::class, 'update'])->name('lista_compra.update');
    Route::post('/delete/{id}', [ListaCompraController::class, 'destroy'])->name('lista_compra.destroy');
})->name('lista_compra');