<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class PasswordController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Muestra el formulario
    public function edit()
    {
        return view('auth.passwords.change');
    }

    // Procesa el cambio
    public function update(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'La contraseña actual no es correcta.');
        }

        $user->update([
            'password' => Hash::make($request->password),
            'pw_update' => false,
        ]);
        
        // Si se usa la sesión en base de datos, eliminar todas las del usuario
        if (config('session.driver') === 'database') {
            DB::table('sessions')->where('user_id', $user->id)->delete();
        }

        // Vaciar sesión actual
        Session::flush();
        Auth::logout();

        return redirect()->route('login')->with('success', 'Contraseña cambiada correctamente. Por favor, inicia sesión de nuevo.');
    }
}
