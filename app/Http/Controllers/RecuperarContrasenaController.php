<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;

class RecuperarContrasenaController extends Controller
{
    public function verificarCorreo(Request $request)
    {
        $request->validate([
            'CorreoUsuario' => 'required|email|exists:idUsuario,CorreoUsuario', // Cambia 'usuarios' y 'email' según tu base de datos
        ]);
    
        return response()->json([
            'status' => 'success',
            'message' => 'Correo verificado correctamente.',
        ]);
    }


    
}

