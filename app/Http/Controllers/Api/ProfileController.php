<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function uploadPhoto(Request $request)
    {
        // Validar archivo
        $request->validate([
            'photo' => 'required|image|max:2048', // 2MB
        ]);

        // Obtener el usuario autenticado (agregado por middleware)
        $user = $request->user();  // <- Cambiado aquí

        if (!$user) {
            return response()->json(['message' => 'Usuario no autenticado'], 401);
        }

        // Guardar la imagen
        $path = $request->file('photo')->store("users/{$user->id}", 'public');

        // Actualizar el campo `photo` en la base de datos
        $user->photo = $path;
        $user->save();

        return response()->json([
            'message' => 'Foto de perfil actualizada correctamente',
            'photo_url' => Storage::disk('public')->url($path),
        ]);
    }
}
