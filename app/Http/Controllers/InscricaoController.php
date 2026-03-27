<?php

namespace App\Http\Controllers;

use App\Models\Competidor;
use App\Models\Inscricao;
use Illuminate\Http\Request;

class InscricaoController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('q', ''));

        $query = Inscricao::with(['vaqueiro', 'bateEsteira'])
            ->withCount(['senhas' => function($q) {
                $q->where('status', '!=', 'cancelado');
            }]);

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->whereHas('vaqueiro', function ($subQ) use ($search) {
                    $subQ->where('nome', 'like', "%{$search}%");
                })->orWhereHas('bateEsteira', function ($subQ) use ($search) {
                    $subQ->where('nome', 'like', "%{$search}%");
                });
            });
        }

        $inscricoes = $query->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('inscricoes.index', compact('inscricoes', 'search'));
    }

    public function create()
    {
        $competidores = Competidor::orderBy('nome')->get();
        return view('inscricoes.create', compact('competidores'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'vaqueiro_id' => 'required|exists:competidores,id',
            'bate_esteira_id' => 'required|exists:competidores,id|different:vaqueiro_id',
            'quantidade_senhas' => 'required|integer|min:1|max:50',
            'forma_pagamento' => 'required|string|max:50',
            'valor_total' => 'required|numeric|min:0',
            'status_pagamento' => 'required|in:pendente,pago,cancelado',
        ]);

        Inscricao::create($data);

        return redirect()->route('inscricoes.index')->with('sucesso', 'Inscrição realizada com sucesso.');
    }

    public function edit(Inscricao $inscricao)
    {
        $competidores = Competidor::orderBy('nome')->get();
        return view('inscricoes.edit', compact('inscricao', 'competidores'));
    }

    public function update(Request $request, Inscricao $inscricao)
    {
        $data = $request->validate([
            'vaqueiro_id' => 'required|exists:competidores,id',
            'bate_esteira_id' => 'required|exists:competidores,id|different:vaqueiro_id',
            'quantidade_senhas' => 'required|integer|min:1|max:50',
            'forma_pagamento' => 'required|string|max:50',
            'valor_total' => 'required|numeric|min:0',
            'status_pagamento' => 'required|in:pendente,pago,cancelado',
        ]);

        $inscricao->update($data);

        return redirect()->route('inscricoes.index')->with('sucesso', 'Inscrição atualizada com sucesso.');
    }

    public function destroy(Inscricao $inscricao)
    {
        $inscricao->delete();
        return redirect()->route('inscricoes.index')->with('sucesso', 'Inscrição excluída com sucesso.');
    }
}
