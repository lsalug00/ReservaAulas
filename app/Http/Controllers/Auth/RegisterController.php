<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => [
                'required',
                'string',
                'min:3',
                'max:20',
                'regex:/^(Mª|[A-ZÁÉÍÓÚÑ][a-záéíóúñ]+)(\s?(del|de|la|los)?\s[A-ZÁÉÍÓÚÑ][a-záéíóúñ]+)*$/'
            ],
            'surname' => [
                'required',
                'string',
                'min:3',
                'max:50',
                'regex:/^[A-ZÁÉÍÓÚÑ][a-záéíóúñ]+(?:\s(de((\s)(la|los))?|del\s)?\s[A-ZÁÉÍÓÚÑ][a-záéíóúñ]+)*(?:\s[A-ZÁÉÍÓÚÑ][a-záéíóúñ]+)?(?:-[A-ZÁÉÍÓÚÑ][a-záéíóúñ]+)?$/'
            ],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{8,}$/'
            ],
        ], [
            'name.regex' => 'El nombre solo debe contener letras, empezar por mayúscula y tener una longitud de 3-20 caracteres.',
            'surname.regex' => 'El(Los) apellido(s) solo debe contener letras, empezar por mayúscula y tener una longitud de 3-20 caracteres.',
            'password.regex' => 'La contraseña debe tener al menos 8 caracteres, incluyendo una mayúscula, una minúscula, un número y un símbolo (!@#$%^&*).',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.'
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'surname' => $data['surname'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'rol' => 'invitado',
            'active' => true,
            'pw_update' => false,
        ]);
    }
}
