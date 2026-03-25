<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Senha Parque Francisco Alves</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; font-size: 15px; font-weight: bold; margin-bottom: 10px; color: #0d6efd; }
        .date { text-align: right; font-size: 12px; }
        .numero { font-size: 15px; font-weight: bold; margin: 10px 0; }
        .info { margin: 5px 0; }
        .label { font-weight: bold; }
        .quadrado { display: inline-block; width: 15px; height: 15px; border: 1px solid #000; margin: 0 5px; vertical-align: middle; }
        .comissao { margin: 10px 0; }
        .comissao-item { margin: 5px 0; }
        .comprovante { margin-top: 20px; border-top: 2px solid #0d6efd; padding-top: 10px; background-color: #f8f9fa; padding: 10px; }
        .page-break { page-break-before: always; }
        .pagina-divisao { page-break-after: always; }
        .senha-card { page-break-inside: avoid; margin-bottom: 10px; border: 1px solid #ddd; padding: 8px; background-color: #f9f9f9; }
        .disputa-set { margin-bottom: 8px; display: flex; align-items: center; gap: 2px; }
        .disputa-set .label { width: 65px; display: inline-block; }
        .disputa-subset { margin-left: 0; margin-bottom: 3px; display: flex; align-items: center; gap: 2px; }
        .disputa-subset::before { content: ""; display: inline-block; width: 65px; }
        .linha-fina { border-top: 0.5px solid #000; margin: 10px 0; width: 100%; }
        .linha-fina-bottom { border-bottom: 0.5px solid #000; margin: 10px 0; width: 100%; }
        .comprovante-header { background-color: #0d6efd; color: white; padding: 5px; text-align: center; font-weight: bold; margin-bottom: 10px; }
    </style>
</head>
<body>
    @foreach($senhas as $senha)
        <div class="senha-card">
            <div class="linha-fina"></div>
            <div class="header">Parque Francisco Alves</div>
            <div class="date">{{ optional($vaqueiro->data)->format('d/m/Y') ?? now()->format('d/m/Y') }}</div>

            <div class="numero">
                <span class="label">N°:</span> {{ $senha->numero }}
            </div>

            <div class="info">
                <span class="label">Puxador:</span> {{ $vaqueiro->nome }}
            </div>

            <div class="info">
                <span class="label">Esteira:</span> {{ $vaqueiro->esteira }}
            </div>

            <div class="info">
                <span class="label">Representação:</span> {{ $vaqueiro->representacao }}
            </div>

            <div style="margin: 8px 0;"></div>

            <div class="info">
                <span class="label">Classificação:</span>
                <span class="quadrado"></span>
                <span class="quadrado"></span>
                <span class="quadrado"></span>
            </div>

            <div style="margin: 8px 0;"></div>

            <div class="disputa-set">
                <span class="label">Disputa:</span>
                <span class="quadrado"></span>
                <span class="quadrado"></span>
                <span class="quadrado"></span>
            </div>

            <div class="disputa-subset">
                <span class="quadrado"></span>
                <span class="quadrado"></span>
                <span class="quadrado"></span>
            </div>

            <div style="margin: 8px 0;"></div>

            <div class="comissao">
                <div class="comissao-item">
                    <span class="label">Comissão:</span>
                    <span>Profissional:</span>
                    <span class="quadrado"></span>
                    <span>Amador:</span>
                    <span class="quadrado"></span>
                    <span>Boi Tv:</span>
                    <span class="quadrado"></span>
                </div>
            </div>

            <div class="linha-fina"></div>
        </div>

        @if($loop->iteration % 6 == 0 && !$loop->last)
            <div class="pagina-divisao"></div>
        @endif
    @endforeach

    <!-- Comprovante final -->
    @if($senhas->count() > 0)
        <div class="pagina-divisao"></div>
        <div class="comprovante">
            <div class="comprovante-header">Comprovante De Entrada</div>
            <div class="linha-fina"></div>

            <div class="info">
                <span class="label">Puxador:</span> {{ $vaqueiro->nome }}
            </div>

            <div class="info">
                <span class="label">Esteira:</span> {{ $vaqueiro->esteira }}
            </div>

            <div class="info">
                <span class="label">Representação:</span> {{ $vaqueiro->representacao }}
            </div>

            <div class="info">
                <span class="label">Quantidade:</span> {{ $vaqueiro->quantidade }}
            </div>

            <div class="info">
                <span class="label">Pagamento:</span> {{ $vaqueiro->pagamento }}
            </div>

            <div class="info">
                <span class="label">Senhas:</span>
                @foreach($senhas as $senha)
                    {{ $senha->numero }}@if(!$loop->last), @endif
                @endforeach
            </div>

            <div class="info">
                <span class="label">Data:</span> {{ optional($vaqueiro->data)->format('d/m/Y H:i:s') ?? now()->format('d/m/Y H:i:s') }}
            </div>

            <div class="linha-fina"></div>
        </div>
    @endif
</body>
</html>