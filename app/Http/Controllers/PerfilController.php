<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reserva;
use App\Models\Categoria;
use App\Models\Aula;
use App\Http\Controllers\MisReservasController;

class PerfilController extends Controller
{
    public function index(Request $request)
    {
        $usuario = auth()->user();

        // Usamos el controlador existente
        $misReservas = new MisReservasController();

        // Llamamos al método index, que devuelve una view con compact(...)
        $response = $misReservas->index($request);

        // Extraemos los datos de la view
        $data = $response->getData();

        return view('perfil.index', [
            'reservas'     => $data['reservas'],
            'categorias'   => $data['categorias'],
            'edificios'    => $data['edificios'],
            'plantas'      => $data['plantas'],
            'ordenarPor'   => $data['ordenarPor'],
            'request'      => $request,
            'usuario'      => $usuario
        ]);
    }

    public function updateEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email,' . auth()->id(),
        ]);

        $usuario = auth()->user();
        $usuario->email = $request->email;
        $usuario->save();

        return response()->json(['success' => true]);
    }

}
