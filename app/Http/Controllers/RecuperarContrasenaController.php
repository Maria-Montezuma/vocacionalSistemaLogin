<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Preguntasseguridad;
use App\Models\Respuestasseguridad;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\Token;
use Illuminate\Support\Facades\Log;
use App\Mail\RecuperacionContrasena;

class RecuperarContrasenaController extends Controller
{
    public function verificarCorreo(Request $request)
    {
        $request->validate([
            'CorreoUsuario' => 'required|email|exists:usuarios,CorreoUsuario',
        ]);
    
        return response()->json([
            'status' => 'success',
            'message' => 'Correo verificado correctamente.',
        ]);
    }

    public function enviarEnlaceRecuperacion(Request $request)
    {
        try {
            $request->validate([
                'CorreoUsuario' => 'required|email'
            ]);

            $usuario = Usuario::where('CorreoUsuario', $request->CorreoUsuario)->first();

            if (!$usuario) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No encontramos una cuenta con ese correo electrónico.'
                ], 404);
            }

            // Invalidar tokens anteriores
            Token::where('Usuarios_idUsuario', $usuario->idUsuario)
                 ->where('TipoToken', 'reset')
                 ->where('Usado', 0)
                 ->update(['Usado' => 1]);

            // Crear nuevo token
            $tokenString = Str::random(60);
            $token = new Token([
                'Usuarios_idUsuario' => $usuario->idUsuario,
                'Token' => $tokenString,
                'TipoToken' => 'reset',
                'FechaExpiracion' => Carbon::now()->addHours(1),
                'Usado' => 0
            ]);

            $token->save();

            Mail::to($usuario->CorreoUsuario)->send(new RecuperacionContrasena($usuario, $tokenString));

            return response()->json([
                'status' => 'success',
                'message' => 'Se ha enviado un enlace de recuperación a tu correo electrónico.'
            ]);

        } catch (\Exception $e) {
            Log::error('Error en recuperación de contraseña: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Ocurrió un error al enviar el enlace de recuperación.'
            ], 500);
        }
    }

    public function verificarToken($token)
    {
        $tokenRecord = Token::where('Token', $token)
            ->where('TipoToken', 'reset')
            ->where('Usado', 0)
            ->where('FechaExpiracion', '>', Carbon::now())
            ->first();

        if (!$tokenRecord) {
            return response()->json([
                'status' => 'error',
                'message' => 'Token inválido o expirado'
            ], 400);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Token válido'
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'ContrasenaUsuario' => 'required|min:8|confirmed'
        ]);

        $tokenRecord = Token::where('Token', $request->token)
            ->where('TipoToken', 'reset')
            ->where('Usado', 0)
            ->where('FechaExpiracion', '>', Carbon::now())
            ->first();

        if (!$tokenRecord) {
            return response()->json([
                'status' => 'error',
                'message' => 'Token inválido o expirado'
            ], 400);
        }

        $usuario = Usuario::find($tokenRecord->Usuarios_idUsuario);
        $usuario->ContrasenaUsuario = bcrypt($request->ContrasenaUsuario);
        $usuario->save();

        $tokenRecord->Usado = 1;
        $tokenRecord->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Contraseña actualizada correctamente'
        ]);
    }

    public function showResetForm($token)
    {
        $tokenRecord = Token::where('Token', $token)
            ->where('TipoToken', 'reset')
            ->where('Usado', 0)
            ->where('FechaExpiracion', '>', Carbon::now())
            ->first();

        if (!$tokenRecord) {
            abort(404, 'Token inválido o expirado');
        }

        return view('reset-password', ['token' => $token]);
    }

    public function verificarCorreoPreguntas(Request $request)
{
    try {
        $request->validate([
            'CorreoUsuario' => 'required|email'
        ]);

        $usuario = Usuario::where('CorreoUsuario', $request->CorreoUsuario)->first();

        if (!$usuario) {
            return response()->json([
                'status' => 'error',
                'message' => 'No se encontró ninguna cuenta con este correo electrónico.'
            ], 404);
        }

        // Verificar si el usuario tiene preguntas de seguridad
        $preguntas = RespuestasSeguridad::where('Usuarios_idUsuario', $usuario->idUsuario)
            ->with('preguntasSeguridad')
            ->get();

        if ($preguntas->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No hay preguntas de seguridad configuradas para esta cuenta.'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'userId' => $usuario->idUsuario
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Ocurrió un error al verificar el correo: ' . $e->getMessage()
        ], 500);
    }
}
public function mostrarPreguntasSeguridad($userId)
{
    try {
        $respuestas = Respuestasseguridad::select(
                'respuestasseguridad.idRespuestasSeguridad',
                'respuestasseguridad.PreguntasSeguridad_idPreguntasSeguridad',
                'preguntasseguridad.PreguntasSeguridad',
                'respuestasseguridad.Respuesta'  // Agregamos la respuesta de la BD
            )
            ->join('preguntasseguridad', 'respuestasseguridad.PreguntasSeguridad_idPreguntasSeguridad', '=', 'preguntasseguridad.idPreguntasSeguridad')
            ->where('respuestasseguridad.Usuarios_idUsuario', $userId)
            ->get();

        if ($respuestas->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No hay preguntas de seguridad configuradas'
            ], 404);
        }

        $preguntasConRespuestas = $respuestas->map(function($respuesta) {
            return [
                'idRespuesta' => $respuesta->idRespuestasSeguridad,
                'pregunta' => $respuesta->PreguntasSeguridad,
                'idPregunta' => $respuesta->PreguntasSeguridad_idPreguntasSeguridad,
                'respuestaCorrecta' => $respuesta->Respuesta, // La respuesta almacenada en la BD
                'respuestaUsuario' => ''  // Campo vacío para que el usuario ingrese su respuesta
            ];
        });

        return response()->json([
            'status' => 'success',
            'preguntas' => $preguntasConRespuestas
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Error al cargar las preguntas: ' . $e->getMessage()
        ], 500);
    }
}

// Nuevo método para validar las respuestas
public function validarRespuestas(Request $request)
{
    try {
        $userId = $request->userId;
        $respuestasUsuario = $request->respuestas;

        foreach ($respuestasUsuario as $respuesta) {
            $respuestaDB = Respuestasseguridad::where('idRespuestasSeguridad', $respuesta['idRespuesta'])
                ->where('Usuarios_idUsuario', $userId)
                ->first();

            if (!$respuestaDB || $respuestaDB->Respuesta !== $respuesta['respuesta']) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Las respuestas no coinciden'
                ], 400);
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Respuestas correctas',
            'permitirCambioContrasena' => true
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Error al validar las respuestas: ' . $e->getMessage()
        ], 500);
    }
}

// Método para cambiar la contraseña
public function cambiarContrasena(Request $request)
{
    try {
        $userId = $request->userId;
        $nuevaContrasena = $request->nuevaContrasena;

        $usuario = Usuario::find($userId);
        if (!$usuario) {
            return response()->json([
                'status' => 'error',
                'message' => 'Usuario no encontrado'
            ], 404);
        }

        $usuario->password = bcrypt($nuevaContrasena);
        $usuario->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Contraseña actualizada correctamente'
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Error al cambiar la contraseña: ' . $e->getMessage()
        ], 500);
    }
}
}