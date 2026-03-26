@extends('layout')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="p-4 bg-light rounded border">
            <h1 class="display-6">Painel De Controle - Senhas de Vaquejada</h1>
            <p class="lead">Visualize os dados do sistema com ações rápidas para cadastro e geração de relatórios.</p>
        </div>
    </div>
</div>

<div class="row mb-3">
    <div class="col-sm-6 col-xl-3 mb-3">
        <div class="card border-primary h-100 shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Competidores</h5>
                <p class="card-text fs-2 fw-bold text-primary">{{ $totalCompetidores }}</p>
                <a href="{{ route('competidores.index') }}" class="btn btn-sm btn-primary">Ver todos</a>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3 mb-3">
        <div class="card border-success h-100 shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Inscrições</h5>
                <p class="card-text fs-2 fw-bold text-success">{{ $totalInscricoes }}</p>
                <a href="{{ route('inscricoes.index') }}" class="btn btn-sm btn-success">Ver lista</a>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3 mb-3">
        <div class="card border-info h-100 shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Senhas</h5>
                <p class="card-text fs-2 fw-bold text-success">{{ $totalSenhas }}</p>
                <a href="{{ route('senhas.index') }}" class="btn btn-sm btn-success">Ver lista</a>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3 mb-3">
        <div class="card border-secondary h-100 shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Acesso rápido</h5>
                <p class="mb-2 text-muted">Cadastros e relatórios</p>
                <div class="d-grid gap-2">
                    <a href="{{ route('competidores.create') }}" class="btn btn-sm btn-outline-primary">Novo Competidor</a>
                    <a href="{{ route('inscricoes.create') }}" class="btn btn-sm btn-outline-success">Nova Inscrição</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="card-title mb-0">Distribuição de Pagamentos</h5>
            </div>
            <div class="card-body">
                <canvas id="pagamentosChart"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="card-title mb-0">Senhas por Vaqueiro</h5>
            </div>
            <div class="card-body">
                <canvas id="senhasChart"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="card-title">Ações rápidas</h5>
                <div class="btn-group" role="group" aria-label="Acoes rápidas">
                    <a href="{{ route('competidores.create') }}" class="btn btn-outline-primary">Novo Competidor</a>
                    <a href="{{ route('inscricoes.create') }}" class="btn btn-outline-success">Nova Inscrição</a>
                    <a href="{{ route('senhas.create') }}" class="btn btn-outline-success">Nova Senha</a>
                    <a href="{{ route('relatorio') }}" target="_blank" class="btn btn-outline-info">Gerar Relatório PDF</a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="{{ asset('js/chart.min.js') }}"></script>
<script>
    // Gráfico de pagamentos
    const ctxPagamentos = document.getElementById('pagamentosChart').getContext('2d');
    const pagamentosData = @json($pagamentos);
    new Chart(ctxPagamentos, {
        type: 'pie',
        data: {
            labels: Object.keys(pagamentosData),
            datasets: [{
                data: Object.values(pagamentosData),
                backgroundColor: ['#007bff', '#28a745', '#ffc107', '#dc3545'],
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });

    // Gráfico de senhas por vaqueiro
    const ctxSenhas = document.getElementById('senhasChart').getContext('2d');
    const senhasData = @json($senhasPorVaqueiro);
    new Chart(ctxSenhas, {
        type: 'bar',
        data: {
            labels: Object.keys(senhasData),
            datasets: [{
                label: 'Senhas',
                data: Object.values(senhasData),
                backgroundColor: '#17a2b8',
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endsection
