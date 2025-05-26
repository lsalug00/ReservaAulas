<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Component\HttpFoundation\StreamedResponse;

class UserCreateController extends Controller
{
    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $csvPath = storage_path('app/public/usuarios_creados.csv');
        $csv = fopen($csvPath, 'w');
        fwrite($csv, chr(0xEF) . chr(0xBB) . chr(0xBF));
        fputcsv($csv, ['Nombre', 'Apellidos', 'Email', 'Contraseña'], ';');

        $validated = $request->validate([
            'codigo' => 'required|string|regex:/^[A-Z]{2}[0-9]{2}$/',
            'name' => 'required|string',
            'surname' => 'required|string',
            'email' => 'required|email|unique:users,email',
        ]);

        $clave = "_%&" . bin2hex(random_bytes(4)) . "&%_";

        User::create([
            'codigo' => $validated['codigo'],
            'name' => $validated['name'],
            'surname' => $validated['surname'],
            'email' => strtolower($validated['email']),
            'password' => Hash::make($clave),
            'rol' => 'profesor',
            'active' => true,
            'pw_update' => true,
        ]);

        fputcsv($csv, [$validated['name'], $validated['surname'], $validated['email'], $clave], ';');

        fclose($csv);

        return redirect()->back()->with('success', 'Usuario creado correctamente.');
    }

    public function confirmUpload(Request $request)
    {
        $request->validate([
            'archivo' => 'required|file|mimes:xls,xlsx'
        ]);

        $spreadsheet = IOFactory::load($request->file('archivo')->getPathname());
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray(null, true, true, true);

        $preview = [];

        foreach ($rows as $index => $row) {
            if ($index < 5) continue;

            $codigo = trim($row['A'] ?? '');
            $nombre = trim($row['B'] ?? '');
            $apellido = trim($row['C'] ?? '');
            $email = strtolower(trim($row['D'] ?? ''));

            $errorMessages = [];

            if (empty($codigo)) {
                $errorMessages[] = 'Código vacío';
            }
            if (empty($nombre)) {
                $errorMessages[] = 'Nombre vacío';
            }
            if (empty($apellido)) {
                $errorMessages[] = 'Apellido vacío';
            }
            if (empty($email)) {
                $errorMessages[] = 'Email vacío';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errorMessages[] = 'Email no válido';
            }

            $exists = User::where('email', $email)->exists();

            if (count($errorMessages) > 0) {
                $preview[] = [
                    'name' => $nombre,
                    'surname' => $apellido,
                    'email' => $email,
                    'valid' => false,
                    'error' => implode(', ', $errorMessages),
                    'exists' => false,
                ];
                continue;
            }

            $preview[] = [
                'codigo' => $codigo,
                'name' => mb_convert_case(mb_strtolower($nombre, 'UTF-8'), MB_CASE_TITLE, "UTF-8"),
                'surname' => mb_convert_case(mb_strtolower($apellido, 'UTF-8'), MB_CASE_TITLE, "UTF-8"),
                'email' => $email,
                'valid' => true,
                'exists' => $exists,
            ];
        }

        return view('admin.users.create', [
            'preview' => $preview,
            'data_serialized' => base64_encode(serialize($preview)),
        ]);
    }

    public function storeMassive(Request $request)
    {
        set_time_limit(300);
        $preview = unserialize(base64_decode($request->input('data_serialized', '')));
        $csvPath = storage_path('app/public/usuarios_creados.csv');
        $csv = fopen($csvPath, 'w');
        fwrite($csv, chr(0xEF) . chr(0xBB) . chr(0xBF));
        fputcsv($csv, ['Nombre', 'Apellidos', 'Email', 'Contraseña'], ';');

        $guardados = 0;
        
        foreach ($preview as $item) {
            if (!$item['valid'] || ($item['exists'] ?? false)) continue;

            $clave = "_%&" . bin2hex(random_bytes(4)) . "&%_";

            User::updateOrCreate(
                ['email' => $item['email']],
                [
                    'codigo' => $item['codigo'],
                    'name' => $item['name'],
                    'surname' => $item['surname'],
                    'password' => Hash::make($clave),
                    'rol' => 'profesor',
                    'active' => true,
                    'pw_update' => true,
                ]
            );

            fputcsv($csv, [$item['name'], $item['surname'], $item['email'], $clave], ';');

            $guardados++;
        }

        fclose($csv);

        return redirect()
            ->route('admin.users.create')
            ->with('success', "Se guardaron {$guardados} usuario(s) correctamente.");
    }

    public function downloadCsv()
    {
        $path = storage_path('app/public/usuarios_creados.csv');
        if (!file_exists($path)) {
            abort(404, 'Archivo no encontrado');
        }

        return new StreamedResponse(function () use ($path) {
            $handle = fopen($path, 'r');
            while (!feof($handle)) {
                echo fread($handle, 1024);
            }
            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="usuarios_creados.csv"',
        ]);
    }
}
