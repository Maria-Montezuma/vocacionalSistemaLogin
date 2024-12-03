<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegistroController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\RespuestaSeguridadController;
use App\Http\Controllers\RecuperarContrasenaController;

//Registro

Route::get('/', [RegistroController::class, 'showRegistrationForm'])->name('registro');
Route::post('/', [RegistroController::class, 'showRegistrationForm'])->name('registro.submit');
Route::post('registro', [AuthController::class, 'register'])->name('registro.submit');


Route::get('/verificar-correo', [RegistroController::class, 'verificarCorreo'])->name('verificar.correo');
Route::post('/verificar-correo', [RegistroController::class, 'verificarCorreo'])->name('verificar.correo');
Route::get('/verificar', [RegistroController::class, 'verificarCorreo'])->name('verificar.email');


// **Login**
Route::get('login', [LoginController::class, 'login'])->name('login.submit');
Route::post('login', [LoginController::class, 'login'])->name('login.submit');


//Perfil

Route::post('logout', [PerfilController::class, 'logout'])->name('logout');
Route::get('/perfil', [PerfilController::class, 'perfil'])->name('perfil');
Route::post('/perfil', [PerfilController::class, 'perfil'])->name('perfil');



//Respuestas de Seguridad

Route::post('/respuesta-seguridad/store', [RespuestaSeguridadController::class, 'store'])->name('respuesta-seguridad.store');


//Auth (Autenticacion)

Route::get('/verify-email/{token}', [AuthController::class, 'verificarEmail'])->name('verify.email');


Route::post('/guardar-respuestas', [AuthController::class, 'guardarRespuestasSeguridad'])->name('guardar.respuestas')->middleware('auth');
Route::get('/guardar-respuestas', [AuthController::class, 'guardarRespuestasSeguridad'])->name('guardar.respuestas')->middleware('auth');


//Recuperar Contrasena

Route::post('/verificar-correo-preguntas', [RecuperarContrasenaController::class, 'verificarCorreoPreguntas'])->name('verificar.correo.preguntas');

Route::post('/enviar-enlace-recuperacion', [RecuperarContrasenaController::class, 'enviarEnlaceRecuperacion'])->name('enviar.enlace.recuperacion');

Route::get('/reset-password/{token}', [RecuperarContrasenaController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [RecuperarContrasenaController::class, 'resetPassword'])->name('password.update');

Route::get('/mostrar-preguntas/{userId}', [RecuperarContrasenaController::class, 'mostrarPreguntasSeguridad'])->name('mostrar.preguntas');

Route::get('/preguntas-seguridad/{userId}', [RecuperarContrasenaController::class, 'mostrarFormularioPreguntas'])->name('preguntas.seguridad');

Route::get('/preguntas-seguridad/{userId}', [RecuperarContrasenaController::class, 'mostrarPreguntasSeguridad'])->name('preguntas.seguridad');

Route::get('/cambiar-contrasena', [RecuperarContrasenaController::class, 'mostrarFormularioCambioContrasena'])->name('mostrar.cambio.contrasena');

Route::get('/cambio-contrasena/{userId}', [RecuperarContrasenaController::class, 'mostrarFormularioCambioContrasena'])->name('cambio.contrasena');

Route::post('/cambiar-contrasena', [RecuperarContrasenaController::class, 'cambiarContrasena']);

Route::post('/validar-respuestas', [RecuperarContrasenaController::class, 'validarRespuestas'])->name('validar.respuestas');

Route::post('/verificar-respuestas-seguridad', [RecuperarContrasenaController::class, 'verificarRespuestasSeguridad'])->name('verificar.respuestas');

Route::post('/actualizar-contrasena', [RecuperarContrasenaController::class, 'actualizarContrasena'])->name('actualizar.contrasena');

// Mostrar formulario de recuperación
Route::get('recuperar-contraseña', function() {return view('recuperar-contrasena');})->name('recuperar-contraseña');

    





























Route::post('/enviar-enlace-recuperacion', [AuthController::class, 'enviarEnlaceRecuperacion'])->name('enviar.enlace.recuperacion');

Route::post('/register', [RegistroController::class, 'register'])->name('register');





 












// Página de perfil del usuario (restringida a usuarios autenticados)
// Route::get('perfil', [AuthController::class, 'showProfile'])->name('perfil')->middleware('auth');





// Route::post('/verificar-correo', [RecuperarContrasenaController::class, 'verificarCorreo']);






    












