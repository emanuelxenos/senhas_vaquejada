@extends('layouts.portal')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="card-title glow-text" style="margin-bottom: 0;">Nova Inscrição</h1>
        <p class="text-muted text-sm mt-1">Selecione seu bate-esteira e compre suas senhas</p>
    </div>
    <a href="{{ route('portal.dashboard') }}" class="btn btn-secondary text-sm" style="width: auto; padding: 0.6rem 1.25rem;">Voltar</a>
</div>

<div class="alert" style="background: rgba(59, 130, 246, 0.1); border: 1px solid rgba(59, 130, 246, 0.2); color: #93c5fd;">
    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
    <strong>Aviso:</strong> A escolha dos números das suas senhas só será liberada após a confirmação do pagamento da inscrição.
</div>

<div class="card" style="padding: 2.5rem;">
    @if ($errors->any())
        <div class="alert alert-error text-sm">
            <ul style="padding-left: 1rem; margin: 0;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('portal.inscricoes.store') }}" id="form-inscricao">
        @csrf
        
        <div class="flex items-center gap-4 mb-6" style="border-bottom: 1px solid var(--glass-border); padding-bottom: 1rem;">
            <div style="width: 32px; height: 32px; background: var(--primary); color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-family: 'Outfit';">1</div>
            <h3 style="color: #fff; font-family: 'Outfit'; font-size: 1.4rem;">Selecione o Bate-Esteira</h3>
        </div>
        
        <div class="form-group">
            <label class="form-label" for="bate_esteira_id">Bate-Esteira Parceiro</label>
            <select name="bate_esteira_id" id="bate_esteira_id" class="form-control" onchange="toggleNovoBateEsteira()" style="cursor: pointer;" placeholder="Pesquise por nome...">
                <option value="">-- Cadastrar Novo Bate-Esteira --</option>
                @foreach($competidores as $comp)
                    <option value="{{ $comp->id }}" {{ old('bate_esteira_id') == $comp->id ? 'selected' : '' }}>
                        {{ $comp->nome }} (CPF: {{ $comp->cpf_oculto }})
                    </option>
                @endforeach
            </select>
        </div>

        <div id="novo-bate-esteira-fields" style="background: rgba(15, 23, 42, 0.4); padding: 1.5rem; border-radius: 12px; margin-bottom: 2rem; border: 1px dashed rgba(255,255,255,0.15); transition: all 0.3s ease;">
            <div class="flex items-center gap-2 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--primary)"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg>
                <p class="text-sm" style="color: #cbd5e1; font-weight: 600;">Cadastrar Novo Bate-Esteira</p>
            </div>
            
            <div class="flex flex-mobile-col gap-4">
                <div class="form-group" style="flex: 1;">
                    <label class="form-label" for="novo_bate_esteira_nome">Nome Completo</label>
                    <input type="text" id="novo_bate_esteira_nome" name="novo_bate_esteira_nome" class="form-control" placeholder="João da Silva" value="{{ old('novo_bate_esteira_nome') }}">
                </div>
                <div class="form-group" style="flex: 1;">
                    <label class="form-label" for="novo_bate_esteira_cpf">CPF (Apenas números)</label>
                    <input type="text" id="novo_bate_esteira_cpf" name="novo_bate_esteira_cpf" class="form-control" placeholder="000.000.000-00" value="{{ old('novo_bate_esteira_cpf') }}">
                </div>
            </div>
            
            <div class="flex flex-mobile-col gap-4">
                <div class="form-group" style="flex: 1; margin-bottom: 0;">
                    <label class="form-label" for="novo_bate_esteira_cidade">Cidade/UF</label>
                    <input type="text" id="novo_bate_esteira_cidade" name="novo_bate_esteira_cidade" class="form-control" placeholder="Ex: Carnaíba/PE" value="{{ old('novo_bate_esteira_cidade') }}">
                </div>
                <div class="form-group" style="flex: 1; margin-bottom: 0;">
                    <label class="form-label" for="novo_bate_esteira_representacao">Representação</label>
                    <input type="text" id="novo_bate_esteira_representacao" name="novo_bate_esteira_representacao" class="form-control" placeholder="Haras, Parque..." value="{{ old('novo_bate_esteira_representacao') }}">
                </div>
            </div>
        </div>

        <div class="flex items-center gap-4 mb-6 mt-8" style="border-bottom: 1px solid var(--glass-border); padding-bottom: 1rem;">
            <div style="width: 32px; height: 32px; background: var(--primary); color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-family: 'Outfit';">2</div>
            <h3 style="color: #fff; font-family: 'Outfit'; font-size: 1.4rem;">Senhas e Pagamento</h3>
        </div>

        <div class="flex flex-mobile-col gap-6 items-center">
            <div class="form-group" style="flex: 1; margin-bottom: 0; width: 100%;">
                <label class="form-label" for="quantidade_senhas">Quantidade de Senhas (R$ {{ number_format($precoSenha, 2, ',', '.') }} cada)</label>
                <div style="position: relative;">
                    <input type="number" id="quantidade_senhas" name="quantidade_senhas" class="form-control" value="{{ old('quantidade_senhas', 1) }}" min="1" max="50" required oninput="calcularTotal()" style="font-size: 1.25rem; font-weight: 600; padding-right: 3rem;">
                    <span style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted); pointer-events: none;">Qtd</span>
                </div>
            </div>

            <input type="hidden" name="valor_total" id="valor_total" value="{{ old('valor_total', $precoSenha) }}">

            <div style="flex: 1; width: 100%; background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(16, 185, 129, 0.05)); border: 1px solid rgba(16, 185, 129, 0.3); border-radius: 12px; padding: 1.5rem; text-align: right; box-shadow: inset 0 0 20px rgba(16, 185, 129, 0.05);">
                <p class="text-sm text-muted" style="text-transform: uppercase; letter-spacing: 0.05em; font-weight: 600;">Total a Pagar (PIX)</p>
                <h2 style="font-size: 2.5rem; color: var(--primary); margin-top: 0.25rem; font-family: 'Outfit'; letter-spacing: -0.03em;" id="display_total">R$ {{ number_format($precoSenha, 2, ',', '.') }}</h2>
            </div>
        </div>

        <button type="submit" class="btn btn-primary mt-8" id="btn-submit" style="font-size: 1.2rem; padding: 1rem;">Confirmar Inscrição e Gerar PIX</button>
    </form>
