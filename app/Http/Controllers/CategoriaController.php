<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CategoriaController extends Controller
{
    public function index()
    {
        Gate::authorize('manage-settings');
        $categorias = Categoria::orderBy('nome')->get();
        return view('categorias.index', compact('categorias'));
    }

    public function store(Request $request)
    {
        Gate::authorize('manage-settings');
        $data = $request->validate([
            'nome' => 'required|string|max:100|unique:categorias,nome',
            'preco_senha' => 'required|numeric|min:0',
            'limite_senhas_por_vaqueiro' => 'required|integer|min:1|max:10',
            'quantidade_bois' => 'required|integer|min:1|max:10',
            'minimo_bois_sucesso' => 'required|integer|min:1|max:10',
        ]);

        Categoria::create($data);

        return redirect()->route('categorias.index')->with('success', 'Categoria criada com sucesso!');
    }

    public function update(Request $request, Categoria $categoria)
    {
        Gate::authorize('manage-settings');
        $data = $request->validate([
            'nome' => 'required|string|max:100|unique:categorias,nome,' . $categoria->id,
            'preco_senha' => 'required|numeric|min:0',
            'limite_senhas_por_vaqueiro' => 'required|integer|min:1|max:10',
            'quantidade_bois' => 'required|integer|min:1|max:10',
            'minimo_bois_sucesso' => 'required|integer|min:1|max:10',
        ]);

        $categoria->update($data);

        return redirect()->route('categorias.index')->with('success', 'Categoria atualizada com sucesso!');
    }

    public function destroy(Categoria $categoria)
    {
        Gate::authorize('manage-settings');
        
        if ($categoria->inscricoes()->exists()) {
            return redirect()->route('categorias.index')->with('error', 'Esta categoria não pode ser excluída pois possui inscrições associadas.');
        }

        $categoria->delete();

        return redirect()->route('categorias.index')->with('success', 'Categoria excluída com sucesso!');
    }
}
