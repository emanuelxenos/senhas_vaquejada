<?php

namespace App\Http\Controllers;

use App\Models\Competidor;
use Illuminate\Http\Request;

class CompetidorController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('q', ''));

        $competidores = Competidor::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('nome', 'like', "%{$search}%")
                        ->orWhere('cpf', 'like', "%{$search}%")
                        ->orWhere('cidade', 'like', "%{$search}%")
                        ->orWhere('representacao', 'like', "%{$search}%");
                });
            })
            ->orderBy('nome')
            ->paginate(15)
            ->withQueryString();

        return view('competidores.index', compact('competidores', 'search'));
    }

    public function create()
    {
        return view('competidores.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome' => 'required|string|max:255',
            'cpf' => 'required|string|max:14|unique:competidores,cpf',
            'cidade' => 'required|string|max:255',
            'representacao' => 'required|string|max:255',
        ]);

        Competidor::create($data);

        return redirect()->route('competidores.index')->with('sucesso', 'Competidor cadastrado com sucesso.');
    }

    public function edit(Competidor $competidor)
    {
        return view('competidores.edit', compact('competidor'));
    }

    public function update(Request $request, Competidor $competidor)
    {
        $data = $request->validate([
            'nome' => 'required|string|max:255',
            'cpf' => 'required|string|max:14|unique:competidores,cpf,' . $competidor->id,
            'cidade' => 'required|string|max:255',
            'representacao' => 'required|string|max:255',
        ]);

        $competidor->update($data);

        return redirect()->route('competidores.index')->with('sucesso', 'Competidor atualizado com sucesso.');
    }

    public function destroy(Competidor $competidor)
    {
        $competidor->delete();
        return redirect()->route('competidores.index')->with('sucesso', 'Competidor excluído com sucesso.');
    }
}
