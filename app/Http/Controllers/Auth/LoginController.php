<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Personaliza las credenciales usadas en el login para requerir que el usuario esté activo.
     */
    protected function credentials(Request $request)
    {
        return [
            'email' => $request->get('email'),
            'password' => $request->get('password'),
            'active' => 1, // Solo usuarios activos pueden iniciar sesión
        ];
    }

    /**
     * Mensaje personalizado si el usuario está desactivado.
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if ($user && !$user->active) {
            throw ValidationException::withMessages([
                'email' => ['Tu cuenta está desactivada. Contacta con el administrador.'],
            ]);
        }

        throw ValidationException::withMessages([
            'email' => [trans('auth.failed')],
        ]);
    }

    /**
     * Redirección personalizada tras autenticación.
     */
    protected function authenticated(Request $request, $user)
    {
        // Si debe cambiar la contraseña, redirigir directamente
        if ($user->pw_update) {
            return redirect()->route('pass.edit')
                ->with('error', 'Debes cambiar tu contraseña antes de continuar.');
        }

        // Si no, continuar con la redirección normal
        return redirect()->intended($this->redirectPath());
    }
}
