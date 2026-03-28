@extends('layout')

@section('page-title', 'Relatório de Inscrições')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="mb-2">Relatório de Inscrições</h1>
        <p class="text-muted mb-0">Filtre e exporte o balanço das inscrições</p>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <form action="{{ route('relatorios.inscricoes.pdf') }}" method="POST" target="_blank">
                    @csrf
                    
                    <h5 class="mb-4">Configuração de Exportação</h5>

                    <div class="mb-4">
                        <label for="status_pagamento" class="form-label fw-bold">Situação do Pagamento</label>
                        <select class="form-select form-select-lg" id="status_pagamento" name="status_pagamento">
                            <option value="todos" selected>Todas as Inscrições</option>
                            <option value="pago">Apenas Pagas</option>
                            <option value="pendente">Apenas Pendentes</option>
                        </select>
                        <small class="text-muted d-block mt-2">
                            <i class="fas fa-info-circle"></i> Escolha 'Pagas' para o fluxo do caixa, ou 'Pendentes' para cobrar a galera.
                        </small>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-file-pdf me-2"></i> Gerar PDF
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
