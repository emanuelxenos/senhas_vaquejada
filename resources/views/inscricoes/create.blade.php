@extends('layout')

@section('content')
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
                                <option value="">Selecione...</option>
                                <option value="Dinheiro" {{ old('forma_pagamento') == 'Dinheiro' ? 'selected' : '' }}>Dinheiro</option>
                                <option value="Pix" {{ old('forma_pagamento') == 'Pix' ? 'selected' : '' }}>Pix</option>
                                <option value="Cartão" {{ old('forma_pagamento') == 'Cartão' ? 'selected' : '' }}>Cartão</option>
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
</script>
@endsection