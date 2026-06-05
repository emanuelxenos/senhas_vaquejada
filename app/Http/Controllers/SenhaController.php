<?php

namespace App\Http\Controllers;

use App\Models\Inscricao;
use App\Models\Senha;
use App\Models\Corrida;
use App\Models\Setting;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use PDF;

class SenhaController extends Controller
{
    public function index(Request $request)
    {
        $statusFiltro = $request->query('status', 'todos');
        $categoriaFiltro = $request->query('categoria_id', 'todas');

        $query = Senha::with(['inscricao.vaqueiro', 'inscricao.bateEsteira', 'inscricao.categoria', 'corridas']);

        if ($statusFiltro && $statusFiltro !== 'todos') {
            $query->where('status', $statusFiltro);
        } else {
            $query->where('status', '!=', 'cancelado');
        }

        if ($categoriaFiltro && $categoriaFiltro !== 'todas') {
            $query->whereHas('inscricao', function ($q) use ($categoriaFiltro) {
                $q->where('categoria_id', $categoriaFiltro);
            });
        }

        $senhas = $query->orderByRaw('CAST(numero_senha AS UNSIGNED) ASC')->get();

        $total = $senhas->count();
        $categorias = Categoria::orderBy('nome')->get();
        return view('senhas.index', compact('senhas', 'total', 'statusFiltro', 'categoriaFiltro', 'categorias'));
    }

    public function create()
    {
        Gate::authorize('manage-cadastros');
        $inscricoes = Inscricao::with(['vaqueiro', 'bateEsteira', 'categoria'])
            ->withCount('senhas')
            ->whereRaw(
                '(select count(*) from senhas where senhas.inscricao_id = inscricoes.id) < inscricoes.quantidade_senhas'
            )
            ->get();

        $senhasVendidas = Senha::where('status', '!=', 'cancelado')
            ->orderByRaw('CAST(numero_senha AS UNSIGNED) ASC')
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

        $tipos = collect($request->input('tipos', []))
            ->map(fn ($v) => is_string($v) ? trim($v) : $v)
            ->filter(fn ($v) => $v !== null && $v !== '')
            ->values()
            ->all();

        if ($restantes === 0) {
            return redirect()->route('senhas.index')->with('sucesso', 'Esta inscrição já está completa.');
        }

        if (count($senhas) !== $restantes) {
            return back()
                ->withErrors(['senhas' => "Você precisa cadastrar exatamente {$restantes} senha(s) para esta inscrição."])
                ->withInput();
        }

        validator(
            [
                'senhas' => $senhas,
                'tipos' => $tipos
            ],
            [
                'senhas' => 'required|array|min:1', 
                'senhas.*' => [
                    'required',
                    'string',
                    'max:50',
                    'distinct',
                    \Illuminate\Validation\Rule::unique('senhas', 'numero_senha')->whereNot('status', 'cancelado')
                ],
                'tipos' => 'required|array|min:1',
                'tipos.*' => 'required|string|in:amador,profissional,boi_tv'
            ]
        )->validate();

        // O model event "created" cuidará de criar as corridas individuais automaticamente!
        foreach ($senhas as $index => $numero) {
            $tipo = $tipos[$index] ?? 'amador';
            Senha::create([
                'inscricao_id' => $inscricao->id,
                'numero_senha' => $numero,
                'status' => 'pendente',
                'tipo' => $tipo
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
        Gate::authorize('update-status'); 

        $data = $request->validate([
            'numero_senha' => [
                'sometimes', 'required', 'string', 'max:50',
                \Illuminate\Validation\Rule::unique('senhas', 'numero_senha')
                    ->ignore($senha->id)
                    ->whereNot('status', 'cancelado')
            ],
            'status' => 'required|in:pendente,correu,boi_batido,cancelado',
            'tipo' => 'sometimes|required|string|in:amador,profissional,boi_tv',
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
        $senha->atualizarStatusAutomatico();

        return redirect()->route('senhas.index')->with('sucesso', 'Senha atualizada com sucesso.');
    }

    public function updateCorrida(Request $request, Corrida $corrida)
    {
        Gate::authorize('update-status');

        $data = $request->validate([
            'resultado' => 'required|in:pendente,boi_batido,zero'
        ]);

        $corrida->update($data);

        $senha = $corrida->senha;
        $senha->atualizarStatusAutomatico();

        return response()->json([
            'success' => true,
            'senha_status' => $senha->status,
            'corrida_resultado' => $corrida->resultado
        ]);
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
        $inscricoes = Inscricao::with(['vaqueiro', 'bateEsteira', 'senhas' => function($query) {
                $query->where('status', '!=', 'cancelado');
            }])
            ->withCount(['senhas' => function($query) {
                $query->where('status', '!=', 'cancelado');
            }])
            ->orderBy('created_at', 'desc')
            ->get();

        $totalInscricoes = $inscricoes->count();
        $totalSenhas = $inscricoes->sum('senhas_count');

        $pagamentoStats = $inscricoes->groupBy('forma_pagamento')
            ->map(function($group) {
                return $group->count();
            });

        $pagamentoStatus = $inscricoes->groupBy('status_pagamento')
            ->map(function($group) {
                return $group->count();
            });

        $disponiveis = (int) ($pagamentoStatus['pago'] ?? 0);
        $indisponiveis = (int) ($pagamentoStatus['pendente'] ?? 0) + (int) ($pagamentoStatus['cancelado'] ?? 0);

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
        $name = 'Relatorio_' . now()->format('d_m_Y_H_i_s') . '.pdf';

        return $pdf->stream($name);
    }
}
