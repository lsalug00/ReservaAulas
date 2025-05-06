<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('rol')) {
            $query->where('rol', $request->rol);
        }

        // Manejo de ordenamiento
        $orden = $request->input('orden', 'id');
        $direccion = $request->input('direccion', 'asc');

        $ordenables = ['id', 'name', 'surname', 'email', 'codigo', 'rol', 'active'];

        if (in_array($orden, $ordenables)) {
            $query->orderBy($orden, $direccion);
        } else {
            $query->orderBy('id');
        }

        $users = $query->paginate(10)->withQueryString();

        $searchOld = $request->input('search', old('search'));
        $rolOld = $request->input('rol', old('rol'));

        return view('admin.users.index', compact(
            'users',
            'searchOld',
            'rolOld'
        ));
    }

    public function toggleActive(User $user)
    {
        $user->active = !$user->active;
        $user->save();

        return back()->with('success', 'Estado de la cuenta actualizado.');
    }

    public function changeRole(Request $request, User $user)
    {
        $request->validate([
            'rol' => 'required|in:admin,profesor,invitado',
        ]);

        $user->rol = $request->rol;
        $user->save();

        return back()->with('success', 'Rol actualizado correctamente.');
    }

    public function updateCode(Request $request, User $user)
    {
        $request->validate([
            'codigo' => [
                'required',
                'regex:/^[A-Z]{2}[0-9]{2}$/',
                'unique:users,codigo,' . $user->id,
            ],
        ], [
            'codigo' => 'El código son 2 letras mayusculas y dos numeros',
        ]);

        $user->codigo = $request->codigo;
        $user->save();

        return back()->with('success', 'Código actualizado correctamente.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return back()->with('success', 'Usuario eliminado.');
    }
}
