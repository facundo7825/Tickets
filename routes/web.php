<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ChangePasswordController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SettingController;
use Illuminate\Support\Facades\Route;

// Ruta raíz → redirigir al login
Route::get('/', fn () => redirect()->route('login'));

// Autenticación (solo para invitados)
Route::middleware('guest')->group(function () {
    Route::get('/login',  [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    Route::get('/forgot-password',         fn () => view('auth.forgot-password'))->name('password.request');
    Route::post('/forgot-password',        [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}',  fn ($token) => view('auth.reset-password', ['request' => request()]))->name('password.reset');
    Route::post('/reset-password',         [\App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])->name('password.store');
});

// Logout
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Cambio de contraseña obligatorio (accesible estando autenticado, incluso con must_change_password=true)
Route::middleware('auth')->group(function () {
    Route::get('/change-password',  [ChangePasswordController::class, 'showChangeForm'])->name('password.change');
    Route::post('/change-password', [ChangePasswordController::class, 'update'])->name('password.change.update');
});

// Área protegida (requiere auth + contraseña ya cambiada)
Route::middleware(['auth', \App\Http\Middleware\ForcePasswordChange::class])->group(function () {
    Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard');

    Route::get('/tickets',         [TicketController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/create',  [TicketController::class, 'create'])->name('tickets.create');
    Route::post('/tickets',        [TicketController::class, 'store'])->name('tickets.store');
    Route::get('/tickets/{id}',    [TicketController::class, 'show'])->name('tickets.show');

    Route::get('/users',   [UserController::class,   'index'])->name('users.index');
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/profile',       [ProfileController::class,     'index'])->name('profile');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/settings',      [SettingController::class,      'index'])->name('settings.index');
});
