<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Sistema de senhas de vaquejada</title>
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
        .carimbo-cancelado {
            position: absolute;
            top: 105px;
            left: 10%;
            width: 80%;
            color: rgba(200, 0, 0, 0.3);
            border: 3px solid rgba(200, 0, 0, 0.3);
            font-size: 50px;
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
            padding: 10px;
            z-index: 100;
            transform: rotate(-15deg);
        }
    </style>
</head>
<body>
    @foreach($senhas as $senha)
        <div class="senha-card" style="position: relative;">
            @if($senha->status === 'cancelado')
                <div class="carimbo-cancelado">CANCELADO</div>
            @endif
            <div class="linha-fina"></div>
            <div class="header">{{ config('parque.name') }}</div>
            <div class="date">{{ optional($inscricao->created_at)->format('d/m/Y') ?? now()->format('d/m/Y') }}</div>

            <div class="numero">
                <span class="label">N°:</span> {{ $senha->numero_senha }}
            </div>

            <div class="info">
                <span class="label">Puxador:</span> {{ $inscricao->vaqueiro->nome }}
            </div>

            <div class="info">
                <span class="label">Bate-Esteira:</span> {{ $inscricao->bateEsteira->nome }}
            </div>

            <div class="info">
                <span class="label">Representação:</span> {{ $inscricao->vaqueiro->representacao }}
            </div>

            <div style="margin: 8px 0;"></div>

            <div class="info">
                <span class="label">Classificação:</span>
                <span class="quadrado"></span>
                <span class="quadrado"></span>
                <span class="quadrado"></span>
            </div>

            <div style="margin: 8px 0;"></div>

            <div style="margin: 8px 0;">
                <div style="font-weight: bold; font-size: 12px; margin-bottom: 6px;">Disputa:</div>
                <div style="display: grid; grid-template-columns: repeat(3, auto); gap: 5px; margin-top: 4px;">
                    <span class="quadrado"></span>
                    <span class="quadrado"></span>
                    <span class="quadrado"></span>
                </div>
                <div style="display: grid; grid-template-columns: repeat(3, auto); gap: 5px; margin-top: 4px;">
                    <span class="quadrado"></span>
                    <span class="quadrado"></span>
                    <span class="quadrado"></span>
                </div>
                <div style="display: grid; grid-template-columns: repeat(3, auto); gap: 5px; margin-top: 4px;">
                    <span class="quadrado"></span>
                    <span class="quadrado"></span>
                    <span class="quadrado"></span>
                </div>
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
                <span class="label">Puxador:</span> {{ $inscricao->vaqueiro->nome }}
            </div>

            <div class="info">
                <span class="label">Esteira:</span> {{ $inscricao->bateEsteira->nome }}
            </div>

            <div class="info">
                <span class="label">Representação:</span> {{ $inscricao->vaqueiro->representacao }}
            </div>

            <div class="info">
                <span class="label">Valor Total:</span> R$ {{ number_format($inscricao->valor_total, 2, ',', '.') }}
            </div>

            <div class="info">
                <span class="label">Pagamento:</span> {{ $inscricao->forma_pagamento }}
            </div>

            <div class="info">
                <span class="label">Status:</span> {{ ucfirst($inscricao->status_pagamento) }}
            </div>

            <div class="info">
                <span class="label">Senhas:</span>
                @foreach($senhas as $senha)
                    {{ $senha->numero_senha }}@if(!$loop->last), @endif
                @endforeach
            </div>

            <div class="info">
                <span class="label">Data:</span> {{ $inscricao->created_at->format('d/m/Y H:i:s') }}
            </div>

            <div class="linha-fina"></div>
        </div>
    @endif
</body>
</html>