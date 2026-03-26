<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Inscrições e Senhas</title>
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

        .badge-warning {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeeba;
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
            <div class="header-title">RELATÓRIO DE INSCRIÇÕES E SENHAS</div>
            <div class="header-subtitle">{{ config('parque.name') }}</div>
            <div class="header-date">Gerado em {{ $dataRelatorio->format('d/m/Y às H:i:s') }}</div>
        </div>

        <!-- RESUMO EXECUTIVO -->
        <div class="summary-section">
            <div class="summary-title">📊 Resumo Executivo</div>
            <div class="summary-grid">
                <div class="summary-item">
                    <div class="summary-number">{{ $totalInscricoes }}</div>
                    <div class="summary-label">Inscrições</div>
                </div>
                <div class="summary-item">
                    <div class="summary-number">{{ $totalSenhas }}</div>
                    <div class="summary-label">Total de Senhas</div>
                </div>
                <div class="summary-item">
                    <div class="summary-number">{{ $disponiveis }}</div>
                    <div class="summary-label">Pagas</div>
                </div>
                <div class="summary-item">
                    <div class="summary-number">{{ $indisponiveis }}</div>
                    <div class="summary-label">Pendentes/Canceladas</div>
                </div>
            </div>
        </div>

        <!-- TABELA PRINCIPAL -->
        <table>
            <thead>
                <tr>
                    <th style="width: 20%;">Dupla</th>
                    <th style="width: 15%;">Representação</th>
                    <th style="width: 15%;">Pagamento</th>
                    <th style="width: 12%;">Valor</th>
                    <th style="width: 10%;">Status</th>
                    <th style="width: 10%;">Senhas</th>
                    <th style="width: 8%;">Status Senhas</th>
                </tr>
            </thead>
            <tbody>
                @forelse($inscricoes as $inscricao)
                    <tr>
                        <td><strong>{{ $inscricao->vaqueiro->nome }}</strong><br><small>& {{ $inscricao->bateEsteira->nome }}</small></td>
                        <td>{{ $inscricao->vaqueiro->representacao }}</td>
                        <td>{{ ucfirst($inscricao->forma_pagamento) }}</td>
                        <td>R$ {{ number_format($inscricao->valor_total, 2, ',', '.') }}</td>
                        <td style="text-align: center;">
                            @if($inscricao->status_pagamento == 'pago')
                                <span class="badge badge-available">Pago</span>
                            @elseif($inscricao->status_pagamento == 'pendente')
                                <span class="badge badge-warning">Pendente</span>
                            @else
                                <span class="badge badge-unavailable">Cancelado</span>
                            @endif
                        </td>
                        <td style="text-align: center; font-weight: bold; color: #0d6efd;">{{ $inscricao->senhas_count }}</td>
                        <td style="text-align: center;">
                            @php
                                $statusCount = $inscricao->senhas->groupBy('status');
                                $correu = $statusCount->get('correu', collect())->count();
                                $total = $inscricao->senhas->count();
                            @endphp
                            @if($total > 0)
                                <span class="badge badge-available">{{ $correu }}/{{ $total }}</span>
                            @else
                                <span class="badge badge-unavailable">0/0</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; color: #999;">Nenhuma inscrição cadastrada</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td colspan="3" style="text-align: right;"><strong>TOTAIS:</strong></td>
                    <td style="text-align: center;"><strong>R$ {{ number_format($inscricoes->sum('valor_total'), 2, ',', '.') }}</strong></td>
                    <td style="text-align: center;"><strong>{{ $totalInscricoes }}</strong></td>
                    <td style="text-align: center;"><strong>{{ $totalSenhas }}</strong></td>
                    <td style="text-align: center;"><strong>-</strong></td>
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
                        <span class="stats-value">{{ $quantidade }} inscrição{{ $quantidade !== 1 ? 'ões' : '' }}</span>
                    </div>
                @empty
                    <div class="stats-item">
                        <span class="stats-label">Sem dados</span>
                    </div>
                @endforelse
            </div>

            <!-- STATUS PAGAMENTO -->
            <div class="stats-box">
                <div class="stats-box-title">💰 Status de Pagamento</div>
                @forelse($pagamentoStatus as $status => $quantidade)
                    <div class="stats-item">
                        <span class="stats-label">{{ ucfirst($status) }}</span>
                        <span class="stats-value">{{ $quantidade }}</span>
                    </div>
                @empty
                    <div class="stats-item">
                        <span class="stats-label">Sem dados</span>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- ESTATÍSTICAS DE SENHAS -->
        <div class="stats-section">
            <!-- STATUS DAS SENHAS -->
            <div class="stats-box">
                <div class="stats-box-title">🎫 Status das Senhas</div>
                @forelse($senhaStatus as $status => $quantidade)
                    <div class="stats-item">
                        <span class="stats-label">{{ ucfirst(str_replace('_', ' ', $status)) }}</span>
                        <span class="stats-value">{{ $quantidade }}</span>
                    </div>
                @empty
                    <div class="stats-item">
                        <span class="stats-label">Sem senhas</span>
                    </div>
                @endforelse
            </div>

            <!-- RESUMO GERAL -->
            <div class="stats-box">
                <div class="stats-box-title">📊 Resumo Geral</div>
                <div class="stats-item">
                    <span class="stats-label">Total de Inscrições</span>
                    <span class="stats-value">{{ $totalInscricoes }}</span>
                </div>
                <div class="stats-item">
                    <span class="stats-label">Total de Senhas</span>
                    <span class="stats-value">{{ $totalSenhas }}</span>
                </div>
                <div class="stats-item">
                    <span class="stats-label">Valor Total</span>
                    <span class="stats-value">R$ {{ number_format($inscricoes->sum('valor_total'), 2, ',', '.') }}</span>
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