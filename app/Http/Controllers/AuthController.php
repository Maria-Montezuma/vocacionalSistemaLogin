<?php

namespace App\Http\Controllers;
use Illuminate\Support\Str; // Agregar al inicio
use App\Mail\VerifyEmail;  
use Illuminate\Support\Facades\Mail;
use App\Models\Usuario;
use App\Models\Token;
use App\Models\Redessociale;
use App\Models\Genero;
use App\Models\Nacionalidade;
use App\Models\Preguntasseguridad;
use App\Models\Respuestasseguridad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Mail\RecuperacionPassword;
use Illuminate\Support\Facades\DB; // Añade esta línea
use Illuminate\Validation\Rule;


class AuthController extends Controller
{
    public function showRegistrationForm()
    {
        // Obtener todos los géneros desde la base de datos
        $generos = Genero::all();
        $nacionalidades = Nacionalidade::all(); 
        $preguntas= Preguntasseguridad:: all();
      
        // Pasar la variable $generos a la vista
        return view('registro', compact('generos', 'nacionalidades', 'preguntas'));
    }


    public function verificarEmail($token)
    {
        $token = Token::where('Token', $token)
                     ->where('TipoToken', 'verify')
                     ->where('Usado', 0)
                     ->where('TiempoExpiracion', '>', now())
                     ->first();

        if (!$token) {
            return redirect()->route('login')
                ->with('error', 'Token de verificación inválido o expirado');
        }

        $usuario = $token->usuario;
        
        // Marcar el token como usado
        $token->Usado = 1;
        $token->save();

        return redirect()->route('login')
            ->with('success', 'Email verificado correctamente. Ya puedes iniciar sesión.');
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
        'FechaNacimientoUsuario' => 'required|date|before_or_equal:' . now()->subYears(15)->toDateString(),
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
        'verificado' => 0,  // Usuario no verificado por defecto
    ]);

    if (!$usuario || !$usuario->idUsuario) {  // Asegúrate que este es el nombre correcto de tu columna ID
        return redirect()->back()->with('error', 'Error al crear el usuario');
    }

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

    // Crear el token de verificación
    $token = Token::create([
        'Usuarios_idUsuario' => $usuario->idUsuario,
        'Token' => Str::random(64),
        'TipoToken' => 'verify',  // 'verify' es adecuado para indicar que es un token de verificación de email
        'TiempoExpiracion' => now()->addHours(24),  // Expira en 24 horas
        'Usado' => 0,  // Indica que no ha sido utilizado aún
    ]);

    // Enviar el correo de verificación
    Mail::to($usuario->CorreoUsuario)->send(new VerifyEmail($usuario, $token->Token));

    // Redirigir al formulario de registro con un mensaje
    return redirect()->route('registro')->with('success', 'Por favor revisa tu email para verificar tu cuenta.');
}

public function login(Request $request)
{
    $request->validate([
        'CorreoUsuario' => 'required|email',
        'ContrasenaUsuario' => 'required'
    ]);

    $usuario = Usuario::where('CorreoUsuario', $request->CorreoUsuario)->first();

    if ($usuario) {
        // Verificar si el usuario está verificado
        if ($usuario->verificado == 0) {
            return back()->withErrors([
                'CorreoUsuario' => 'Debes verificar tu correo electrónico antes de iniciar sesión.'
            ])->withInput();
        }

        if (Hash::check($request->ContrasenaUsuario, $usuario->ContrasenaUsuario)) {
            session([
                'usuario_id' => $usuario->idUsuario,
                'usuario_nombre' => $usuario->NombreUsuario,
                'usuario_email' => $usuario->CorreoUsuario
            ]);

            return redirect()->route('perfil');
        }
    }

    return back()->withErrors([
        'CorreoUsuario' => 'Credenciales incorrectas'
    ])->withInput();
}

public function perfil()
{
    // Obtener el ID del usuario de la sesión
    $usuarioId = session('usuario_id');

    // Buscar el usuario con su relación de género
    $usuario = Usuario::with(['genero'])
        ->where('idUsuario', $usuarioId)
        ->first();

    // Verificar si el usuario existe
    if (!$usuario) {
        return redirect()->route('login')->with('error', 'Usuario no encontrado');
    }

    // Obtener todas las preguntas de seguridad desde la base de datos
    $preguntas = Preguntasseguridad::all();

    // Pasar los datos del usuario y las preguntas de seguridad a la vista
    return view('perfil', [
        'usuario' => $usuario,
        'genero' => $usuario->genero->NombreGenero ?? 'No especificado',
        'preguntas' => $preguntas // Pasa la variable $preguntas
    ]);
}



public function logout()
{
    // Eliminar datos de sesión
    session()->forget(['idUsuario', 'NombreUsuario', 'CorreoUsuario']);
    
    // Redirigir a la página de registro
    return redirect()->route('registro');
}

public function showPasswordRecoveryForm()
{
    return view('recuperar-contrasena'); // Ajusta la ruta de la vista según sea necesario
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

    // Si la validación falla, redirigir con errores
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

    // Redirigir al perfil con éxito
    return redirect()->route('perfil')->with('success', 'Respuestas de seguridad guardadas correctamente');
}
// AuthController.php


public function verificarCorreo(Request $request)
{
    try {
        // Validar que el token sea proporcionado
        $request->validate([
            'token' => 'required|string',
        ]);

        // Buscar el token en la base de datos
        $tokenRecord = Token::where('Token', $request->token)
            ->where('TipoToken', 'verify')  // Asegúrate de que el token sea de tipo "verify"
            ->where('Usado', 0)  // Verifica que no haya sido usado
            ->first();

        if (!$tokenRecord) {
            // Si no encontramos un token válido
            return redirect()->route('registro')->with('error', 'Token inválido o ya utilizado');
        }

        // Verificar si el token ha expirado
        if ($tokenRecord->TiempoExpiracion < now()) {
            return redirect()->route('registro')->with('error', 'El token ha expirado');
        }

        // Encontrar al usuario asociado con este token
        $usuario = Usuario::find($tokenRecord->Usuarios_idUsuario);

        if (!$usuario) {
            return redirect()->route('registro')->with('error', 'Usuario no encontrado');
        }

        // Actualizar el estado del usuario para marcarlo como verificado (asumiendo que tienes un campo "verificado")
        $usuario->update([
            'verificado' => 1,  // 1 significa verificado
        ]);

        // Marcar el token como utilizado
        $tokenRecord->update(['Usado' => 1]);

        // Redirigir al login con un mensaje de éxito
        return redirect()->route('registro')->with('success', 'Correo electrónico verificado correctamente. Ahora puedes iniciar sesión.');
        
    } catch (\Exception $e) {
        return redirect()->route('registro')->with('error', 'Error al verificar el correo: ' . $e->getMessage());
    }
}

}