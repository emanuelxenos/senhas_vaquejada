<?php

namespace App\Http\Controllers;

use App\Models\Inscricao;
use App\Models\Senha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use PDF;

class SenhaController extends Controller
{
    public function index(Request $request)
    {
        $statusFiltro = $request->query('status', 'todos');

        $query = Senha::with('inscricao.vaqueiro', 'inscricao.bateEsteira');

        if ($statusFiltro && $statusFiltro !== 'todos') {
            $query->where('status', $statusFiltro);
        } else {
            $query->where('status', '!=', 'cancelado');
        }

        $senhas = $query->orderBy('numero_senha')->get();

        $total = $senhas->count();
        return view('senhas.index', compact('senhas', 'total', 'statusFiltro'));
    }

    public function create()
    {
        Gate::authorize('manage-cadastros');
        $inscricoes = Inscricao::with(['vaqueiro', 'bateEsteira'])
            ->withCount('senhas')
            // MySQL não permite usar coluna não agregada em HAVING sem GROUP BY.
            // Filtra via subquery no WHERE (senhas cadastradas < quantidade contratada).
            ->whereRaw(
                '(select count(*) from senhas where senhas.inscricao_id = inscricoes.id) < inscricoes.quantidade_senhas'
            )
            ->get();

        $senhasVendidas = Senha::where('status', '!=', 'cancelado')
            ->orderByRaw('CAST(numero_senha AS UNSIGNED) ASC') // Ordenação numérica inteligente
            ->pluck('numero_senha')
            ->toArray();

        return view('senhas.create', compact('inscricoes', 'senhasVendidas'));
    }

    public function store(Request $request)
    {
        Gate::authorize('manage-cadastros');
        $request->validate([
            'inscricao_id' => 'required|exists:inscricoes,id',
            'senhas' => 'required|array|min:1',
        ]);

        $inscricao = Inscricao::withCount('senhas')->findOrFail($request->input('inscricao_id'));

        $quantidade = (int) ($inscricao->quantidade_senhas ?? 0);
        $jaCadastradas = (int) ($inscricao->senhas_count ?? 0);
        $restantes = max($quantidade - $jaCadastradas, 0);

        $senhas = collect($request->input('senhas', []))
            ->map(fn ($v) => is_string($v) ? trim($v) : $v)
            ->filter(fn ($v) => $v !== null && $v !== '')
            ->values()
            ->all();

        if ($restantes === 0) {
            return redirect()->route('senhas.index')->with('sucesso', 'Esta inscrição já está completa.');
        }

        // Exigir exatamente a quantidade restante (evita “10 inputs vazios” quebrando o cadastro)
        if (count($senhas) !== $restantes) {
            return back()
                ->withErrors(['senhas' => "Você precisa cadastrar exatamente {$restantes} senha(s) para esta inscrição."])
                ->withInput();
        }

        // Validar conteúdo/uniqueness depois de filtrar vazios
        validator(
            ['senhas' => $senhas],
            ['senhas' => 'required|array|min:1', 'senhas.*' => 'required|string|max:50|distinct|unique:senhas,numero_senha']
        )->validate();

        foreach ($senhas as $numero) {
            Senha::create([
                'inscricao_id' => $inscricao->id,
                'numero_senha' => $numero,
                'status' => 'pendente'
            ]);
        }

        return redirect()->route('senhas.index')->with('sucesso', 'Senhas cadastradas com sucesso.');
    }

    public function edit(Senha $senha)
    {
        Gate::authorize('manage-cadastros');
        return view('senhas.edit', compact('senha'));
    }

    public function update(Request $request, Senha $senha)
    {
        Gate::authorize('update-status'); // nova regra que permite locutor

        $data = $request->validate([
            'numero_senha' => 'sometimes|required|string|max:50|unique:senhas,numero_senha,' . $senha->id,
            'status' => 'required|in:pendente,correu,boi_batido,cancelado',
            'motivo_cancelamento' => 'required_if:status,cancelado|nullable|string',
        ]);

        if ($data['status'] === 'cancelado') {
            if (auth()->check() && auth()->user()->isLocutor()) {
                abort(403, 'Acesso Negado: Locutores não podem cancelar senhas.');
            }
            $data['cancelado_por'] = auth()->check() ? auth()->user()->name : 'Usuário';
        } else {
            $data['cancelado_por'] = null;
            $data['motivo_cancelamento'] = null;
        }

        $senha->fill($data);
        $senha->save();

        return redirect()->route('senhas.index')->with('sucesso', 'Senha atualizada com sucesso.');
    }

    public function destroy(Senha $senha)
    {
        Gate::authorize('manage-cadastros');
        $senha->delete();
        return redirect()->route('senhas.index')->with('sucesso', 'Senha excluída com sucesso.');
    }

    public function gerarPdf(Inscricao $inscricao)
    {
        $senhas = $inscricao->senhas()->orderBy('numero_senha')->get();

        $pdf = PDF::loadView('pdf.vaqueiro', compact('inscricao', 'senhas'));
        $name = 'Senha_de_corrida_' . now()->format('d_m_Y') . '.pdf';

        return $pdf->stream($name);
    }

    public function relatorio()
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

        // Resumo rápido (substitui o conceito antigo de disponível/indisponível)
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

        // Data do relatório
        $dataRelatorio = now();

        // Passar todos os dados para a view
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
        $name = 'Relatorio_' . now()->format('d_m_Y_H_i_s') . '.pdf';

        return $pdf->stream($name);
    }
}
