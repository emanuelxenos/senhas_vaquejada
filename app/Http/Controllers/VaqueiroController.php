<?php

namespace App\Http\Controllers;

use App\Models\Vaqueiro;
use App\Models\Senha;
use Illuminate\Http\Request;
use PDF;

class VaqueiroController extends Controller
{
    public function index()
    {
        $vaqueiros = Vaqueiro::orderBy('nome')->get();
        return view('vaqueiros.index', compact('vaqueiros'));
    }

    public function create()
    {
        return view('vaqueiros.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome' => 'required|string|max:255',
            'esteira' => 'required|string|max:255',
            'pagamento' => 'required|string|max:50',
            'qtd' => 'required|integer|min:0',
            'repre' => 'required|string|max:255',
        ]);

        $vaqueiro = Vaqueiro::create([
            'nome' => $data['nome'],
            'esteira' => $data['esteira'],
            'pagamento' => $data['pagamento'],
            'quantidade' => $data['qtd'],
            'representacao' => $data['repre'],
            'data' => now(),
            'disponivel' => 'sim',
        ]);

        return redirect()->route('vaqueiros.index')->with('sucesso', 'Cadastro realizado com sucesso.');
    }

    public function edit(Vaqueiro $vaqueiro)
    {
        return view('vaqueiros.edit', compact('vaqueiro'));
    }

    public function update(Request $request, Vaqueiro $vaqueiro)
    {
        $data = $request->validate([
            'nome' => 'required|string|max:255',
            'esteira' => 'required|string|max:255',
            'pagamento' => 'required|string|max:50',
            'repre' => 'required|string|max:255',
        ]);

        $vaqueiro->update([
            'nome' => $data['nome'],
            'esteira' => $data['esteira'],
            'pagamento' => $data['pagamento'],
            'representacao' => $data['repre'],
        ]);

        return redirect()->route('vaqueiros.index')->with('sucesso', 'Vaqueiro atualizado.');
    }

    public function destroy(Vaqueiro $vaqueiro)
    {
        $vaqueiro->delete();
        return redirect()->route('vaqueiros.index')->with('sucesso', 'Vaqueiro excluído.');
    }

    public function cadastrarSenhaForm()
    {
        $vaqueiros = Vaqueiro::where('disponivel', 'sim')->get();
        return view('senhas.create', compact('vaqueiros'));
    }

    public function storeSenhas(Request $request)
    {
        $data = $request->validate([
            'vaqueiro_id' => 'required|exists:vaqueiros,id',
            'senhas' => 'required|array',
            'senhas.*' => 'required|string|max:50',
        ]);

        $vaqueiro = Vaqueiro::findOrFail($data['vaqueiro_id']);

        foreach ($data['senhas'] as $numero) {
            Senha::create(['numero' => $numero, 'vaqueiro_id' => $vaqueiro->id]);
        }

        $vaqueiro->update(['disponivel' => 'nao']);

        return redirect()->route('senhas.index')->with('sucesso', 'Senhas cadastradas com sucesso.');
    }

    public function listarSenhas()
    {
        $senhas = Senha::with('vaqueiro')->orderBy('numero')->get();
        $total = $senhas->count();
        return view('senhas.index', compact('senhas', 'total'));
    }

    public function gerarPdf(Vaqueiro $vaqueiro)
    {
        $senhas = $vaqueiro->senhas()->orderBy('numero')->get();

        $pdf = PDF::loadView('pdf.vaqueiro', compact('vaqueiro', 'senhas'));
        $name = 'Senha_de_corrida_' . now()->format('d_m_Y') . '.pdf';

        return $pdf->stream($name);
    }

    public function relatorio()
    {
        // Buscar vaqueiros com contagem de senhas
        $vaqueiros = Vaqueiro::with('senhas')
            ->withCount('senhas')
            ->orderBy('nome')
            ->get();

        // Calculando estatísticas
        $totalVaqueiros = $vaqueiros->count();
        $totalSenhas = $vaqueiros->sum('senhas_count');
        $totalQuantidade = $vaqueiros->sum('quantidade');
        
        // Contar por tipo de pagamento
        $pagamentoStats = $vaqueiros->groupBy('pagamento')
            ->map(function($group) {
                return $group->count();
            });
        
        // Vaqueiros disponíveis e indisponíveis
        $disponiveis = $vaqueiros->where('disponivel', 'sim')->count();
        $indisponíveis = $vaqueiros->where('disponivel', 'não')->count();
        
        // Data do relatório
        $dataRelatorio = now();
        
        // Passar todos os dados para a view
        $dados = compact(
            'vaqueiros',
            'totalVaqueiros',
            'totalSenhas',
            'totalQuantidade',
            'pagamentoStats',
            'disponiveis',
            'indisponíveis',
            'dataRelatorio'
        );
        
        $pdf = PDF::loadView('pdf.relatorio', $dados);
        $pdf->setPaper('A4', 'portrait');
        $name = 'Relatorio_' . now()->format('d_m_Y_H_i_s') . '.pdf';

        return $pdf->stream($name);
    }
}
