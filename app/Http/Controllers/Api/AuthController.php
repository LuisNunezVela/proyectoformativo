<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Validar los datos del request
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Obtener solo los campos necesarios
        $credentials = $request->only('email', 'password');

        // Intentar autenticar con los datos
        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Credenciales inválidas'], 401);
        }

        // Obtener el usuario autenticado
        $user = Auth::user();

        // Puedes retornar el usuario o generar un token aquí si quieres (más adelante)
        return response()->json([
            'message' => 'Login exitoso',
            'user' => $user,
        ], 200);
    }
}
