<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Vaqueiros e Senhas</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Courier New', Arial, sans-serif;
            color: #333;
            line-height: 1.6;
            font-size: 11px;
        }

        .container {
            max-width: 210mm;
            margin: 0 auto;
            padding: 15px;
        }

        /* CABEÇALHO */
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #0d6efd;
            padding-bottom: 10px;
        }

        .header-title {
            font-size: 18px;
            font-weight: bold;
            color: #0d6efd;
            margin-bottom: 5px;
        }

        .header-subtitle {
            font-size: 12px;
            color: #666;
            margin-bottom: 5px;
        }

        .header-date {
            font-size: 10px;
            color: #999;
        }

        /* RESUMO EXECUTIVO */
        .summary-section {
            background-color: #f8f9fa;
            border-left: 4px solid #0d6efd;
            padding: 12px;
            margin-bottom: 15px;
            margin-top: 15px;
        }

        .summary-title {
            font-weight: bold;
            font-size: 12px;
            color: #0d6efd;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
        }

        .summary-item {
            background: white;
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
            border-radius: 3px;
        }

        .summary-number {
            font-size: 18px;
            font-weight: bold;
            color: #0d6efd;
        }

        .summary-label {
            font-size: 9px;
            color: #666;
            margin-top: 3px;
            text-transform: uppercase;
        }

        /* TABELA PRINCIPAL */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            margin-top: 10px;
        }

        thead {
            background-color: #0d6efd;
            color: white;
        }

        th {
            padding: 8px;
            text-align: left;
            font-weight: bold;
            font-size: 10px;
            border: 1px solid #0d6efd;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        td {
            padding: 7px;
            border: 1px solid #ddd;
            font-size: 10px;
        }

        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tbody tr:hover {
            background-color: #f0f0f0;
        }

        /* BADGE STATUS */
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .badge-available {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .badge-unavailable {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* ESTATÍSTICAS SECUNDÁRIAS */
        .stats-section {
            margin-top: 15px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .stats-box {
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            border-left: 4px solid #0d6efd;
            padding: 10px;
        }

        .stats-box-title {
            font-weight: bold;
            font-size: 10px;
            color: #0d6efd;
            margin-bottom: 8px;
            text-transform: uppercase;
        }

        .stats-item {
            display: flex;
            justify-content: space-between;
            padding: 4px 0;
            border-bottom: 1px solid #e9ecef;
            font-size: 10px;
        }

        .stats-item:last-child {
            border-bottom: none;
        }

        .stats-label {
            color: #666;
        }

        .stats-value {
            font-weight: bold;
            color: #333;
        }

        /* RODAPÉ */
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 9px;
            color: #999;
        }

        .page-break {
            page-break-after: always;
        }

        /* TOTALIZADOR */
        .total-row {
            background-color: #e7f3ff;
            font-weight: bold;
            color: #0d6efd;
        }

        .total-row td {
            border-top: 2px solid #0d6efd;
            border-bottom: 2px solid #0d6efd;
            padding: 8px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- CABEÇALHO -->
        <div class="header">
            <div class="header-title">📋 RELATÓRIO DE VAQUEIROS E SENHAS</div>
            <div class="header-subtitle">Parque Francisco Alves - Vaquejada 2026</div>
            <div class="header-date">Gerado em {{ $dataRelatorio->format('d/m/Y às H:i:s') }}</div>
        </div>

        <!-- RESUMO EXECUTIVO -->
        <div class="summary-section">
            <div class="summary-title">📊 Resumo Executivo</div>
            <div class="summary-grid">
                <div class="summary-item">
                    <div class="summary-number">{{ $totalVaqueiros }}</div>
                    <div class="summary-label">Total de Vaqueiros</div>
                </div>
                <div class="summary-item">
                    <div class="summary-number">{{ $totalSenhas }}</div>
                    <div class="summary-label">Total de Senhas</div>
                </div>
                <div class="summary-item">
                    <div class="summary-number">{{ $disponiveis }}</div>
                    <div class="summary-label">Disponíveis</div>
                </div>
                <div class="summary-item">
                    <div class="summary-number">{{ $indisponíveis }}</div>
                    <div class="summary-label">Indisponíveis</div>
                </div>
            </div>
        </div>

        <!-- TABELA PRINCIPAL -->
        <table>
            <thead>
                <tr>
                    <th style="width: 20%;">Puxador</th>
                    <th style="width: 15%;">Esteira</th>
                    <th style="width: 15%;">Representação</th>
                    <th style="width: 12%;">Pagamento</th>
                    <th style="width: 10%;">Qtd Prev.</th>
                    <th style="width: 10%;">Senhas</th>
                    <th style="width: 8%;">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($vaqueiros as $vaqueiro)
                    <tr>
                        <td><strong>{{ $vaqueiro->nome }}</strong></td>
                        <td>{{ $vaqueiro->esteira }}</td>
                        <td>{{ $vaqueiro->representacao }}</td>
                        <td>{{ ucfirst($vaqueiro->pagamento) }}</td>
                        <td style="text-align: center;">{{ $vaqueiro->quantidade }}</td>
                        <td style="text-align: center; font-weight: bold; color: #0d6efd;">{{ $vaqueiro->senhas_count }}</td>
                        <td style="text-align: center;">
                            @if($vaqueiro->disponivel === 'sim')
                                <span class="badge badge-available">Ativo</span>
                            @else
                                <span class="badge badge-unavailable">Inativo</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; color: #999;">Nenhum vaqueiro cadastrado</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td colspan="4" style="text-align: right;"><strong>TOTAIS:</strong></td>
                    <td style="text-align: center;"><strong>{{ $totalQuantidade }}</strong></td>
                    <td style="text-align: center;"><strong>{{ $totalSenhas }}</strong></td>
                    <td style="text-align: center;"><strong>{{ $totalVaqueiros }}</strong></td>
                </tr>
            </tfoot>
        </table>

        <!-- ESTATÍSTICAS ADICIONAIS -->
        <div class="stats-section">
            <!-- PAGAMENTOS -->
            <div class="stats-box">
                <div class="stats-box-title">💳 Distribuição por Tipo de Pagamento</div>
                @forelse($pagamentoStats as $tipo => $quantidade)
                    <div class="stats-item">
                        <span class="stats-label">{{ ucfirst($tipo) }}</span>
                        <span class="stats-value">{{ $quantidade }} vaqueiro{{ $quantidade !== 1 ? 's' : '' }}</span>
                    </div>
                @empty
                    <div class="stats-item">
                        <span class="stats-label">Sem dados</span>
                    </div>
                @endforelse
            </div>

            <!-- DISPONIBILIDADE -->
            <div class="stats-box">
                <div class="stats-box-title">✅ Status de Disponibilidade</div>
                <div class="stats-item">
                    <span class="stats-label">Vaqueiros Disponíveis</span>
                    <span class="stats-value">{{ $disponiveis }}</span>
                </div>
                <div class="stats-item">
                    <span class="stats-label">Vaqueiros Indisponíveis</span>
                    <span class="stats-value">{{ $indisponíveis }}</span>
                </div>
                <div class="stats-item">
                    <span class="stats-label">Percentual de Ocupação</span>
                    <span class="stats-value">
                        @if($totalQuantidade > 0)
                            {{ number_format(($totalSenhas / $totalQuantidade) * 100, 1) }}%
                        @else
                            0%
                        @endif
                    </span>
                </div>
            </div>
        </div>

        <!-- RODAPÉ -->
        <div class="footer">
            <p>Este é um documento gerado automaticamente pelo sistema de Vaquejada.</p>
            <p>Data: {{ $dataRelatorio->format('d \\d\\e F \\d\\e Y') }} | Hora: {{ $dataRelatorio->format('H:i:s') }}</p>
        </div>
    </div>
</body>
</html>