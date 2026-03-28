<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Relatório de Senhas</title>
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
            border: 1px solid #000;
        }
        th {
            background-color: #f0f0f0;
            padding: 10px;
            text-align: left;
            font-size: 16px;
        }
        td {
            padding: 8px 10px;
            font-size: 15px;
        }
        .senha-numero {
            font-size: 20px;
            font-weight: bold;
            text-align: center;
        }
        .status {
            text-transform: uppercase;
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 11px;
            color: #777;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Relatório de Senhas</h1>
        <p>Sistema de Gerenciamento de Vaquejada</p>
    </div>

    <div class="summary">
        <p><strong>Filtro de Status:</strong> {{ ucfirst(str_replace('_', ' ', $status)) }}</p>
        <p><strong>Total de Senhas Listadas:</strong> {{ $totalSenhas }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="15%">Senha</th>
                <th width="50%">Vaqueiro / Esteira</th>
                <th width="20%">Status</th>
                <th width="15%">Obs</th>
            </tr>
        </thead>
        <tbody>
            @forelse($senhas as $senha)
                <tr>
                    <td class="senha-numero">{{ $senha->numero_senha }}</td>
                    <td>
                        <strong>{{ $senha->inscricao->vaqueiro->nome }}</strong> 
                        ({{ $senha->inscricao->vaqueiro->representacao }} / {{ $senha->inscricao->vaqueiro->cidade }})<br>
                        <em>Esteira: {{ $senha->inscricao->bateEsteira->nome }}</em>
                    </td>
                    <td class="status">{{ str_replace('_', ' ', $senha->status) }}</td>
                    <td></td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center;">Nenhuma senha encontrada para este filtro.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Gerado em {{ $dataRelatorio->format('d/m/Y \à\s H:i') }} - Para uso da Organização do Parque
    </div>

</body>
</html>
