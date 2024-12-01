<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
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
                'FechaExpiracion' => Carbon::now()->addHours(1), // Asegúrate que el nombre del campo coincida con tu BD
                'Usado' => 0
            ]);

            $token->save();

            // Enviar el correo
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
}