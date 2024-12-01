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
    
        // Si la validación falla, redirige con errores
    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    $token = Str::random(64);
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
 // En tu AuthController, modifica esta parte:
$token = Token::create([
    'Usuarios_idUsuario' => $usuario->idUsuario,
    'Token' => Str::random(64),
    'TipoToken' => 'verify',  // Cambiado de 'email_verification' a 'verify'
    'TiempoExpiracion' => now()->addHours(24),
    'Usado' => 0
]);

// Enviar email
// Enviar email
Mail::to($usuario->CorreoUsuario)->send(new VerifyEmail($usuario, $token->Token));


    // Redirigir al formulario de registro o mostrar un mensaje de éxito
    return redirect()->route('registro')->with('success', 'Por favor revisa tu email para verificar tu cuenta.');
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
        $request->validate([
            'CorreoUsuario' => 'required|email'
        ]);

        $usuario = Usuario::where('CorreoUsuario', $request->CorreoUsuario)->first();

        if ($usuario) {
            // Si encuentra el usuario, guarda el ID en la sesión para usarlo después
            session(['reset_email' => $usuario->CorreoUsuario]);
            
            return response()->json([
                'status' => 'success',
                'message' => 'Correo electrónico verificado correctamente'
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'No existe una cuenta con este correo electrónico'
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Error al verificar el correo: ' . $e->getMessage()
        ]);
    }
}


}