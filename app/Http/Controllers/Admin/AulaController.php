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

    public function manage(Request $request)
    {
        $query = Aula::query();

        // Filtros
        if ($request->filled('capacidad')) {
            $query->where('capacidad', '>=', $request->input('capacidad'));
        }
        
        if ($request->filled('edificio')) {
            $query->where('edificio', $request->input('edificio'));
        }

        if ($request->filled('planta')) {
            $query->where('planta', $request->input('planta'));
        }

        if ($request->filled('categoria_id')) {
            $query->where('categoria_id', $request->input('categoria_id'));
        }

        // Ordenación
        $orden = $request->input('orden', 'nombre');
        $direccion = $request->input('direccion', 'asc');

        $ordenables = [
            'codigo' => 'aulas.codigo',
            'nombre' => 'aulas.nombre',
            'capacidad' => 'aulas.capacidad',
            'categoria_id' => 'aulas.categoria_id',
            'edificio' => 'aulas.edificio',
            'planta' => 'aulas.planta',
        ];

        if (array_key_exists($orden, $ordenables)) {
            $query->orderBy($ordenables[$orden], $direccion);
        }else {
            // Default por si no viene o es inválido
            $query->orderBy('id', 'asc');
        }
            
        $aulas = $query->paginate(10)->appends($request->query());
        
        // Filtros para los selects dinámicos
        $edificios = Aula::select('edificio')
            ->whereNotNull('edificio')
            ->distinct()
            ->orderBy('edificio')
            ->pluck('edificio');

        $plantas = Aula::select('planta')
            ->whereNotNull('planta')
            ->distinct()
            ->orderBy('planta')
            ->pluck('planta');
        $categorias = Categoria::orderBy('nombre')->get();

        // Helper para ordenar desde Blade
        $ordenarPor = function ($campo) use ($request) {
            return array_merge(
                $request->except(['_token', 'orden', 'direccion']),
                [
                    'orden' => $campo,
                    'direccion' => $request->input('orden') === $campo && $request->input('direccion') === 'asc' ? 'desc' : 'asc',
                ]
            );
        };
        
        return view('admin.aulas.manage', compact(
            'aulas',
            'edificios',
            'plantas',
            'categorias',
            'request',
            'ordenarPor'
        ));
    }

    public function edit(Aula $aula)
    {
        $categorias = Categoria::all();
        return view('admin.aulas.edit', compact('aula', 'categorias'));
    }

    public function update(Request $request, Aula $aula)
    {
        $request->validate([
            'codigo' => 'required|unique:aulas,codigo,' . $aula->id,
            'nombre' => 'required',
            'capacidad' => 'required|integer|min:1',
            'categoria_id' => 'required|exists:categorias,id',
            'edificio' => 'required',
            'planta' => 'required'
        ]);

        $aula->update($request->all());
        return redirect()->route('admin.aulas.manage')->with('mensaje', 'Aula actualizada correctamente.');
    }

    public function destroy(Aula $aula)
    {
        $aula->delete();
        return redirect()->route('admin.aulas.manage')->with('mensaje', 'Aula eliminada correctamente.');
    }
}
