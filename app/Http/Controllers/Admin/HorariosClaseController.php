<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\User;
use App\Models\Aula;
use App\Models\Horario;
use App\Models\HorariosClase;

class HorariosClaseController extends Controller
{
    private function diaCompleto($inicial)
    {
        return [
            'L' => 'lunes',
            'M' => 'martes',
            'X' => 'miércoles',
            'J' => 'jueves',
            'V' => 'viernes',
        ][$inicial] ?? null;
    }

    private function obtenerHorario($sesion)
    {
        $mapa = [
            '1' => 1, '2' => 2, '3' => 3, 'R3' => 4, '4' => 5, '5' => 6,
            '6' => 7, '7' => 8, 'V1' => 9, 'V2' => 10, 'V3' => 11,
            'V4' => 13, 'V5' => 14, 'V6' => 15,
        ];

        $id = $mapa[$sesion] ?? null;
        return $id ? Horario::find($id) : null;
    }

    public function form()
    {
        return view('admin.horarios-clase.importar');
    }

    public function import(Request $request)
    {
        $request->validate([
            'archivo' => 'required|file|mimes:xls,xlsx',
        ]);

        // Eliminar todos los horarios existentes antes de importar nuevos
        HorariosClase::truncate();

        $archivo = $request->file('archivo')->getPathname();
        $spreadsheet = IOFactory::load($archivo);
        $rows = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

        $insertadas = 0;

        foreach ($rows as $index => $row) {
            if ($index < 5) continue;

            $codigo = trim($row['B'] ?? '');
            $dia = $this->diaCompleto(trim($row['C'] ?? ''));
            $sesion = trim($row['D'] ?? '');
            $aulaCodigo = trim($row['G'] ?? '');

            if (!$codigo || !$dia || !$sesion || !$aulaCodigo) continue;

            $user = User::where('codigo', $codigo)->first();
            $aula = Aula::where('codigo', $aulaCodigo)->first();
            $horario = $this->obtenerHorario($sesion);

            if (!$user || !$aula || !$horario) continue;

            HorariosClase::create([
                'user_id' => $user->id,
                'aula_id' => $aula->id,
                'dia' => $dia,
                'hora_inicio' => $horario->hora_inicio,
                'hora_fin' => $horario->hora_fin,
            ]);

            $insertadas++;
        }

        return redirect()
            ->route('admin.horarios-clase.form')
            ->with('mensaje', "✔ $insertadas clases importadas correctamente. Se eliminaron los horarios anteriores.");
    }
}
