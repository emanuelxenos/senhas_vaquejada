<?php

namespace App\Http\Controllers;

use App\Models\Vaqueiro;
use App\Models\Senha;

class DashboardController extends Controller
{
    public function index()
    {
        $totalVaqueiros = Vaqueiro::count();
        $totalSenhas = Senha::count();
        $vaqueirosDisponiveis = Vaqueiro::where('disponivel', 'sim')->count();
        $vaqueirosIndisponiveis = Vaqueiro::where('disponivel', 'nao')->count();

        // Dados para gráfico: distribuição de pagamentos
        $pagamentos = Vaqueiro::selectRaw('pagamento, COUNT(*) as total')
            ->groupBy('pagamento')
            ->pluck('total', 'pagamento')
            ->toArray();

        // Dados para gráfico: senhas por vaqueiro
        $senhasPorVaqueiro = Vaqueiro::withCount('senhas')
            ->get()
            ->pluck('senhas_count', 'nome')
            ->toArray();

        return view('dashboard', compact(
            'totalVaqueiros',
            'totalSenhas',
            'vaqueirosDisponiveis',
            'vaqueirosIndisponiveis',
            'pagamentos',
            'senhasPorVaqueiro'
        ));
    }
}
