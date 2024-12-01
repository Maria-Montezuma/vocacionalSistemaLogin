<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RespuestaSeguridadController;
use App\Http\Controllers\RecuperarContrasenaController;


// Página principal (redirige al formulario de registro)
Route::get('/', [AuthController::class, 'showRegistrationForm'])->name('registro');
Route::post('/', [AuthController::class, 'showRegistrationForm'])->name('registro.submit');

// Procesar el registro
Route::post('registro', [AuthController::class, 'register'])->name('registro.submit');
Route::get('/registro', [AuthController::class, 'showRegistrationForm'])->name('registro');

// // Mostrar formulario de login
// Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
// Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');

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