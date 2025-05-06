<?php

namespace App\Http\Controllers\Admin;

use DateTime;
use DatePeriod;
use DateInterval;
use Illuminate\Http\Request;
use Smalot\PdfParser\Parser;
use App\Models\DiasNoLectivos;
use App\Http\Controllers\Controller;

class DiasNoLectivosController extends Controller
{
    public function form()
    {
        return view('admin.dias-no-lectivos.form');
    }

    public function import(Request $request)
    {
        $request->validate([
            'pdf' => 'required|mimes:pdf|max:2048',
        ]);

        $pdf = (new Parser())->parseFile($request->file('pdf')->getRealPath());
        $text = $pdf->getText();
        $lineas = explode("\n", $text);

        $fechas = [];

        foreach ($lineas as $linea) {
            $linea = trim($linea);

            // Rangos de fechas
            if (preg_match('/^(\d{2})\/(\d{2})\/(\d{4})\s*-\s*(\d{2})\/(\d{2})\/(\d{4})\s+(.+)/', $linea, $m)) {
                $inicio = \DateTime::createFromFormat('d/m/Y', "$m[1]/$m[2]/$m[3]");
                $fin = \DateTime::createFromFormat('d/m/Y', "$m[4]/$m[5]/$m[6]");
                $descripcion = $m[7];

                $periodo = new \DatePeriod($inicio, new \DateInterval('P1D'), $fin->modify('+1 day'));

                foreach ($periodo as $fecha) {
                    $dia = (int) $fecha->format('w');
                    $fechas[] = [
                        'title' => $descripcion,
                        'start' => $fecha->format('Y-m-d'),
                        'es_finde' => ($dia === 0 || $dia === 6), // Marcar si es finde
                    ];
                }
            }
            // Fecha única
            elseif (preg_match('/^(\d{2})\/(\d{2})\/(\d{4})\s+(.+)/', $linea, $m)) {
                $fecha = "$m[3]-$m[2]-$m[1]";
                $descripcion = $m[4];

                $dia = (int) date('w', strtotime($fecha));
                if (!preg_match('/inicio de curso|fin de curso/i', $descripcion)) {
                    $fechas[] = [
                        'title' => $descripcion,
                        'start' => $fecha,
                        'es_finde' => ($dia === 0 || $dia === 6),
                    ];
                }
            }
        }

        $primerFecha = collect($fechas)->pluck('start')->sort()->first();
        $añoInicio = (int) substr($primerFecha, 0, 4);
        $startDate = "$añoInicio-09-01";
        $endDate = ($añoInicio + 1) . "-06-30";

        return view('admin.dias-no-lectivos.form', [
            'eventos' => json_encode($fechas),
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }

    public function store(Request $request)
    {
        $eventos = json_decode($request->input('eventos'), true);

        foreach ($eventos as $evento) {
            // Saltar fines de semana
            if ($evento['es_finde']) {
                continue;
            }

            DiasNoLectivos::updateOrCreate(
                ['fecha' => $evento['start']],
                ['descripcion' => $evento['title']]
            );
        }

        return redirect()->back()->with('mensaje', 'Días no lectivos guardados correctamente.');
    }
}
