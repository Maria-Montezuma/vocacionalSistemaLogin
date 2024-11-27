<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


// Página principal (redirige al formulario de registro)
Route::get('/', [AuthController::class, 'showRegistrationForm'])->name('registro');
Route::post('/', [AuthController::class, 'showRegistrationForm'])->name('registro.submit');

// Procesar el registro
Route::post('registro', [AuthController::class, 'register'])->name('registro.submit');

// Mostrar formulario de login
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');

// Procesar el login
Route::post('login', [AuthController::class, 'login'])->name('login.submit');

// Mostrar formulario de recuperación de contraseña
Route::get('recuperar-contraseña', [AuthController::class, 'showPasswordRecoveryForm'])->name('recuperar-contraseña');

// Procesar la recuperación de contraseña
Route::post('recuperar-contraseña', [AuthController::class, 'processPasswordRecovery'])->name('recuperar-contraseña.submit');

// Página de perfil del usuario (restringida a usuarios autenticados)
Route::get('perfil', [AuthController::class, 'showProfile'])->name('perfil')->middleware('auth');

// Cerrar sesión
Route::post('logout', [AuthController::class, 'logout'])->name('logout');
