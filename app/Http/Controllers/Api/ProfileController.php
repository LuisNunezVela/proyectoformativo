<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;  // Asegúrate de importar el modelo User
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    public function uploadPhoto(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'photo_base64' => 'required|string',
        ]);

        try {
            $user = User::find($request->user_id);
            if (!$user) {
                return response()->json(['message' => 'Usuario no encontrado'], 404);
            }

            // Guardar directamente el base64 en el campo photo
            $user->photo = $request->photo_base64;
            $user->save();

            return response()->json([
                'message' => 'Foto de perfil actualizada correctamente',
            ], 200);
        } catch (\Throwable $e) {
            Log::error('Error al subir la foto de perfil: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error del servidor: ' . $e->getMessage(),
            ], 500);
        }
    }
}
