<?php

namespace App\Http\Controllers;

use App\Models\Competidor;
use App\Models\Inscricao;
use App\Models\Senha;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalCompetidores = Competidor::count();
        $totalInscricoes = Inscricao::count();
        $totalSenhas = Senha::count();

        // Dados para gráfico: distribuição de pagamentos (por inscrição)
        $pagamentos = Inscricao::selectRaw('forma_pagamento, COUNT(*) as total')
            ->groupBy('forma_pagamento')
            ->pluck('total', 'pagamento')
            ->toArray();

        // Ajuste: chaves do gráfico devem bater com a coluna do select
        $pagamentos = Inscricao::selectRaw('forma_pagamento, COUNT(*) as total')
            ->groupBy('forma_pagamento')
            ->pluck('total', 'forma_pagamento')
            ->toArray();

        // Dados para gráfico: senhas por vaqueiro (competidor como vaqueiro na inscrição)
        $senhasPorVaqueiro = DB::table('senhas')
            ->join('inscricoes', 'inscricoes.id', '=', 'senhas.inscricao_id')
            ->join('competidores', 'competidores.id', '=', 'inscricoes.vaqueiro_id')
            ->select('competidores.nome', DB::raw('COUNT(senhas.id) as total'))
            ->groupBy('competidores.nome')
            ->orderBy('competidores.nome')
            ->pluck('total', 'competidores.nome')
            ->toArray();

        return view('dashboard', compact(
            'totalCompetidores',
            'totalInscricoes',
            'totalSenhas',
            'pagamentos',
            'senhasPorVaqueiro'
        ));
    }
}
