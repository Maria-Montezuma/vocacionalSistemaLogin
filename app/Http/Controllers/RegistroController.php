<?php

namespace App\Http\Controllers;

use App\Mail\VerifyEmail;  
use App\Models\Usuario;
use App\Models\Genero;
use App\Models\Token;
use App\Models\Nacionalidade;
use App\Models\Redessociale;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;


class RegistroController extends Controller
{
   
     //Obtener los generos y nacionalidades 
     public function showRegistrationForm()
     {
         $generos = Genero::all();
         $nacionalidades = Nacionalidade::all(); 
       
         return view('registro', compact('generos', 'nacionalidades'));
     } 

    //Funcion de registro 
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
            'Nacionalidades_idNacionalidad' => ['required', 'exists:nacionalidades,idNacionalidad',

            function($attribute,$value, $fail)  use ($request) {
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
            'FechaNacimientoUsuario' => 'required|date|before_or_equal:' . now()->subYears(15)->toDateString(),
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

            'FechaNacimientoUsuario' => 'Usted no cumple con el requisito de edad para registrarse',
            
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
        'verificado' => 0,  
    ]);
    
    if (!$usuario || !$usuario->idUsuario) {  
        return redirect()->back()->with('error', 'Error al crear el usuario');
    }

    // Crear las redes sociales si existen
    if ($request->has('sitio_web')) {
        Redessociale::create([
            'WebPersonal' => $request->sitio_web,
            'Usuarios_idUsuario' => $usuario->idUsuario,
        ]);
    }

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

    // Crear el token de verificación
    $token = Token::create([
        'Usuarios_idUsuario' => $usuario->idUsuario,
        'Token' => Str::random(64),
        'TipoToken' => 'verify',  
        'TiempoExpiracion' => now()->addHours(24),  
        'Usado' => 0,  
    ]);


    Mail::to($usuario->CorreoUsuario)->send(new VerifyEmail($usuario, $token->Token));


    return redirect()->route('registro')->with('success', 'Por favor revisa tu email para verificar tu cuenta.');
}


public function verificarCorreo(Request $request)
{
    try {
        $request->validate([
            'token' => 'required|string',
        ]);

        $tokenRecord = Token::where('Token', $request->token)
            ->where('TipoToken', 'verify')  
            ->where('Usado', 0) 
            ->first();

        if (!$tokenRecord) {
            return redirect()->route('registro')->with('error', 'Token inválido o ya utilizado');
        }

        if ($tokenRecord->TiempoExpiracion < now()) {
            return redirect()->route('registro')->with('error', 'El token ha expirado');
        }

        $usuario = Usuario::find($tokenRecord->Usuarios_idUsuario);

        if (!$usuario) {
            return redirect()->route('registro')->with('error', 'Usuario no encontrado');
        }

        $usuario->update([
            'verificado' => 1,  
        ]);

        $tokenRecord->update(['Usado' => 1]);

        return redirect()->route('registro')->with('success', 'Correo electrónico verificado correctamente. Ahora puedes iniciar sesión.');
        
    } catch (\Exception $e) {
        return redirect()->route('registro')->with('error', 'Error al verificar el correo: ' . $e->getMessage());
    }
}
}

