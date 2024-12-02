<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RespuestaSeguridadController;
use App\Http\Controllers\RecuperarContrasenaController;
use App\Mail\RecuperacionContrasena;

// Página principal (redirige al formulario de registro)
Route::get('/', [AuthController::class, 'showRegistrationForm'])->name('registro');
Route::post('/', [AuthController::class, 'showRegistrationForm'])->name('registro.submit');

// Procesar el registro
Route::post('registro', [AuthController::class, 'register'])->name('registro.submit');
Route::get('/registro', [AuthController::class, 'showRegistrationForm'])->name('registro');

// Procesar el login
Route::get('login', [AuthController::class, 'login'])->name('login.submit');
Route::post('login', [AuthController::class, 'login'])->name('login.submit');

// Página de perfil del usuario (restringida a usuarios autenticados)
Route::get('perfil', [AuthController::class, 'showProfile'])->name('perfil')->middleware('auth');

// Cerrar sesión
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// Ruta para el perfil
Route::get('/perfil', [AuthController::class, 'perfil'])->name('perfil');


// Route::post('/verificar-correo', [RecuperarContrasenaController::class, 'verificarCorreo']);
Route::post('/enviar-enlace-recuperacion', [AuthController::class, 'enviarEnlaceRecuperacion'])->name('enviar.enlace.recuperacion');

Route::get('verify-email/{token}', [AuthController::class, 'verificarEmail'])->name('verify.email');


Route::post('/register', [AuthController::class, 'register'])->name('register');

Route::post('/verificar-correo', [AuthController::class, 'verificarCorreo'])->name('verificar.correo');


// Mostrar formulario de recuperación
Route::get('recuperar-contraseña', function() {
    return view('recuperar-contrasena');
})->name('recuperar-contraseña');

// Procesar el envío del enlace
Route::post('/enviar-enlace-recuperacion', [RecuperarContrasenaController::class, 'enviarEnlaceRecuperacion'])
    ->name('enviar.enlace.recuperacion');

// Mostrar formulario de reset
Route::get('/reset-password/{token}', [RecuperarContrasenaController::class, 'showResetForm'])
    ->name('password.reset');

// Procesar el reset
Route::post('/reset-password', [RecuperarContrasenaController::class, 'resetPassword'])
    ->name('password.update');

    


    Route::post('/respuesta-seguridad/store', [RespuestaSeguridadController::class, 'store'])->name('respuesta-seguridad.store');



    Route::get('/perfil', [AuthController::class, 'perfil'])->name('perfil');
    Route::post('/perfil', [AuthController::class, 'perfil'])->name('perfil');
    // Guardar respuestas de seguridad
    Route::post('/guardar-respuestas', [AuthController::class, 'guardarRespuestasSeguridad'])->name('guardar.respuestas')->middleware('auth');
    Route::get('/guardar-respuestas', [AuthController::class, 'guardarRespuestasSeguridad'])->name('guardar.respuestas')->middleware('auth');
    // routes/web.php

    Route::post('/verificar-correo-preguntas', [RecuperarContrasenaController::class, 'verificarCorreoPreguntas'])->name('verificar.correo.preguntas');

    Route::get('/preguntas-seguridad/{userId}', [RecuperarContrasenaController::class, 'mostrarPreguntasSeguridad'])->name('preguntas.seguridad');

    Route::post('/verificar-respuestas-seguridad', [RecuperarContrasenaController::class, 'verificarRespuestasSeguridad'])->name('verificar.respuestas');

    // Si es una ruta web
Route::get('/mostrar-preguntas/{userId}', [RecuperarContrasenaController::class, 'mostrarPreguntasSeguridad'])->name('mostrar.preguntas');

Route::post('/validar-respuestas', [RecuperarContrasenaController::class, 'validarRespuestas']);
Route::post('/cambiar-contrasena', [RecuperarContrasenaController::class, 'cambiarContrasena']);

Route::post('/validar-respuestas', [RecuperarContrasenaController::class, 'validarRespuestas'])->name('validar.respuestas');

Route::get('/preguntas-seguridad/{userId}', [RecuperarContrasenaController::class, 'mostrarFormularioPreguntas'])->name('preguntas.seguridad');
Route::post('/validar-respuestas', [RecuperarContrasenaController::class, 'validarRespuestas'])->name('validar.respuestas');

Route::get('/cambio-contrasena/{userId}', [RecuperarContrasenaController::class, 'mostrarFormularioCambioContrasena'])->name('cambio.contrasena');
Route::post('/actualizar-contrasena', [RecuperarContrasenaController::class, 'actualizarContrasena'])->name('actualizar.contrasena');


// Ruta para mostrar el formulario
Route::get('/cambiar-contrasena', [RecuperarContrasenaController::class, 'mostrarFormularioCambioContrasena'])->name('mostrar.cambio.contrasena');

// Ruta para procesar el cambio de contraseña
Route::post('/actualizar-contrasena', [RecuperarContrasenaController::class, 'actualizarContrasena'])->name('actualizar.contrasena');



Route::get('/verificar', [AuthController::class, 'verificarCorreo'])->name('verificar.email');

