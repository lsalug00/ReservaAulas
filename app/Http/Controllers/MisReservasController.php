<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reserva;
use App\Models\Categoria;
use App\Models\Aula;

class MisReservasController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $reservas = Reserva::with('aula.categoria')
            ->where('user_id', $user->id)
            ->join('aulas', 'reservas.aula_id', '=', 'aulas.id')
            ->select('reservas.*');

        // Filtros
        if ($request->filled('fecha_inicio')) {
            $reservas->where('reservas.fecha', '>=', $request->input('fecha_inicio'));
        }

        if ($request->filled('fecha_fin')) {
            $reservas->where('reservas.fecha', '<=', $request->input('fecha_fin'));
        }

        if ($request->filled('uso')) {
            $reservas->where('reservas.uso', $request->input('uso'));
        }

        if ($request->input('estado') === 'futuras') {
            $reservas->where('reservas.fecha', '>=', date('Y-m-d'));
        } elseif ($request->input('estado') === 'pasadas') {
            $reservas->where('reservas.fecha', '<', date('Y-m-d'));
        }

        if ($request->filled('categoria_id')) {
            $reservas->where('aulas.categoria_id', $request->input('categoria_id'));
        }

        if ($request->filled('edificio')) {
            $reservas->where('aulas.edificio', $request->input('edificio'));
        }

        if ($request->filled('planta')) {
            $reservas->where('aulas.planta', $request->input('planta'));
        }

        // Ordenación
        $orden = $request->input('orden', 'fecha');
        $direccion = $request->input('direccion', 'asc');

        $ordenables = [
            'fecha' => 'reservas.fecha',
            'hora_inicio' => 'reservas.hora_inicio',
            'hora_fin' => 'reservas.hora_fin',
            'uso' => 'reservas.uso',
            'aula' => 'aulas.nombre',
            'edificio' => 'aulas.edificio',
            'planta' => 'aulas.planta',
        ];

        if (array_key_exists($orden, $ordenables)) {
            $reservas->orderBy($ordenables[$orden], $direccion);
        }

        $reservas = $reservas->paginate(10)->appends($request->except('_token'));

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

        return view('perfil.index', compact(
            'reservas',
            'categorias',
            'edificios',
            'plantas',
            'request',
            'ordenarPor'
        ));
    }

    public function destroy(Reserva $reserva)
    {
        if ($reserva->user_id !== auth()->id()) {
            abort(403);
        }

        if ($reserva->fecha < date('Y-m-d')) {
            return back()->withErrors(['No se pueden cancelar reservas pasadas.']);
        }

        $reserva->delete();

        return redirect()->route('perfil')->with('success', 'Reserva cancelada correctamente.');
    }
}
