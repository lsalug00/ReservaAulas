<?php

namespace App\Http\Controllers;

use App\Models\Aula;
use App\Models\User;
use App\Models\Horario;
use App\Models\Reserva;
use App\Models\Categoria;
use App\Models\HorariosClase;
use App\Models\DiasNoLectivos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InicioController extends Controller
{
    public function __construct()
    {
        // Solo proteger el método store
        $this->middleware(['auth', 'profesor'])->only('store');
    }
    
    public function index(Request $request)
    {
        // Utiliza las variables compartidas por el provider
        $hoy = view()->shared('hoy');
        $inicio_semana = view()->shared('inicio_semana');
        $fin_semana_2 = view()->shared('fin_semana_2');

        $categorias = Categoria::orderBy('nombre')->get();

        // Construir query de aulas con filtros
        $aulasQuery = Aula::query();

        if ($request->filled('categoria_id')) {
            $aulasQuery->where('categoria_id', $request->input('categoria_id'));
        }

        if ($request->filled('edificio')) {
            $aulasQuery->where('edificio', $request->input('edificio'));
        }

        if ($request->filled('planta')) {
            $aulasQuery->where('planta', $request->input('planta'));
        }

        if ($request->filled('capacidad_min')) {
            $aulasQuery->where('capacidad', '>=', $request->capacidad_min);
        }

        if ($request->filled('capacidad_max')) {
            $aulasQuery->where('capacidad', '<=', $request->capacidad_max);
        }

        $aulas = $aulasQuery->orderBy('codigo')->get();
        $aula = $request->filled('aula_id')?$request->input('aula_id'):old('aula_id');
        $aulaSeleccionada = $aulas->firstWhere('id', $aula);
        $turno = $request->input('turno'); // 'mañana' | 'tarde' | null
        $incluirAmbos = $request->boolean('incluir_ambos');

        // Cargar horarios filtrados
        $horarios = Horario::when($turno, function ($query) use ($turno, $incluirAmbos) {
            $query->where(function ($q) use ($turno, $incluirAmbos) {
                $q->where('turno', $turno);
                if ($incluirAmbos) {
                    $q->orWhere('turno', 'ambos');
                }
            });
        }, function ($query) {
            $query->whereIn('turno', ['mañana', 'tarde', 'ambos']);
        })->orderBy('hora_inicio')->get();

        $recreos = collect(['mañana' => null, 'tarde' => null]);
        foreach (['mañana', 'tarde'] as $t) {
            $franjas = $horarios->where('turno', $t);
            if ($franjas->count()) {
                $recreos[$t] = $franjas
                    ->sortBy(fn($h) => strtotime($h->hora_fin) - strtotime($h->hora_inicio))
                    ->first();
            }
        }

        $horariosClase = collect();
        $reservasSemana = collect();
        $diasNoLectivos = collect();

        if ($aulaSeleccionada) {
            $horariosClase = HorariosClase::where('aula_id', $aulaSeleccionada->id)
                ->get()
                ->flatMap(function ($clase) use ($horarios) {
                    $inicio = $clase->hora_inicio;
                    $fin = $clase->hora_fin;
                    return $horarios
                        ->filter(fn($h) => $h->hora_inicio >= $inicio && $h->hora_fin <= $fin)
                        ->mapWithKeys(function ($franja) use ($clase) {
                            $key = $clase->dia . '_' . substr($franja->hora_inicio, 0, 5);
                            return [$key => $clase];
                        });
                });

            $reservasSemana = Reserva::where('aula_id', $aulaSeleccionada->id)
                ->whereBetween('fecha', [$inicio_semana, $fin_semana_2])
                ->get()
                ->groupBy('fecha');

            $diasNoLectivos = DiasNoLectivos::whereBetween('fecha', [$inicio_semana, $fin_semana_2])
                ->get()
                ->keyBy('fecha');
        }

        $edificios = Aula::select('edificio')->distinct()->orderBy('edificio')->pluck('edificio');
        $plantas = Aula::select('planta')->distinct()->orderBy('planta')->pluck('planta');
        $todasLasAulas = Aula::orderBy('codigo')->get();

        $aulaOld = $aula;
        $turnoOld = $request->input('turno', old('turno'));
        $categoriaOld = $request->input('categoria_id', old('categoria_id'));
        $incluirAmbosOld = $request->boolean('incluir_ambos', old('incluir_ambos'));
        $capacidadMinOld = $request->input('capacidad_min', old('capacidad_min'));
        $capacidadMaxOld = $request->input('capacidad_max', old('capacidad_max'));
        $edificioOld = $request->input('edificio', old('edificio'));
        $plantaOld = $request->input('planta', old('planta'));

        return view('index', compact(
            'aulas',
            'categorias',
            'aulaSeleccionada',
            'horarios',
            'horariosClase',
            'recreos',
            'reservasSemana',
            'diasNoLectivos',
            'edificios',
            'plantas',
            'todasLasAulas',
            'aulaOld',
            'categoriaOld',
            'turnoOld',
            'incluirAmbosOld',
            'capacidadMinOld',
            'capacidadMaxOld',
            'edificioOld',
            'plantaOld'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'aula_id' => 'required|exists:aulas,id',
            'fecha' => 'required|date|after_or_equal:today',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
            'uso' => 'required|in:clase,examen,charla,taller,otro',
        ], [
            'hora_fin.after' => 'La hora de fin debe ser posterior a la hora de inicio.',
        ]);

        $fechaReserva = $request->fecha;
        $timestamp = strtotime($fechaReserva);
        $diaSemanaReserva = date('w', $timestamp); // 0 = domingo, 6 = sábado

        if ($diaSemanaReserva == 0 || $diaSemanaReserva == 6) {
            return back()->withErrors([
                'fecha' => 'No se pueden hacer reservas en fines de semana.',
            ])->withInput();
        }

        // Usar las fechas compartidas globalmente desde el service provider
        $inicioSemana = strtotime(view()->shared('inicio_semana'));
        $finSemana2 = strtotime(view()->shared('fin_semana_2'));

        if ($timestamp < $inicioSemana || $timestamp > $finSemana2) {
            return back()->withErrors([
                'fecha' => 'Solo se pueden hacer reservas para esta semana y las dos siguientes.',
            ])->withInput();
        }

        // Día no lectivo
        $esNoLectivo = DiasNoLectivos::where('fecha', $fechaReserva)->exists();
        if ($esNoLectivo) {
            return back()->withErrors(['fecha' => 'No se puede reservar en un día no lectivo.'])->withInput();
        }

        // Verificar conflictos con clases
        $diaNombre = strtolower(now()->parse($fechaReserva)->locale('es')->isoFormat('dddd'));

        $hayClase = HorariosClase::where('aula_id', $request->aula_id)
            ->where('dia', $diaNombre)
            ->where(function ($q) use ($request) {
                $q->whereBetween('hora_inicio', [$request->hora_inicio, $request->hora_fin])
                  ->orWhereBetween('hora_fin', [$request->hora_inicio, $request->hora_fin])
                  ->orWhere(function ($q2) use ($request) {
                      $q2->where('hora_inicio', '<', $request->hora_inicio)
                         ->where('hora_fin', '>', $request->hora_fin);
                  });
            })
            ->exists();

        if ($hayClase) {
            return back()->withErrors([
                'hora_inicio' => 'Esta franja horaria está ocupada por una clase regular.',
            ])->withInput();
        }

        // Verificar conflictos con otras reservas
        $hayReserva = Reserva::where('aula_id', $request->aula_id)
            ->where('fecha', $fechaReserva)
            ->where(function ($q) use ($request) {
                $q->whereBetween('hora_inicio', [$request->hora_inicio, $request->hora_fin])
                  ->orWhereBetween('hora_fin', [$request->hora_inicio, $request->hora_fin])
                  ->orWhere(function ($q2) use ($request) {
                      $q2->where('hora_inicio', '<', $request->hora_inicio)
                         ->where('hora_fin', '>', $request->hora_fin);
                  });
            })
            ->exists();

        if ($hayReserva) {
            return back()->withErrors([
                'hora_inicio' => 'Ya existe una reserva en ese horario.',
            ])->withInput();
        }

        // Guardar la reserva
        Reserva::create([
            'user_id' => auth()->id(),
            'aula_id' => $request->aula_id,
            'fecha' => $fechaReserva,
            'hora_inicio' => $request->hora_inicio,
            'hora_fin' => $request->hora_fin,
            'uso' => $request->uso,
        ]);

        return redirect()
            ->route('index')
            ->withInput() // Mantiene los valores del formulario
            ->with('success', 'Reserva realizada con éxito.');
    }
}
