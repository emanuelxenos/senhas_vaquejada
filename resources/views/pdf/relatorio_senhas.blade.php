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

    @php
        $logoPath = \App\Models\Setting::getValue('parque.logo');
        $logoBase64 = null;
        if ($logoPath && file_exists(public_path($logoPath))) {
            $logoData = base64_encode(file_get_contents(public_path($logoPath)));
            $logoBase64 = 'data:image/' . pathinfo(public_path($logoPath), PATHINFO_EXTENSION) . ';base64,' . $logoData;
        }
    @endphp

    <div class="header">
        @if($logoBase64)
            <img src="{{ $logoBase64 }}" style="max-height: 60px; float: right; margin-top: -10px;">
        @endif
        <h1 style="text-align: left; margin: 0;">Relatório de Senhas</h1>
        <p style="text-align: left; margin: 5px 0 0 0;">Sistema de Gerenciamento de Vaquejada</p>
        <div style="clear: both;"></div>
    </div>

    <div class="summary">
        <p><strong>Filtro de Status:</strong> {{ ucfirst(str_replace('_', ' ', $status)) }}</p>
        <p><strong>Categoria:</strong> {{ $categoriaSelecionada ? $categoriaSelecionada->nome : 'Todas' }}</p>
        <p><strong>Total de Senhas Listadas:</strong> {{ $totalSenhas }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="15%">Senha</th>
                <th width="40%">Vaqueiro / Esteira</th>
                <th width="20%">Categoria / Tipo</th>
                <th width="15%">Status</th>
                <th width="10%">Obs</th>
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
                    <td>
                        <strong>Cat:</strong> {{ $senha->inscricao->categoria ? $senha->inscricao->categoria->nome : 'N/A' }}<br>
                        <strong>Tipo:</strong> {{ $senha->is_boi_tv ? 'Boi TV' : 'Comum' }}
                    </td>
                    <td class="status">{{ str_replace('_', ' ', $senha->status) }}</td>
                    <td></td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center;">Nenhuma senha encontrada para este filtro.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Gerado em {{ $dataRelatorio->format('d/m/Y \à\s H:i') }} - Para uso da Organização do Parque
    </div>

</body>
</html>
