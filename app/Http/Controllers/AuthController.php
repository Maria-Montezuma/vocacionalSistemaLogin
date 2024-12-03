<?php

namespace App\Http\Controllers;


use App\Models\Usuario;
use App\Models\Token;
use App\Models\Respuestasseguridad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{ 
    public function verificarEmail($token)
    {
        $token = Token::where('Token', $token)
                     ->where('TipoToken', 'verify')
                     ->where('Usado', 0)
                     ->where('TiempoExpiracion', '>', now())
                     ->first();

        if (!$token) {
            return redirect()->route('registro')
                ->with('error', 'Token de verificación inválido o expirado');
        }

        $usuario = $token->usuario;
            if (!$usuario) {
                return redirect()->route('registro')
                    ->with('error', 'Usuario no encontrado');
            }

        $usuario->update(['verificado' => 1]);
        
        $token->Usado = 1;
        $token->save();

        return redirect()->route('registro')
            ->with('success', 'Email verificado correctamente. Ya puedes iniciar sesión.');
    }
    
  
    public function showPasswordRecoveryForm()
    {

        return view('recuperar-contrasena'); 
    }

    public function guardarRespuestasSeguridad(Request $request)
    {
        // Validación de las respuestas
        $validator = Validator::make($request->all(), [
            'pregunta1' => 'required|exists:preguntasseguridad,idPreguntasSeguridad',
            'respuesta1' => 'required|string|max:255',
            'pregunta2' => 'required|exists:preguntasseguridad,idPreguntasSeguridad',
            'respuesta2' => 'required|string|max:255',
        ], [
            'pregunta1.exists' => 'La primera pregunta no es válida.',
            'pregunta2.exists' => 'La segunda pregunta no es válida.',
            'respuesta1.required' => 'La respuesta a la primera pregunta es obligatoria.',
            'respuesta2.required' => 'La respuesta a la segunda pregunta es obligatoria.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Obtener el usuario logueado
        $usuario = Usuario::find(session('usuario_id'));

        if (!$usuario) {
            return redirect()->route('login')->with('error', 'Usuario no encontrado');
        }

        // Crear las respuestas de seguridad
        Respuestasseguridad::create([
            'RespuestaSeguridad_hash' => Hash::make($request->respuesta1),
            'PreguntasSeguridad_idPreguntasSeguridad' => $request->pregunta1,
            'Usuarios_idUsuario' => $usuario->idUsuario
        ]);

        Respuestasseguridad::create([
            'RespuestaSeguridad_hash' => Hash::make($request->respuesta2),
            'PreguntasSeguridad_idPreguntasSeguridad' => $request->pregunta2,
            'Usuarios_idUsuario' => $usuario->idUsuario
        ]);

    
        return redirect()->route('perfil')->with('success', 'Respuestas de seguridad guardadas correctamente');
    }


}