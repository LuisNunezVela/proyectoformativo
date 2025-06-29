<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;

class CheckApiToken
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['message' => 'No autorizado: token no enviado'], 401);
        }

        $user = User::where('api_token', $token)->first();

        if (!$user) {
            return response()->json(['message' => 'No autorizado: token inválido'], 401);
        }

        // Asignar usuario autenticado al request
        $request->setUserResolver(fn() => $user);

        return $next($request);
    }
}
