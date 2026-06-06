<?php

namespace App\Http\Controllers;

use App\Models\Competidor;
use App\Models\Inscricao;
use App\Models\Setting;
use App\Models\Categoria;
use App\Models\Senha;
use App\Services\Pagamento\AsaasGateway;
use App\Services\Pagamento\PagSeguroGateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PortalInscricaoController extends Controller
{
    private function getGatewayInstance(string $provider)
    {
        if ($provider === 'asaas') {
            return new AsaasGateway();
        } elseif ($provider === 'pagseguro') {
            return new PagSeguroGateway();
        }
        throw new \Exception("Gateway não suportado ou inválido.");
    }

    public function dashboard()
    {
        $user = Auth::user();
        if (!$user->isVaqueiro() || !$user->competidor) {
            abort(403, 'Acesso restrito a vaqueiros.');
        }

        $inscricoes = $user->competidor->inscricoesComoVaqueiro()
            ->with(['bateEsteira', 'categoria'])
            ->withCount(['senhas' => function($q) {
                $q->where('status', '!=', 'cancelado');
            }])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('portal.dashboard', compact('inscricoes'));
    }

    public function create()
    {
        $user = Auth::user();
        if (!$user->isVaqueiro() || !$user->competidor) {
            abort(403, 'Acesso restrito a vaqueiros.');
        }

        $categorias = Categoria::orderBy('nome')->get();
        
        $competidores = Competidor::where('id', '!=', $user->competidor->id)
                                  ->orderBy('nome')
                                  ->get()
                                  ->map(function ($comp) {
                                      if (preg_match('/^(\d{3})\.\d{3}\.\d{3}-(\d{2})$/', $comp->cpf, $matches)) {
                                          $comp->cpf_oculto = $matches[1] . '.***.***-' . $matches[2];
                                      } else {
                                          $comp->cpf_oculto = '***';
                                      }
                                      return $comp;
                                  });

        return view('portal.inscricoes.create', compact('categorias', 'competidores'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user->isVaqueiro() || !$user->competidor) {
            abort(403, 'Acesso restrito a vaqueiros.');
        }

        $request->validate([
            'categoria_id' => 'required|exists:categorias,id',
            'bate_esteira_id' => 'nullable|exists:competidores,id',
            'novo_bate_esteira_nome' => 'required_without:bate_esteira_id|nullable|string|max:255',
            'novo_bate_esteira_cpf' => 'required_without:bate_esteira_id|nullable|string|max:20',
            'novo_bate_esteira_cidade' => 'required_without:bate_esteira_id|nullable|string|max:255',
            'novo_bate_esteira_representacao' => 'nullable|string|max:255',
            'quantidade_senhas' => 'required|integer|min:1|max:50',
        ]);

        $categoria = Categoria::findOrFail($request->categoria_id);
        if ($request->quantidade_senhas > $categoria->limite_senhas_por_vaqueiro) {
            return back()->withErrors(['quantidade_senhas' => "O limite de senhas para a categoria {$categoria->nome} é de no máximo {$categoria->limite_senhas_por_vaqueiro}."])->withInput();
        }

        $valorTotal = $request->quantidade_senhas * $categoria->preco_senha;

        $bateEsteiraId = $request->bate_esteira_id;

        if (!$bateEsteiraId) {
            $novoBateEsteira = Competidor::create([
                'nome' => $request->novo_bate_esteira_nome,
                'cpf' => $request->novo_bate_esteira_cpf,
                'cidade' => $request->novo_bate_esteira_cidade,
                'representacao' => $request->novo_bate_esteira_representacao,
            ]);
            $bateEsteiraId = $novoBateEsteira->id;
        }

        $inscricao = Inscricao::create([
            'categoria_id' => $categoria->id,
            'vaqueiro_id' => $user->competidor->id,
            'bate_esteira_id' => $bateEsteiraId,
            'quantidade_senhas' => $request->quantidade_senhas,
            'forma_pagamento' => 'Pix (Gateway)',
            'valor_total' => $valorTotal,
            'status_pagamento' => 'pendente',
        ]);

        $provider = Setting::getValue('payment.gateway', 'none');
        if (in_array($provider, ['asaas', 'pagseguro'])) {
            try {
                $gateway = $this->getGatewayInstance($provider);
                $pixData = $gateway->gerarPix($inscricao, (float)$valorTotal);
                
                $inscricao->update([
                    'gateway_provider' => $provider,
                    'gateway_transaction_id' => $pixData['transaction_id'],
                    'gateway_qr_code' => $pixData['qr_code'],
                    'gateway_qr_code_url' => $pixData['qr_code_url'],
                ]);
                
                return redirect()->route('portal.inscricoes.pagamento', $inscricao->id)
                                 ->with('sucesso', 'Inscrição criada. Realize o pagamento PIX abaixo.');
            } catch (\Exception $e) {
                return redirect()->route('portal.dashboard')->with('error', 'Inscrição criada, mas falha ao gerar PIX: ' . $e->getMessage());
            }
        }

        return redirect()->route('portal.dashboard')->with('sucesso', 'Inscrição realizada. Aguarde o pagamento.');
    }

    public function pagamento(Inscricao $inscricao)
    {
        $user = Auth::user();
        if ($inscricao->vaqueiro_id !== $user->competidor->id) {
            abort(403);
        }

        if (!$inscricao->gateway_qr_code) {
            return redirect()->route('portal.dashboard')->with('error', 'Esta inscrição não possui uma cobrança PIX pendente.');
        }

        return view('portal.inscricoes.pagamento', compact('inscricao'));
    }

    public function checarStatus(Request $request, Inscricao $inscricao)
    {
        $user = Auth::user();
        if ($inscricao->vaqueiro_id !== $user->competidor->id) {
            return response()->json(['status' => 'unauthorized'], 403);
        }

        if ($inscricao->status_pagamento === 'pago') {
            return response()->json(['status' => 'pago']);
        }
        
        if (in_array($inscricao->gateway_provider, ['asaas', 'pagseguro']) && $inscricao->gateway_transaction_id) {
            try {
                $gateway = $this->getGatewayInstance($inscricao->gateway_provider);
                $novoStatus = $gateway->consultarStatus($inscricao->gateway_transaction_id);
                
                if ($novoStatus !== 'pendente') {
                    $inscricao->update(['status_pagamento' => $novoStatus]);
                }
                
                return response()->json(['status' => $novoStatus]);
            } catch (\Exception $e) {
                return response()->json(['status' => 'pendente', 'error' => $e->getMessage()]);
            }
        }
        
        return response()->json(['status' => $inscricao->status_pagamento]);
    }

    public function showSenhas(Inscricao $inscricao)
    {
        $user = Auth::user();
        if ($inscricao->vaqueiro_id !== $user->competidor->id) {
            abort(403);
        }

        $inscricao->load(['bateEsteira', 'senhas' => function ($q) {
            $q->where('status', '!=', 'cancelado')->orderBy('numero_senha');
        }]);

        $senhasCadastradas = $inscricao->senhas->count();
        $restantes = max($inscricao->quantidade_senhas - $senhasCadastradas, 0);

        $senhasVendidas = Senha::where('status', '!=', 'cancelado')
            ->orderByRaw('CAST(numero_senha AS UNSIGNED) ASC')
            ->pluck('numero_senha')
            ->toArray();

        // Verificar se boi_tv está liberado por data
        $dataLimiteBoiTv = Setting::getValue('senha.data_limite_boi_tv', '');
        $permitirBoiTv = true;
        if ($dataLimiteBoiTv && now()->format('Y-m-d') > $dataLimiteBoiTv) {
            $permitirBoiTv = false;
        }

        return view('portal.inscricoes.senhas', compact('inscricao', 'restantes', 'senhasVendidas', 'permitirBoiTv'));
    }

    public function storeSenhas(Request $request, Inscricao $inscricao)
    {
        $user = Auth::user();
        if ($inscricao->vaqueiro_id !== $user->competidor->id) {
            abort(403);
        }

        if ($inscricao->status_pagamento !== 'pago') {
            return back()->with('error', 'Você só pode escolher senhas após a confirmação do pagamento.');
        }

        $inscricao->loadCount(['senhas' => function ($q) {
            $q->where('status', '!=', 'cancelado');
        }]);

        $quantidade = (int) $inscricao->quantidade_senhas;
        $jaCadastradas = (int) $inscricao->senhas_count;
        $restantes = max($quantidade - $jaCadastradas, 0);

        if ($restantes === 0) {
            return back()->with('sucesso', 'Todas as senhas desta inscrição já foram escolhidas.');
        }

        $senhas = collect($request->input('senhas', []))
            ->map(fn ($v) => is_string($v) ? trim($v) : $v)
            ->filter(fn ($v) => $v !== null && $v !== '')
            ->values()
            ->all();

        $isBoiTvArray = collect($request->input('is_boi_tv', []))
            ->map(fn ($v) => (int)$v)
            ->all();

        if (count($senhas) !== $restantes) {
            return back()->withErrors(['senhas' => "Você precisa cadastrar exatamente {$restantes} senha(s)."])->withInput();
        }

        // Validar data limite de Boi TV
        $dataLimiteBoiTv = Setting::getValue('senha.data_limite_boi_tv', '');
        $permitirBoiTv = true;
        if ($dataLimiteBoiTv && now()->format('Y-m-d') > $dataLimiteBoiTv) {
            $permitirBoiTv = false;
        }

        if (!$permitirBoiTv && in_array(1, $isBoiTvArray)) {
            return back()->withErrors(['is_boi_tv' => 'A data limite para a compra online da senha tipo Boi TV já expirou.'])->withInput();
        }

        validator(
            [
                'senhas' => $senhas,
                'is_boi_tv' => $isBoiTvArray
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
                'is_boi_tv' => 'required|array|min:1',
                'is_boi_tv.*' => 'required|in:0,1'
            ],
            [
                'senhas.*.unique' => 'Um dos números de senha escolhidos já foi pego por outro competidor. Escolha outro.',
                'is_boi_tv.*.in' => 'Opção inválida selecionada.'
            ]
        )->validate();

        // O evento "created" em Senha criará automaticamente as corridas no BD!
        foreach ($senhas as $index => $numero) {
            $isBoiTv = (bool) ($isBoiTvArray[$index] ?? false);
            Senha::create([
                'inscricao_id' => $inscricao->id,
                'numero_senha' => $numero,
                'status' => 'pendente',
                'is_boi_tv' => $isBoiTv
            ]);
        }

        return redirect()->route('portal.inscricoes.senhas', $inscricao->id)->with('sucesso', 'Suas senhas foram escolhidas e garantidas com sucesso!');
    }

    public function gerarPdf(Inscricao $inscricao)
    {
        $user = Auth::user();
        if ($inscricao->vaqueiro_id !== $user->competidor->id) {
            abort(403);
        }

        if ($inscricao->senhas()->count() === 0) {
            return back()->with('error', 'Nenhuma senha escolhida ainda para gerar comprovante.');
        }

        $senhas = $inscricao->senhas()->where('status', '!=', 'cancelado')->orderBy('numero_senha')->get();

        $pdf = \PDF::loadView('pdf.vaqueiro', compact('inscricao', 'senhas'));
        $name = 'Comprovante_Vaquejada_' . str_pad($inscricao->id, 4, '0', STR_PAD_LEFT) . '.pdf';

        return $pdf->stream($name);
    }
}
