<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\Aula;
use App\Models\Horario;
use App\Models\HorariosClase;
use App\Models\DiasNoLectivos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReservaController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'profesor']);
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
