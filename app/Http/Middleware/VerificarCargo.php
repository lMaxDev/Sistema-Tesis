<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;


class VerificarCargo
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$cargos): Response
    {
        // Comprobar que el usuario esté autenticado
        if (!$request->user()) {
            return redirect()->route('login');
        }

        // Comprobar si el cargo del usuario está autorizado
        if (!in_array($request->user()->cargo, $cargos)) {
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }

        return $next($request);
    }
}
