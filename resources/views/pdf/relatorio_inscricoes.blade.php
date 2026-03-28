<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Relatório de Inscrições</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0 0 5px 0;
            color: #000;
        }
        .header p {
            margin: 0;
            color: #555;
        }
        .summary {
            margin-bottom: 20px;
        }
        .summary p {
            margin: 5px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th {
            background-color: #f8f9fa;
            padding: 10px;
            text-align: left;
        }
        td {
            padding: 8px 10px;
        }
        .status-pago {
            color: #198754;
            font-weight: bold;
        }
        .status-pendente {
            color: #ffc107;
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #777;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Relatório de Inscrições</h1>
        <p>Sistema de Gerenciamento de Vaquejada</p>
    </div>

    <div class="summary">
        <p><strong>Filtro de Status:</strong> {{ ucfirst($status) }}</p>
        <p><strong>Total de Inscrições:</strong> {{ $inscricoes->count() }}</p>
        <p><strong>Valor Total Previsto:</strong> R$ {{ number_format($totalValor, 2, ',', '.') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="10%">ID</th>
                <th width="35%">Vaqueiro / Bate-Esteira</th>
                <th width="15%">Valor</th>
                <th width="20%">Pgt. Forma</th>
                <th width="20%">Pgt. Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($inscricoes as $inscricao)
                <tr>
                    <td>#{{ str_pad($inscricao->id, 3, '0', STR_PAD_LEFT) }}</td>
                    <td>
                        <strong>V:</strong >{{ $inscricao->vaqueiro->nome }}<br>
                        <strong>E:</strong >{{ $inscricao->bateEsteira->nome }}
                    </td>
                    <td>R$ {{ number_format($inscricao->valor_total, 2, ',', '.') }}</td>
                    <td>{{ ucfirst($inscricao->forma_pagamento) }}</td>
                    <td class="status-{{ $inscricao->status_pagamento }}">{{ ucfirst($inscricao->status_pagamento) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center;">Nenhuma inscrição encontrada para o filtro selecionado.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Gerado em {{ $dataRelatorio->format('d/m/Y \à\s H:i') }} - Sistema de Vaquejada
    </div>

</body>
</html>
