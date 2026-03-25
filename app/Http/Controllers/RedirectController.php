<?php

namespace App\Http\Controllers;

class RedirectController extends Controller
{
    /**
     * Redireciona para dashboard se autenticado, ou para login se não
     */
    public function index()
    {
        if (auth()->check()) {
            return redirect()->route('dashboard');
        }

        return redirect()->route('login');
    }
}