</div>

<!-- Adicionando CDN do TomSelect para busca avançada -->
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

@push('scripts')
<script>
    const precoSenha = parseFloat("{{ $precoSenha }}");

    // Inicializando TomSelect
    new TomSelect("#bate_esteira_id",{
        create: false,
        sortField: {
            field: "text",
            direction: "asc"
        },
        placeholder: "Pesquise pelo nome do Bate-Esteira..."
    });

    function toggleNovoBateEsteira() {
        const select = document.getElementById('bate_esteira_id');
        const fields = document.getElementById('novo-bate-esteira-fields');
        const inputNome = document.getElementById('novo_bate_esteira_nome');
        const inputCpf = document.getElementById('novo_bate_esteira_cpf');
        const inputCidade = document.getElementById('novo_bate_esteira_cidade');
        
        if (select.value === "") {
            fields.style.display = 'block';
            setTimeout(() => fields.style.opacity = '1', 10);
            inputNome.required = true;
            inputCpf.required = true;
            inputCidade.required = true;
        } else {
            fields.style.opacity = '0';
            setTimeout(() => fields.style.display = 'none', 300);
            inputNome.required = false;
            inputCpf.required = false;
            inputCidade.required = false;
        }
    }

    function calcularTotal() {
        let qtd = parseInt(document.getElementById('quantidade_senhas').value) || 0;
        if (qtd < 1) qtd = 1;
        let total = qtd * precoSenha;
        
        document.getElementById('valor_total').value = total.toFixed(2);
        
        let formatoBRL = total.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        document.getElementById('display_total').innerText = 'R$ ' + formatoBRL;
    }

    document.addEventListener('DOMContentLoaded', () => {
        toggleNovoBateEsteira();
        calcularTotal();
    });

    document.getElementById('form-inscricao').addEventListener('submit', function() {
        document.getElementById('btn-submit').innerHTML = '<svg class="animate-spin" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="animation: spin 1s linear infinite; margin-right: 0.5rem;"><line x1="12" y1="2" x2="12" y2="6"></line><line x1="12" y1="18" x2="12" y2="22"></line><line x1="4.93" y1="4.93" x2="7.76" y2="7.76"></line><line x1="16.24" y1="16.24" x2="19.07" y2="19.07"></line><line x1="2" y1="12" x2="6" y2="12"></line><line x1="18" y1="12" x2="22" y2="12"></line><line x1="4.93" y1="19.07" x2="7.76" y2="16.24"></line><line x1="16.24" y1="7.76" x2="19.07" y2="4.93"></line></svg> Processando...';
        document.getElementById('btn-submit').style.opacity = '0.8';
        document.getElementById('btn-submit').style.cursor = 'not-allowed';
        // Avoid multi-click but allow submit to proceed
        setTimeout(() => document.getElementById('btn-submit').disabled = true, 50);
    });
</script>
<style>
    @keyframes spin { 100% { transform: rotate(360deg); } }
    .animate-spin { display: inline-block; }
</style>
@endpush
@endsection
