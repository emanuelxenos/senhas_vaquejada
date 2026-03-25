<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\VaqueiroController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RedirectController;
use App\Http\Controllers\SettingController;

// Rota home (redirecionador)
Route::get('/', [RedirectController::class, 'index']);

// Rotas de autenticação (públicas)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.post')->middleware('guest');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register')->middleware('guest');
Route::post('/register', [AuthController::class, 'register'])->name('register.post')->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Rotas protegidas (requerem autenticação)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/vaqueiros', [VaqueiroController::class, 'index'])->name('vaqueiros.index');
    Route::get('/vaqueiros/create', [VaqueiroController::class, 'create'])->name('vaqueiros.create');
    Route::post('/vaqueiros', [VaqueiroController::class, 'store'])->name('vaqueiros.store');
    Route::get('/vaqueiros/{vaqueiro}/edit', [VaqueiroController::class, 'edit'])->name('vaqueiros.edit');
    Route::put('/vaqueiros/{vaqueiro}', [VaqueiroController::class, 'update'])->name('vaqueiros.update');
    Route::delete('/vaqueiros/{vaqueiro}', [VaqueiroController::class, 'destroy'])->name('vaqueiros.destroy');

    Route::get('/senhas', [VaqueiroController::class, 'listarSenhas'])->name('senhas.index');
    Route::get('/senhas/create', [VaqueiroController::class, 'cadastrarSenhaForm'])->name('senhas.create');
    Route::post('/senhas', [VaqueiroController::class, 'storeSenhas'])->name('senhas.store');

    Route::get('/vaqueiros/{vaqueiro}/pdf', [VaqueiroController::class, 'gerarPdf'])->name('vaqueiros.pdf');
    Route::get('/relatorio', [VaqueiroController::class, 'relatorio'])->name('relatorio');

    // Configurações do sistema
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
});

