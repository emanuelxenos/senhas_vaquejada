<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CompetidorController;
use App\Http\Controllers\InscricaoController;
use App\Http\Controllers\SenhaController;
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

    // Competidores
    Route::get('/competidores', [CompetidorController::class, 'index'])->name('competidores.index');
    Route::get('/competidores/create', [CompetidorController::class, 'create'])->name('competidores.create');
    Route::post('/competidores', [CompetidorController::class, 'store'])->name('competidores.store');
    Route::get('/competidores/{competidor}/edit', [CompetidorController::class, 'edit'])->name('competidores.edit');
    Route::put('/competidores/{competidor}', [CompetidorController::class, 'update'])->name('competidores.update');
    Route::delete('/competidores/{competidor}', [CompetidorController::class, 'destroy'])->name('competidores.destroy');

    // Inscrições
    Route::get('/inscricoes', [InscricaoController::class, 'index'])->name('inscricoes.index');
    Route::get('/inscricoes/create', [InscricaoController::class, 'create'])->name('inscricoes.create');
    Route::post('/inscricoes', [InscricaoController::class, 'store'])->name('inscricoes.store');
    Route::get('/inscricoes/{inscricao}/edit', [InscricaoController::class, 'edit'])->name('inscricoes.edit');
    Route::put('/inscricoes/{inscricao}', [InscricaoController::class, 'update'])->name('inscricoes.update');
    Route::delete('/inscricoes/{inscricao}', [InscricaoController::class, 'destroy'])->name('inscricoes.destroy');

    // Senhas
    Route::get('/senhas', [SenhaController::class, 'index'])->name('senhas.index');
    Route::get('/senhas/create', [SenhaController::class, 'create'])->name('senhas.create');
    Route::post('/senhas', [SenhaController::class, 'store'])->name('senhas.store');
    Route::get('/senhas/{senha}/edit', [SenhaController::class, 'edit'])->name('senhas.edit');
    Route::put('/senhas/{senha}', [SenhaController::class, 'update'])->name('senhas.update');
    Route::delete('/senhas/{senha}', [SenhaController::class, 'destroy'])->name('senhas.destroy');

    Route::get('/inscricoes/{inscricao}/pdf', [SenhaController::class, 'gerarPdf'])->name('inscricoes.pdf');
    Route::get('/relatorio', [SenhaController::class, 'relatorio'])->name('relatorio');

    // Configurações do sistema
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
});

