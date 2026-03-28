@extends('layout')

@section('page-title', 'Relatório de Senhas')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="mb-2">Relatório de Senhas</h1>
        <p class="text-muted mb-0">Filtre e exporte as senhas para Juízes e Locutores</p>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <form action="{{ route('relatorios.senhas.pdf') }}" method="POST" target="_blank">
                    @csrf
                    
                    <h5 class="mb-4">Configuração de Exportação</h5>

                    <div class="mb-4">
                        <label for="status_senha" class="form-label fw-bold">Qual status você quer imprimir?</label>
                        <select class="form-select form-select-lg" id="status_senha" name="status_senha">
                            <option value="todos" selected>Lista Completa (Todos os Status)</option>
                            <option value="pendente">Apenas Pendentes (Fila do Estrado)</option>
                            <option value="correu">Apenas Correu (Senhas já descidas)</option>
                            <option value="boi_batido">Apenas Boi Batido (Classificados)</option>
                            <option value="cancelado">Apenas Canceladas (Balanço anti-fraude)</option>
                        </select>
                        <small class="text-muted d-block mt-2">
                            <i class="fas fa-info-circle"></i> Ideal para imprimir só as `Pendentes` para o Juiz.
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
