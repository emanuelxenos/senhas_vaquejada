<?php

namespace App\Http\Controllers;

use App\Models\Competidor;
use App\Models\Inscricao;
use Illuminate\Http\Request;

class InscricaoController extends Controller
{
    public function index()
    {
        $inscricoes = Inscricao::with(['vaqueiro', 'bateEsteira'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('inscricoes.index', compact('inscricoes'));
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
