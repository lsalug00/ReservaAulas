<?php

namespace App\Http\Controllers\Admin;

use App\Models\Aula;
use App\Models\Categoria;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AulaController extends Controller
{
    public function index()
    {
        return view('admin.aulas.index', [
            'categorias' => Categoria::all()
        ]);
    }

    public function storeCategoria(Request $request)
    {
        $request->validate([
            'nombre' => 'required|unique:categorias,nombre',
        ]);

        Categoria::create($request->only('nombre'));
        return redirect()->back()->with('mensaje', 'Categoría creada con éxito.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'codigo' => 'required|unique:aulas,codigo',
            'nombre' => 'required',
            'capacidad' => 'required|integer|min:1',
            'categoria_id' => 'required|exists:categorias,id',
            'edificio' => 'required',
            'planta' => 'required'
        ]);

        Aula::create($request->all());
        return redirect()->back()->with('mensaje', 'Aula creada con éxito.');
    }
}
