<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cupom Térmico - Inscrição #{{ $inscricao->id }}</title>
    <style>
        @page {
            margin: 0;
        }
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 14px;
            color: #000;
            background: #fff;
            margin: 0;
            padding: 10px;
            width: 80mm; /* Largura padrão de bobina térmica */
            box-sizing: border-box;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-bold { font-weight: bold; }
        .mt-2 { margin-top: 10px; }
        .mb-2 { margin-bottom: 10px; }
        .divider { border-top: 1px dashed #000; margin: 10px 0; }
        .divider-solid { border-top: 1px solid #000; margin: 10px 0; }
        h1, h2, h3, h4, p { margin: 0; padding: 2px 0; }
        .cabecalho { text-align: center; margin-bottom: 10px; }
        h2.park-name { font-size: 18px; margin-bottom: 5px; }
        .info-row { display: flex; justify-content: space-between; margin: 3px 0; }
        .senhas { background: #000; color: #fff; text-align: center; padding: 5px; margin: 10px 0; font-size: 18px; font-weight: bold; }
        .footer { text-align: center; margin-top: 20px; font-size: 12px; }
        
        /* Simular papel na tela do navegador para quem estiver testando */
        @media screen {
            body { margin: 20px auto; border: 1px solid #ccc; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        }
    </style>
</head>
<body>
    <div class="cabecalho">
        <h2 class="park-name">{{ \App\Models\Setting::getValue('parque.name', 'Parque de Vaquejada') }}</h2>
        <p>Recibo de Inscrição</p>
        <p>Insc: #{{ str_pad($inscricao->id, 5, '0', STR_PAD_LEFT) }}</p>
    </div>

    <div class="divider"></div>
    
    <div class="info-row">
        <span class="text-bold">Data:</span>
        <span>{{ $inscricao->created_at->format('d/m/Y H:i') }}</span>
    </div>
    
    <div class="divider-solid"></div>
    
    <p class="text-bold">Puxador (Vaqueiro):</p>
    <p>{{ $inscricao->vaqueiro->nome }}</p>
    
    <p class="text-bold mt-2">Bate-Esteira:</p>
    <p>{{ $inscricao->bateEsteira->nome }}</p>

    <div class="divider"></div>

    <div class="info-row">
        <span class="text-bold">Pgto:</span>
        <span>{{ $inscricao->forma_pagamento }}</span>
    </div>
    <div class="info-row">
        <span class="text-bold">Status:</span>
        <span>{{ strtoupper($inscricao->status_pagamento) }}</span>
    </div>
    <div class="info-row text-bold" style="font-size: 16px; margin-top: 5px;">
        <span>TOTAL:</span>
        <span>R$ {{ number_format($inscricao->valor_total, 2, ',', '.') }}</span>
    </div>

    <div class="divider-solid"></div>

    @if($inscricao->senhas->count() > 0)
        <p class="text-center text-bold">SENHAS VINCULADAS</p>
        <div class="senhas">
            @foreach($inscricao->senhas as $senha)
                {{ $senha->numero_senha }}@if(!$loop->last), @endif
            @endforeach
        </div>
    @else
        <p class="text-center text-bold mt-2 mb-2">Nenhuma senha gerada</p>
    @endif

    <div class="divider"></div>

    <div class="footer">
        <p>Obrigado e Boa Sorte!</p>
        <p style="margin-top:30px;">________________________</p>
        <p>Assinatura Caixa</p>
    </div>

    <script>
        // Imprime automaticamente assim que for carregado pelo navegador
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>
