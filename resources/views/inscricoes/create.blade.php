@extends('layout')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">
                    <i class="fas fa-clipboard-plus"></i> Nova Inscrição
                </h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('inscricoes.store') }}">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="vaqueiro_id" class="form-label">Vaqueiro *</label>
                            <select name="vaqueiro_id" id="vaqueiro_id" class="form-select @error('vaqueiro_id') is-invalid @enderror" required>
                                <option value="">Selecione o vaqueiro...</option>
                                @foreach($competidores as $competidor)
                                    <option value="{{ $competidor->id }}" {{ old('vaqueiro_id') == $competidor->id ? 'selected' : '' }}>
                                        {{ $competidor->nome }} - {{ $competidor->representacao }}
                                    </option>
                                @endforeach
                            </select>
                            @error('vaqueiro_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="bate_esteira_id" class="form-label">Bate-Esteira *</label>
                            <select name="bate_esteira_id" id="bate_esteira_id" class="form-select @error('bate_esteira_id') is-invalid @enderror" required>
                                <option value="">Selecione o bate-esteira...</option>
                                @foreach($competidores as $competidor)
                                    <option value="{{ $competidor->id }}" {{ old('bate_esteira_id') == $competidor->id ? 'selected' : '' }}>
                                        {{ $competidor->nome }} - {{ $competidor->representacao }}
                                    </option>
                                @endforeach
                            </select>
                            @error('bate_esteira_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="forma_pagamento" class="form-label">Forma de Pagamento *</label>
                            <select name="forma_pagamento" id="forma_pagamento" class="form-select @error('forma_pagamento') is-invalid @enderror" required>
                                <option value="" {{ old('forma_pagamento') == '' ? 'selected' : '' }}>Selecione...</option>
                                <option value="Dinheiro" {{ old('forma_pagamento') == 'Dinheiro' ? 'selected' : '' }}>Dinheiro</option>
                                <option value="Pix" {{ old('forma_pagamento') == 'Pix' ? 'selected' : '' }}>Pix</option>
                                @php $gateway = \App\Models\Setting::getValue('payment.gateway', 'none') @endphp
                                @if($gateway !== 'none')
                                    <option value="Pix (Gateway)" {{ old('forma_pagamento') == 'Pix (Gateway)' ? 'selected' : '' }} class="fw-bold text-success">Pix Online (Imediato)</option>
                                @endif
                                <option value="Cartão" {{ old('forma_pagamento') == 'Cartão' ? 'selected' : '' }}>Cartão Manual</option>
                                <option value="Crediário" {{ old('forma_pagamento') == 'Crediário' ? 'selected' : '' }}>Crediário</option>
                                <option value="Cheque" {{ old('forma_pagamento') == 'Cheque' ? 'selected' : '' }}>Cheque</option>
                                <option value="Troca" {{ old('forma_pagamento') == 'Troca' ? 'selected' : '' }}>Troca</option>
                            </select>
                            @error('forma_pagamento')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="valor_total" class="form-label">Valor Total (R$) *</label>
                            <input type="number" name="valor_total" id="valor_total" class="form-control @error('valor_total') is-invalid @enderror"
                                   value="{{ old('valor_total') }}" step="0.01" min="0" placeholder="0,00" required>
                            @error('valor_total')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="quantidade_senhas" class="form-label">Quantidade de Senhas *</label>
                            <input type="number" name="quantidade_senhas" id="quantidade_senhas"
                                   class="form-control @error('quantidade_senhas') is-invalid @enderror"
                                   value="{{ old('quantidade_senhas', 1) }}" min="1" max="50" required>
                            @error('quantidade_senhas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="status_pagamento" class="form-label">Status do Pagamento *</label>
                            <select name="status_pagamento" id="status_pagamento" class="form-select @error('status_pagamento') is-invalid @enderror" required>
                                <option value="pendente" {{ old('status_pagamento', 'pendente') == 'pendente' ? 'selected' : '' }}>Pendente</option>
                                <option value="pago" {{ old('status_pagamento') == 'pago' ? 'selected' : '' }}>Pago</option>
                                <option value="cancelado" {{ old('status_pagamento') == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                            </select>
                            @error('status_pagamento')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Helper do Caixa -->
                    <div class="row mb-3 bg-light p-3 rounded mx-1">
                        <div class="col-md-6 mb-2 mb-md-0">
                            <label for="valor_recebido" class="form-label text-primary fw-bold"><i class="fas fa-hand-holding-usd"></i> Valor Recebido no Caixa (R$)</label>
                            <input type="number" id="valor_recebido" class="form-control" placeholder="0,00" step="0.01" min="0">
                            <small class="text-muted">Apenas visual, ajuda a calcular o troco.</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-success fw-bold"><i class="fas fa-exchange-alt"></i> Troco a devolver</label>
                            <div class="h3 font-monospace text-success mb-0" id="valor_troco">R$ 0,00</div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('inscricoes.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Voltar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Criar Inscrição
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Validação para impedir que vaqueiro e bate-esteira sejam a mesma pessoa
document.getElementById('vaqueiro_id').addEventListener('change', validarDupla);
document.getElementById('bate_esteira_id').addEventListener('change', validarDupla);

function validarDupla() {
    const vaqueiro = document.getElementById('vaqueiro_id').value;
    const bateEsteira = document.getElementById('bate_esteira_id').value;

    if (vaqueiro && bateEsteira && vaqueiro === bateEsteira) {
        alert('O vaqueiro e o bate-esteira não podem ser a mesma pessoa!');
        document.getElementById('bate_esteira_id').value = '';
    }
}

// Lógica da Calculadora do Caixa
const precoSenha = {{ $precoSenha }};
const inputQtd = document.getElementById('quantidade_senhas');
const inputTotal = document.getElementById('valor_total');
const inputRecebido = document.getElementById('valor_recebido');
const displayTroco = document.getElementById('valor_troco');

let manualTotal = false;

// Evitar que o script sobrescreva se o operador digitar o total manualmente
inputTotal.addEventListener('input', function() {
    manualTotal = true;
    calcularTroco();
});

// Auto-calcular total ao mudar a quantidade (se o operador não tiver digitado o total manualmente antes)
inputQtd.addEventListener('input', function() {
    if (!manualTotal) {
        let qtd = parseInt(this.value) || 1;
        inputTotal.value = (qtd * precoSenha).toFixed(2);
    }
    calcularTroco();
});

// Auto-calcular a primeira vez, sem travar edição manual
window.addEventListener('DOMContentLoaded', () => {
    if (!inputTotal.value) {
        inputTotal.value = (parseFloat(inputQtd.value || 1) * precoSenha).toFixed(2);
    }
});

function calcularTroco() {
    const total = parseFloat(inputTotal.value) || 0;
    const recebido = parseFloat(inputRecebido.value) || 0;
    
    if (recebido >= total && total > 0) {
        const troco = recebido - total;
        displayTroco.innerHTML = `R$ ${troco.toFixed(2).replace('.', ',')}`;
        displayTroco.classList.remove('text-danger', 'text-warning');
        displayTroco.classList.add('text-success');
    } else if (recebido > 0) {
        const falta = total - recebido;
        displayTroco.innerHTML = `Faltam R$ ${falta.toFixed(2).replace('.', ',')}`;
        displayTroco.classList.remove('text-success', 'text-warning');
        displayTroco.classList.add('text-danger');
    } else {
        displayTroco.innerHTML = `R$ 0,00`;
        displayTroco.classList.remove('text-danger', 'text-warning');
        displayTroco.classList.add('text-success');
    }
}

inputRecebido.addEventListener('input', calcularTroco);
</script>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('#vaqueiro_id').select2({
            theme: 'bootstrap-5',
            placeholder: 'Selecione ou pesquise o vaqueiro...'
        });
        $('#bate_esteira_id').select2({
            theme: 'bootstrap-5',
            placeholder: 'Selecione ou pesquise o bate-esteira...'
        });
    });
</script>
@endsection