<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Str;
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

        // Buscar usuario por email
        $user = User::where('email', $request->email)->first();

        // Verificar usuario y contraseña
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Credenciales inválidas'], 401);
        }

        // Generar nuevo token y guardar
        $user->api_token = Str::random(60);
        $user->save();

        // Responder con usuario y token (solo campos seguros)
        return response()->json([
            'message' => 'Login exitoso',
            'user' => $user->only(['id', 'name', 'lastname', 'email', 'birthdate', 'photo']),
            'token' => $user->api_token,
        ]);
    }

    public function register(Request $request)
    {
        // Validar datos de registro
        $request->validate([
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'birthdate' => 'required|date',
        ]);

        // Crear usuario con token
        $user = User::create([
            'name' => $request->name,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'birthdate' => $request->birthdate,
            'api_token' => Str::random(60),
        ]);

        // Responder con usuario y token (solo campos seguros)
        return response()->json([
            'message' => 'Usuario registrado con éxito',
            'user' => $user->only(['id', 'name', 'lastname', 'email', 'birthdate', 'photo']),
            'token' => $user->api_token,
        ], 201);
    }
}
