<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    public function index()
    {
        $invitados = User::where('rol', 'invitado')->orderBy('surname')->get();

        return view('admin.dashboard', compact('invitados'));
    }
}
