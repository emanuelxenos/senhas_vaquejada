<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\VaqueiroController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
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

