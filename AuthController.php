<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Redessociale;
use App\Models\Genero;
use App\Models\Nacionalidade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function showRegistrationForm()
    {
        // Obtener todos los géneros desde la base de datos
        $generos = Genero::all();
        $nacionalidades = Nacionalidade::all(); 

        // Pasar la variable $generos a la vista
        return view('registro', compact('generos', 'nacionalidades'));
        return view('auth.registro');
    }
    public function register(Request $request)
    {
        
        // Validación de los datos del formulario
        $validator = Validator::make($request->all(), [
            'NombreUsuario' => 'required|string|max:255',
            'ApellidoUsuario' => 'required|string|max:255',
            'CorreoUsuario' => 'required|email|unique:usuarios,CorreoUsuario',
            'ContrasenaUsuario' => 'required|string|min:8|confirmed',
            'FechaNacimientoUsuario' => 'required|date',
            'CedulaUsuario' => 'required|numeric',
            'DireccionUsuario' => 'required|string|max:255',
            'DescripcionUsuario' => 'required|string|max:255',
            'Generos_idGenero' => 'required|exists:generos,idGenero',
            'Nacionalidades_idNacionalidad' => 'required|exists:nacionalidades,idNacionalidad',
            'sitio_web' => 'nullable|url',
            'facebook' => 'nullable|url',
            'instagram' => 'nullable|string',
            'twitter' => 'nullable|string',
            'tiktok' => 'nullable|string',
        ]);

        // Si la validación falla, redirige con errores
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Crear el usuario
        $usuario = Usuario::create([
            'NombreUsuario' => $request->NombreUsuario,
            'ApellidoUsuario' => $request->ApellidoUsuario,
            'CorreoUsuario' => $request->CorreoUsuario,
            'ContrasenaUsuario' => Hash::make($request->ContrasenaUsuario),
            'FechaNacimientoUsuario' => $request->FechaNacimientoUsuario,
            'CedulaUsuario' => $request->CedulaUsuario,
            'DireccionUsuario' => $request->DireccionUsuario,
            'DescripcionUsuario' => $request->DescripcionUsuario,
            'Generos_idGenero' => $request->Generos_idGenero,
            'Nacionalidades_idNacionalidad' => $request->Nacionalidades_idNacionalidad,
        ]);

        // Crear las redes sociales si existen
        if ($request->has('sitio_web')) {
            Redessociale::create([
                'WebPersonal' => $request->sitio_web,
                'Usuarios_idUsuario' => $usuario->idUsuario,
            ]);
        }

        // Registrar redes sociales si son proporcionadas
        if ($request->has('facebook')) {
            Redessociale::create([
                'RedSocial' => 'Facebook',
                'UrlRedSocial' => $request->facebook,
                'Usuarios_idUsuario' => $usuario->idUsuario,
            ]);
        }

        if ($request->has('instagram')) {
            Redessociale::create([
                'RedSocial' => 'Instagram',
                'UrlRedSocial' => $request->instagram,
                'Usuarios_idUsuario' => $usuario->idUsuario,
            ]);
        }

        if ($request->has('twitter')) {
            Redessociale::create([
                'RedSocial' => 'Twitter',
                'UrlRedSocial' => $request->twitter,
                'Usuarios_idUsuario' => $usuario->idUsuario,
            ]);
        }

        if ($request->has('tiktok')) {
            Redessociale::create([
                'RedSocial' => 'TikTok',
                'UrlRedSocial' => $request->tiktok,
                'Usuarios_idUsuario' => $usuario->idUsuario,
            ]);
        }

        // Redirigir al usuario a la página de perfil o login después del registro
        return redirect()->route('perfil');
    }
    
}
