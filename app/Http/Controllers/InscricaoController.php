<?php

namespace App\Http\Controllers;

use App\Models\Competidor;
use App\Models\Inscricao;
use App\Models\Setting;
use App\Services\Pagamento\AsaasGateway;
use App\Services\Pagamento\PagSeguroGateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class InscricaoController extends Controller
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
        Gate::authorize('manage-cadastros');
        $competidores = Competidor::orderBy('nome')->get();
        $precoSenha = \App\Models\Setting::getValue('parque.preco_senha', '100.00');
        return view('inscricoes.create', compact('competidores', 'precoSenha'));
    }

    public function store(Request $request)
    {
        Gate::authorize('manage-cadastros');
        $data = $request->validate([
            'vaqueiro_id' => 'required|exists:competidores,id',
            'bate_esteira_id' => 'required|exists:competidores,id|different:vaqueiro_id',
            'quantidade_senhas' => 'required|integer|min:1|max:50',
            'forma_pagamento' => 'required|string|max:50',
            'valor_total' => 'required|numeric|min:0',
            'status_pagamento' => 'required|in:pendente,pago,cancelado',
        ]);

        $inscricao = Inscricao::create($data);

        $provider = Setting::getValue('payment.gateway', 'none');
        if ($data['forma_pagamento'] === 'Pix (Gateway)' && in_array($provider, ['asaas', 'pagseguro'])) {
            try {
                $gateway = $this->getGatewayInstance($provider);
                $pixData = $gateway->gerarPix($inscricao, (float)$data['valor_total']);
                
                $inscricao->update([
                    'gateway_provider' => $provider,
                    'gateway_transaction_id' => $pixData['transaction_id'],
                    'gateway_qr_code' => $pixData['qr_code'],
                    'gateway_qr_code_url' => $pixData['qr_code_url'],
                ]);
                
                return redirect()->route('inscricoes.pagamento', $inscricao->id)
                                 ->with('sucesso', 'Inscrição criada. Realize o pagamento PIX abaixo.');
            } catch (\Exception $e) {
                return redirect()->route('inscricoes.index')->with('error', 'Inscrição criada, mas falha ao gerar PIX online: ' . $e->getMessage());
            }
        }

        return redirect()->route('inscricoes.index')->with('sucesso', 'Inscrição realizada com sucesso.');
    }

    public function pagamento(Inscricao $inscricao)
    {
        Gate::authorize('manage-cadastros');
        if (!$inscricao->gateway_qr_code) {
            return redirect()->route('inscricoes.index')->with('error', 'Esta inscrição não possui uma cobrança PIX automática pendente.');
        }

        return view('inscricoes.pagamento', compact('inscricao'));
    }

    public function reciboTermico(Inscricao $inscricao)
    {
        Gate::authorize('manage-cadastros');
        $inscricao->load(['vaqueiro', 'bateEsteira', 'senhas']);
        
        return view('inscricoes.termica', compact('inscricao'));
    }

    public function checarStatus(Request $request, Inscricao $inscricao)
    {
        Gate::authorize('manage-cadastros');
        
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

    public function gerarPixManual(Inscricao $inscricao)
    {
        Gate::authorize('manage-cadastros');
        
        if ($inscricao->status_pagamento !== 'pendente' || $inscricao->forma_pagamento !== 'Pix (Gateway)') {
            return redirect()->route('inscricoes.index')->with('error', 'Esta inscrição não está pendente ou não é do tipo Pix Gateway.');
        }

        try {
            $provider = Setting::getValue('payment.gateway', 'none');
            if ($provider === 'none') {
                throw new \Exception("Nenhum gateway de pagamento está ativo nas configurações.");
            }

            $gateway = $this->getGatewayInstance($provider);
            $pixData = $gateway->gerarPix($inscricao, (float)$inscricao->valor_total);
            
            $inscricao->update([
                'gateway_provider' => $provider,
                'gateway_transaction_id' => $pixData['transaction_id'],
                'gateway_qr_code' => $pixData['qr_code'],
                'gateway_qr_code_url' => $pixData['qr_code_url'],
            ]);
            
            return redirect()->route('inscricoes.pagamento', $inscricao->id)
                             ->with('sucesso', 'A cobrança PIX foi atualizada com sucesso.');
        } catch (\Exception $e) {
            return redirect()->route('inscricoes.index')->with('error', 'Falha ao gerar PIX: ' . $e->getMessage());
        }
    }

    public function edit(Inscricao $inscricao)
    {
        Gate::authorize('manage-cadastros');
        $competidores = Competidor::orderBy('nome')->get();
        $precoSenha = \App\Models\Setting::getValue('parque.preco_senha', '100.00');
        return view('inscricoes.edit', compact('inscricao', 'competidores', 'precoSenha'));
    }

    public function update(Request $request, Inscricao $inscricao)
    {
        Gate::authorize('manage-cadastros');
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
        Gate::authorize('manage-cadastros');
        $inscricao->delete();
        return redirect()->route('inscricoes.index')->with('sucesso', 'Inscrição excluída com sucesso.');
    }
}
