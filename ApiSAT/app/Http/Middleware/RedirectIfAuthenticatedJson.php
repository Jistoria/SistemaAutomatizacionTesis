<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticatedJson extends \Illuminate\Auth\Middleware\RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    protected function redirectTo(Request $request): ?string
    {
        // Si la solicitud espera una respuesta JSON, devolver un mensaje de error.
        if ($request->expectsJson()) {
            abort(response()->json(['error' => 'No puedes acceder'], 403));
        }

        // De lo contrario, usar el comportamiento por defecto.
        return parent::redirectTo($request);
    }
}
