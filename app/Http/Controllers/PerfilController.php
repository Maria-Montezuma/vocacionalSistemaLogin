<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Preguntasseguridad;


class PerfilController extends Controller
{
    //Funcion de perfil
    public function perfil()
    {
        
        $usuarioId = session('usuario_id');

        if (!$usuarioId) {
            return redirect()->route('login')->with('error', 'Por favor, inicia sesión primero.');
        }

        $usuario = Usuario::with(['genero'])
            ->where('idUsuario', $usuarioId)
            ->first();

        if (!$usuario) {
            return redirect()->route('login')->with('error', 'Usuario no encontrado');
        }

        $preguntas = Preguntasseguridad::all();

        return view('perfil', [
            'usuario' => $usuario,
            'genero' => $usuario->genero->NombreGenero ?? 'No especificado',
            'preguntas' => $preguntas 
        ]);
    }
    

    public function logout()
    {
        session()->forget(['idUsuario', 'NombreUsuario', 'CorreoUsuario']);
        return redirect()->route('registro');
    }

}
