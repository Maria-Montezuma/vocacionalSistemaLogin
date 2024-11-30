<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Redessociale;
use App\Models\Genero;
use App\Models\Nacionalidade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


class AuthController extends Controller
{
    public function showRegistrationForm()
    {
        // Obtener todos los géneros desde la base de datos
        $generos = Genero::all();
        $nacionalidades = Nacionalidade::all(); 

        // Pasar la variable $generos a la vista
        return view('registro', compact('generos', 'nacionalidades'));
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
            'CedulaUsuario' => 'required|numeric|unique:usuarios,CedulaUsuario',
            'Nacionalidades_idNacionalidad' => [
        'required', 
        'exists:nacionalidades,idNacionalidad',
        // Agrega esta nueva regla personalizada
        function($attribute, $value, $fail)  use ($request) {
            // Verifica si ya existe un usuario con esta nacionalidad
            $existingUser = Usuario::where('CorreoUsuario', $request->CorreoUsuario)
                ->where('Nacionalidades_idNacionalidad', $value)
                ->first();
            
            if ($existingUser) {
                $fail('Ya existe un usuario registrado con esta nacionalidad.');
            }
        }
    ],
            'DireccionUsuario' => 'required|string|max:255',
            'DescripcionUsuario' => 'required|string|max:255',
            'Generos_idGenero' => 'required|exists:generos,idGenero',
            'Nacionalidades_idNacionalidad' => 'required|exists:nacionalidades,idNacionalidad',
            'sitio_web' => 'nullable|url',
            'facebook' => 'nullable|url',
            'instagram' => 'nullable|string',
            'twitter' => 'nullable|string',
            'tiktok' => 'nullable|string',
        ], [
            'NombreUsuario.required' => 'El nombre de usuario es obligatorio.',
            'NombreUsuario.string' => 'El nombre de usuario debe ser un texto.',
            'NombreUsuario.max' => 'El nombre de usuario no puede exceder los 255 caracteres.',
            
            'ApellidoUsuario.required' => 'El apellido de usuario es obligatorio.',
            'ApellidoUsuario.string' => 'El apellido de usuario debe ser un texto.',
            'ApellidoUsuario.max' => 'El apellido de usuario no puede exceder los 255 caracteres.',
            
            'CorreoUsuario.required' => 'El correo electrónico es obligatorio.',
            'CorreoUsuario.email' => 'El correo electrónico debe tener un formato válido.',
            'CorreoUsuario.unique' => 'El correo electrónico ya está registrado.',
            
            'ContrasenaUsuario.required' => 'La contraseña es obligatoria.',
            'ContrasenaUsuario.string' => 'La contraseña debe ser un texto.',
            'ContrasenaUsuario.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'ContrasenaUsuario.confirmed' => 'Las contraseñas no coinciden.',
            
            'FechaNacimientoUsuario.required' => 'La fecha de nacimiento es obligatoria.',
            'FechaNacimientoUsuario.date' => 'La fecha de nacimiento debe ser una fecha válida.',
            
            'CedulaUsuario.required' => 'La cédula es obligatoria.',
            'CedulaUsuario.numeric' => 'La cédula debe ser un número.',
            'CedulaUsuario.unique' => 'La cédula ingresada ya está registrada. Por favor, ingrese una cédula diferente.',
            
            'DireccionUsuario.required' => 'La dirección es obligatoria.',
            'DireccionUsuario.string' => 'La dirección debe ser un texto.',
            'DireccionUsuario.max' => 'La dirección no puede exceder los 255 caracteres.',
            
            'DescripcionUsuario.required' => 'La descripción es obligatoria.',
            'DescripcionUsuario.string' => 'La descripción debe ser un texto.',
            'DescripcionUsuario.max' => 'La descripción no puede exceder los 255 caracteres.',
            
            'Generos_idGenero.required' => 'El género es obligatorio.',
            'Generos_idGenero.exists' => 'El género seleccionado no es válido.',
            
            'Nacionalidades_idNacionalidad.required' => 'La nacionalidad es obligatoria.',
            'Nacionalidades_idNacionalidad.exists' => 'La nacionalidad seleccionada no es válida.',
            'Nacionalidades_idNacionalidad.required' => 'La nacionalidad es obligatoria.',
            
            'sitio_web.url' => 'El sitio web debe tener un formato de URL válido.',
            'sitio_web.unique' => 'El sitio web ya está registrado. Por favor, ingrese una URL diferente.',
            
            'facebook.url' => 'El enlace de Facebook debe tener un formato de URL válido.',
            'facebook.unique' => 'El enlace de Facebook ya está registrado.',
            
            'instagram.string' => 'El enlace de Instagram debe ser un texto.',
            'instagram.unique' => 'El enlace de Instagram ya está registrado.',
            
            'twitter.string' => 'El enlace de Twitter debe ser un texto.',
            'twitter.unique' => 'El enlace de Twitter ya está registrado.',
            
            'tiktok.string' => 'El enlace de TikTok debe ser un texto.',
            'tiktok.unique' => 'El enlace de TikTok ya está registrado.',
        ]);

        // Si la validación falla, redirige con errores
    
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

    // Redirigir al formulario de registro o mostrar un mensaje de éxito
    return redirect()->route('registro')->with('success', 'Registro completado exitosamente');
}


    public function login(Request $request)
    {
        $request->validate([
            'CorreoUsuario' => 'required|email',
            'ContrasenaUsuario' => 'required'
        ]);

        $usuario = Usuario::where('CorreoUsuario', $request->CorreoUsuario)->first();

        if ($usuario && Hash::check($request->ContrasenaUsuario, $usuario->ContrasenaUsuario)) {
            session([
                'usuario_id' => $usuario->idUsuario,
                'usuario_nombre' => $usuario->NombreUsuario,
                'usuario_email' => $usuario->CorreoUsuario
            ]);
            
            return redirect()->route('perfil');
        }

        return back()->withErrors([
            'CorreoUsuario' => 'Credenciales incorrectas'
        ])->withInput();
    }

    public function perfil()
    {
        return view('perfil');
    }

    public function logout()
    {
        session()->forget(['usuario_id', 'usuario_nombre', 'usuario_email']);
        return redirect()->route('registro');
    }


}