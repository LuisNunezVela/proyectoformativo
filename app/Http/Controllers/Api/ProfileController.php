<?php

namespace App\Http\Controllers\Api;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function uploadPhoto(Request $request)
{
    try {
        // Validar archivo
        $request->validate([
            'photo' => 'required|image|max:2048', // 2MB
        ]);

        // Obtener el usuario autenticado
        $user = $request->user();

        if (!$user) {
            Log::error('Usuario no autenticado al subir imagen.');
            return response()->json(['message' => 'Usuario no autenticado'], 401);
        }

        // Guardar la imagen
        $path = $request->file('photo')->store("users/{$user->id}", 'private');

        // Actualizar el campo `photo`
        $user->photo = $path;
        $user->save();

        return response()->json([
            'message' => 'Foto de perfil actualizada correctamente',
            'photo_url' => Storage::disk('private')->temporaryUrl($path, now()->addMinutes(30)),
        ]);
    } catch (\Throwable $e) {
        Log::error('Error al subir la foto de perfil: ' . $e->getMessage(), [
            'trace' => $e->getTraceAsString(),
        ]);

        return response()->json([
            'message' => 'Error del servidor: ' . $e->getMessage(),
        ], 500);
    }
}
}
