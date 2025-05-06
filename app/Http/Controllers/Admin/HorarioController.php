<?php

namespace App\Http\Controllers\Admin;

use App\Models\Horario;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HorarioController extends Controller
{
    public function index()
    {
        return view('admin.horarios.index', [
            'horarios' => Horario::orderBy('hora_inicio')->get()
        ]);
    }

    public function update(Request $request, Horario $horario)
    {
        $request->validate([
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
            'turno' => 'required|in:mañana,tarde,ambos',
        ]);

        // Comprobar solapamiento (permitiendo fin == inicio de otra franja)
        $solapado = Horario::where('id', '!=', $horario->id)
            ->where(function ($query) use ($request) {
                $inicio = $request->hora_inicio;
                $fin = $request->hora_fin;
                $query->where(function ($q) use ($inicio, $fin) {
                    $q->where('hora_inicio', '<', $fin)
                    ->where('hora_fin', '>', $inicio);
                });
            })
            ->exists();

        if ($solapado) {
            return back()->withErrors('La franja horaria se solapa con otra existente.')->withInput();
        }

        $horario->update($request->only('hora_inicio', 'hora_fin', 'turno'));

        return back()->with('mensaje', 'Franja actualizada correctamente.');
    }
}
