<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Validar entrada
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Buscar el usuario por email
        $user = User::where('email', $request->email)->first();

        // Verificar si existe y si la contraseña coincide
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Credenciales inválidas'], 401);
        }

        // Login exitoso, se puede agregar token aquí si usas Sanctum o JWT
        return response()->json([
            'message' => 'Login exitoso',
            'user' => $user,
        ], 200);
    }

    public function register(Request $request)
{
    // Validar los datos del formulario
    $request->validate([
        'name' => 'required|string|max:255',
        'lastname' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:6',
        'birthdate' => 'required|date',
    ]);

    // Crear el usuario
    $user = User::create([
        'name' => $request->name,
        'lastname' => $request->lastname,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'birthdate' => $request->birthdate,
    ]);

    return response()->json([
        'message' => 'Usuario registrado con éxito',
        'user' => $user,
    ], 201);
}

}
