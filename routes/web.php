<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CompetidorController;
use App\Http\Controllers\InscricaoController;
use App\Http\Controllers\SenhaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RedirectController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RelatorioController;
use App\Http\Controllers\PortalAuthController;
use App\Http\Controllers\PortalInscricaoController;

// Rota home (redirecionador)
Route::get('/', [RedirectController::class, 'index']);

// Webhooks de Pagamento (Público)
Route::post('/webhook/asaas', [\App\Http\Controllers\WebhookController::class, 'asaas'])
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class]);

// Rotas de autenticação administrativa (públicas)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.post')->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Rotas do Portal do Vaqueiro (Públicas / Guest)
Route::prefix('portal')->name('portal.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [PortalAuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [PortalAuthController::class, 'login'])->name('login.post');
        Route::get('/register', [PortalAuthController::class, 'showRegisterForm'])->name('register');
        Route::post('/register', [PortalAuthController::class, 'register'])->name('register.post');
    });

    Route::middleware('auth')->group(function () {
        Route::post('/logout', [PortalAuthController::class, 'logout'])->name('logout');
        
        // Apenas para Vaqueiros
        Route::get('/dashboard', [PortalInscricaoController::class, 'dashboard'])->name('dashboard');
        Route::get('/inscricoes/create', [PortalInscricaoController::class, 'create'])->name('inscricoes.create');
        Route::post('/inscricoes', [PortalInscricaoController::class, 'store'])->name('inscricoes.store');
        Route::get('/inscricoes/{inscricao}/pagamento', [PortalInscricaoController::class, 'pagamento'])->name('inscricoes.pagamento');
        Route::get('/inscricoes/{inscricao}/status', [PortalInscricaoController::class, 'checarStatus'])->name('inscricoes.status');
    });
});

// Rotas protegidas Administrativas (requerem autenticação e cargo não-vaqueiro)
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
    Route::get('/inscricoes/{inscricao}/pagamento', [InscricaoController::class, 'pagamento'])->name('inscricoes.pagamento');
    Route::get('/inscricoes/{inscricao}/termica', [InscricaoController::class, 'reciboTermico'])->name('inscricoes.termica');
    Route::get('/inscricoes/{inscricao}/status', [InscricaoController::class, 'checarStatus'])->name('inscricoes.status');
    Route::post('/inscricoes/{inscricao}/gerar-pix', [InscricaoController::class, 'gerarPixManual'])->name('inscricoes.gerarPix');
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

    // Central de Relatórios
    Route::prefix('relatorios')->name('relatorios.')->group(function () {
        Route::get('/geral', [RelatorioController::class, 'geral'])->name('geral');
        
        Route::get('/inscricoes', [RelatorioController::class, 'inscricoesForm'])->name('inscricoes');
        Route::post('/inscricoes/pdf', [RelatorioController::class, 'inscricoesPdf'])->name('inscricoes.pdf');
        
        Route::get('/senhas', [RelatorioController::class, 'senhasForm'])->name('senhas');
        Route::post('/senhas/pdf', [RelatorioController::class, 'senhasPdf'])->name('senhas.pdf');
    });

    // Configurações do sistema
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');

    // Usuários do sistema (Apenas Admin)
    Route::resource('users', UserController::class)->except(['show']);
});

