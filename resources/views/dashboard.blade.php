@extends('layout')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="p-4 bg-light rounded border">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                <div>
                    <h1 class="display-6">Painel De Controle - Senhas de Vaquejada</h1>
                    <p class="lead mb-2">Visualize os dados do sistema com ações rápidas para cadastro e geração de relatórios.</p>
                    <p class="text-muted mb-0">Use o QR Code para abrir rapidamente no celular.</p>
                </div>
                <div class="text-center ms-md-3" style="background: white; padding: 10px; border-radius: 8px; border: 1px solid #dee2e6;">
                    <a href="{{ $mobileUrl }}" target="_blank" rel="noopener" class="text-decoration-none d-inline-block">
                        <img src="{{ $qrCodeSvg }}" alt="QR Code" style="width: 104px; height: 104px; display: block; border: none; background: white;" />
                    </a>
                    <div class="small text-muted mt-1" style="font-size: 11px;">Escaneie para abrir</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Ações rápidas no topo -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow-sm border-0 bg-white">
            <div class="card-body p-3">
                <div class="d-flex flex-wrap gap-2 align-items-center">
                    <span class="fw-bold text-muted me-2"><i class="fas fa-bolt text-warning"></i> Atalhos Rápidos:</span>
                    <a href="{{ route('competidores.create') }}" class="btn btn-outline-primary btn-sm rounded-pill"><i class="fas fa-user-plus me-1"></i> Novo Competidor</a>
                    <a href="{{ route('inscricoes.create') }}" class="btn btn-outline-success btn-sm rounded-pill"><i class="fas fa-plus me-1"></i> Nova Inscrição</a>
                    <a href="{{ route('senhas.create') }}" class="btn btn-outline-success btn-sm rounded-pill"><i class="fas fa-ticket-alt me-1"></i> Nova Senha</a>
                    <a href="{{ route('relatorios.geral') }}" target="_blank" class="btn btn-outline-info btn-sm rounded-pill"><i class="fas fa-file-pdf me-1"></i> Financeiro (PDF)</a>
                </div>
            </div>
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
                <p class="card-text fs-2 fw-bold text-info">{{ $totalSenhas }}</p>
                <a href="{{ route('senhas.index') }}" class="btn btn-sm btn-info">Ver lista</a>
            </div>
        </div>
    </div>

    @if(auth()->user()->role === 'admin')
    <div class="col-sm-6 col-xl-3 mb-3">
        <div class="card border-warning h-100 shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Faturamento (Pago)</h5>
                <p class="card-text fs-2 fw-bold text-warning">R$ {{ number_format($totalFaturamento, 2, ',', '.') }}</p>
                <a href="{{ route('relatorios.geral') }}" target="_blank" class="btn btn-sm btn-warning">Relatório</a>
            </div>
        </div>
    </div>
    @endif
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
                <h5 class="card-title mb-0">Senhas por Categoria</h5>
            </div>
            <div class="card-body">
                <canvas id="senhasChart"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/chart.min.js') }}?v={{ time() }}"></script>
<script>
</script>

<script>
    // Configuração isolada dos gráficos com tratamento de erros completo
    document.addEventListener("DOMContentLoaded", function() {
        // Gráfico de pagamentos
        try {
            const chartElement = document.getElementById('pagamentosChart');
            if (chartElement) {
                const ctxPagamentos = chartElement.getContext('2d');
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
            }
        } catch (e) {
            console.error("Erro no gráfico de pagamentos:", e);
        }

        // Gráfico de senhas por categoria
        try {
            const chartElement = document.getElementById('senhasChart');
            if (chartElement) {
                const ctxSenhas = chartElement.getContext('2d');
                const senhasData = @json($senhasPorCategoria);
                new Chart(ctxSenhas, {
                    type: 'bar',
                    data: {
                        labels: Object.keys(senhasData),
                        datasets: [{
                            label: 'Quantidade de Senhas',
                            data: Object.values(senhasData),
                            backgroundColor: ['#ffc107', '#17a2b8', '#fd7e14', '#20c997'],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                }
                            }
                        }
                    }
                });
            }
        } catch (e) {
            console.error("Erro no gráfico de senhas:", e);
        }
    });
</script>
@endsection
