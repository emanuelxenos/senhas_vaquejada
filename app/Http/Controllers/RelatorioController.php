<?php

namespace App\Http\Controllers;

use App\Models\Inscricao;
use App\Models\Senha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use PDF;

class RelatorioController extends Controller
{
    // ============================================
    // RELATÓRIO GERAL (Antigo)
    // ============================================
    public function geral()
    {
        Gate::authorize('view-reports');
        // Buscar inscrições com contagem de senhas (ignorando canceladas)
        $inscricoes = Inscricao::with(['vaqueiro', 'bateEsteira', 'senhas' => function($query) {
                $query->where('status', '!=', 'cancelado');
            }])
            ->withCount(['senhas' => function($query) {
                $query->where('status', '!=', 'cancelado');
            }])
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculando estatísticas
        $totalInscricoes = $inscricoes->count();
        $totalSenhas = $inscricoes->sum('senhas_count');

        // Contar por tipo de pagamento
        $pagamentoStats = $inscricoes->groupBy('forma_pagamento')
            ->map(function($group) {
                return $group->count();
            });

        // Status de pagamento
        $pagamentoStatus = $inscricoes->groupBy('status_pagamento')
            ->map(function($group) {
                return $group->count();
            });

        // Resumo rápido
        $disponiveis = (int) ($pagamentoStatus['pago'] ?? 0);
        $indisponiveis = (int) ($pagamentoStatus['pendente'] ?? 0) + (int) ($pagamentoStatus['cancelado'] ?? 0);

        // Status das senhas
        $senhaStatus = collect();
        foreach ($inscricoes as $inscricao) {
            foreach ($inscricao->senhas as $senha) {
                $status = $senha->status;
                $senhaStatus[$status] = ($senhaStatus[$status] ?? 0) + 1;
            }
        }

        $dataRelatorio = now();

        $dados = compact(
            'inscricoes',
            'totalInscricoes',
            'totalSenhas',
            'pagamentoStats',
            'pagamentoStatus',
            'disponiveis',
            'indisponiveis',
            'senhaStatus',
            'dataRelatorio'
        );

        $pdf = PDF::loadView('pdf.relatorio', $dados);
        $pdf->setPaper('A4', 'portrait');
        $name = 'Relatorio_Geral_' . now()->format('d_m_Y_H_i') . '.pdf';

        return $pdf->stream($name);
    }

    // ============================================
    // RELATÓRIO DE INSCRIÇÕES
    // ============================================
    public function inscricoesForm()
    {
        // Administradores e Secretários podem gerar
        Gate::authorize('manage-cadastros');
        $categorias = \App\Models\Categoria::orderBy('nome', 'asc')->get();
        return view('relatorios.inscricoes', compact('categorias'));
    }

    public function inscricoesPdf(Request $request)
    {
        Gate::authorize('manage-cadastros');
        $status = $request->input('status_pagamento', 'todos');
        $categoriaId = $request->input('categoria_id', 'todos');

        $query = Inscricao::with(['vaqueiro', 'bateEsteira', 'categoria'])->withCount('senhas');

        if ($status !== 'todos') {
            $query->where('status_pagamento', $status);
        }

        if ($categoriaId !== 'todos') {
            $query->where('categoria_id', $categoriaId);
        }

        $inscricoes = $query->orderBy('created_at', 'desc')->get();
        $totalValor = $inscricoes->sum('valor_total');
        
        $dataRelatorio = now();
        $categoriaSelecionada = $categoriaId !== 'todos' ? \App\Models\Categoria::find($categoriaId) : null;

        $pdf = PDF::loadView('pdf.relatorio_inscricoes', compact('inscricoes', 'totalValor', 'status', 'dataRelatorio', 'categoriaSelecionada'));
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream('Inscricoes_' . ucfirst($status) . '_' . now()->format('d_m_Y') . '.pdf');
    }

    // ============================================
    // RELATÓRIO DE SENHAS
    // ============================================
    public function senhasForm()
    {
        Gate::authorize('manage-cadastros');
        $categorias = \App\Models\Categoria::orderBy('nome', 'asc')->get();
        return view('relatorios.senhas', compact('categorias'));
    }

    public function senhasPdf(Request $request)
    {
        Gate::authorize('manage-cadastros');
        $status = $request->input('status_senha', 'todos');
        $categoriaId = $request->input('categoria_id', 'todos');

        $query = Senha::with(['inscricao.vaqueiro', 'inscricao.bateEsteira', 'inscricao.categoria']);

        if ($status !== 'todos') {
            $query->where('status', $status);
        }

        if ($categoriaId !== 'todos') {
            $query->whereHas('inscricao', function($q) use ($categoriaId) {
                $q->where('categoria_id', $categoriaId);
            });
        }

        $senhas = $query->orderBy('numero_senha', 'asc')->get();
        $totalSenhas = $senhas->count();
        $dataRelatorio = now();
        $categoriaSelecionada = $categoriaId !== 'todos' ? \App\Models\Categoria::find($categoriaId) : null;

        $pdf = PDF::loadView('pdf.relatorio_senhas', compact('senhas', 'totalSenhas', 'status', 'dataRelatorio', 'categoriaSelecionada'));
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream('Senhas_' . ucfirst(str_replace('_', '', $status)) . '_' . now()->format('d_m_Y') . '.pdf');
    }
}
