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
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            // Mensaje genérico para no revelar si email existe
            return response()->json(['message' => 'Credenciales incorrectas'], 401);
        }

        $user->api_token = Str::random(60);
        $user->save();

        return response()->json([
            'message' => 'Login exitoso',
            'user' => $user->only(['id', 'name', 'lastname', 'email', 'birthdate', 'photo']),
            'token' => $user->api_token,
        ], 200);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'birthdate' => 'required|date',
        ]);

        $user = User::create([
            'name' => $request->name,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'birthdate' => $request->birthdate,
            'api_token' => Str::random(60),
        ]);

        return response()->json([
            'message' => 'Usuario registrado con éxito',
            'user' => $user->only(['id', 'name', 'lastname', 'email', 'birthdate', 'photo']),
            'token' => $user->api_token,
        ], 201);
    }
}
