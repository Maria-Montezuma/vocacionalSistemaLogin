<?php

namespace App\Http\Controllers;

use App\Models\Respuestasseguridad;
use App\Models\Preguntasseguridad;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RespuestaSeguridadController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'preguntas' => 'required|array|size:3',
            'preguntas.*.PreguntasSeguridad_idPreguntasSeguridad' => 'required|exists:preguntasseguridad,idPreguntasSeguridad',
            'preguntas.*.RespuestaSeguridad_hash' => 'required|string',
            'Usuarios_idUsuario' => 'required|exists:usuarios,idUsuario'
        ]);

        foreach ($request->preguntas as $pregunta) {
            // Buscar si existe una respuesta previa
            $respuestaExistente = Respuestasseguridad::where([
                'PreguntasSeguridad_idPreguntasSeguridad' => $pregunta['PreguntasSeguridad_idPreguntasSeguridad'],
                'Usuarios_idUsuario' => $request->Usuarios_idUsuario
            ])->first();

            if ($respuestaExistente) {
                // Actualizar respuesta existente
                $respuestaExistente->update([
                    'RespuestaSeguridad_hash' => Hash::make($pregunta['RespuestaSeguridad_hash'])
                ]);
            } else {
                // Crear nueva respuesta
                Respuestasseguridad::create([
                    'RespuestaSeguridad_hash' => Hash::make($pregunta['RespuestaSeguridad_hash']),
                    'PreguntasSeguridad_idPreguntasSeguridad' => $pregunta['PreguntasSeguridad_idPreguntasSeguridad'],
                    'Usuarios_idUsuario' => $request->Usuarios_idUsuario
                ]);
            }
        }

        return redirect()->back()->with('success', 'Respuestas actualizadas exitosamente');
    }

    public function verificarRespuesta(Request $request)
    {
        $request->validate([
            'respuesta' => 'required|string',
            'pregunta_id' => 'required|exists:preguntasseguridad,idPreguntasSeguridad',
            'usuario_id' => 'required|exists:usuarios,idUsuario'
        ]);

        $respuestaSeguridad = Respuestasseguridad::where('PreguntasSeguridad_idPreguntasSeguridad', $request->pregunta_id)
            ->where('Usuarios_idUsuario', $request->usuario_id)
            ->first();

        if(!$respuestaSeguridad) {
            return response()->json([
                'message' => 'No se encontró la respuesta de seguridad',
                'status' => 404
            ], 404);
        }

       if (Hash::check($request->respuesta, $respuestaSeguridad->RespuestaSeguridad_hash)) {
            return response()->json([
                'message' => 'Respuesta correcta',
                'status' => 200
            ]);
        }

        return response()->json([
            'message' => 'Respuesta incorrecta',
            'status' => 401
        ], 401);
    }

    public function show($id)
{
    $usuario = Usuario::find($id);
    $respuestasSeguridad = RespuestasSeguridad::where('Usuarios_idUsuario', $usuario->idUsuario)
        ->with('preguntaSeguridad')
        ->get();
    
    
    return view('perfil.show', [
        'usuario' => $usuario,
        'respuestasSeguridad' => $respuestasSeguridad,
        'preguntas' => PreguntasSeguridad::all(),
        'genero' => $usuario->genero
    ]);
}
}