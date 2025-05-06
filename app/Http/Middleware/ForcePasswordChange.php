<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForcePasswordChange
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (
            auth()->check() &&
            auth()->user()->pw_update &&
            !$request->is('cambiar-contraseña') &&
            !$request->is('logout')
        ) {
            return redirect()->route('password.edit')->with('error', 'Debes cambiar tu contraseña antes de continuar.');
        }

        return $next($request);
    }
}
