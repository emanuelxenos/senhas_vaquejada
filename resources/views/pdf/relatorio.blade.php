<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório Completo</title>
    <style>body{font-family:Arial,sans-serif;} table{width:100%;border-collapse:collapse;} th,td{border:1px solid #000;padding:4px;text-align:left;}</style>
</head>
<body>
    <h1>Relatório Completo</h1>
    <table>
        <thead>
            <tr>
                <th>Puxador</th>
                <th>Esteira</th>
                <th>F.Pagamento</th>
                <th>Qtd Senhas</th>
                <th>Representação</th>
            </tr>
        </thead>
        <tbody>
            @foreach($vaqueiros as $vaqueiro)
                <tr>
                    <td>{{ $vaqueiro->nome }}</td>
                    <td>{{ $vaqueiro->esteira }}</td>
                    <td>{{ $vaqueiro->pagamento }}</td>
                    <td>{{ $vaqueiro->quantidade }}</td>
                    <td>{{ $vaqueiro->representacao }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <p>Gerado em: {{ now()->format('d/m/Y H:i:s') }}</p>
</body>
</html>