@extends('layout')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-lg border-0 rounded-lg">
            <div class="card-header bg-success text-white text-center py-4">
                <h3 class="font-weight-light my-0"><i class="fas fa-qrcode"></i> Pagamento da Inscrição #{{ $inscricao->id }}</h3>
            </div>
            <div class="card-body text-center p-5">
                <h4 class="mb-4 text-muted">Vaqueiro: <strong>{{ $inscricao->vaqueiro->nome }}</strong></h4>
                
                <div id="pix-content">
                    <div class="alert alert-info">
                        <h5>Valor a Pagar: <strong>R$ {{ number_format($inscricao->valor_total, 2, ',', '.') }}</strong></h5>
                        <p class="mb-0">Aponte a câmera do seu celular para o QRCode abaixo para pagar via PIX.</p>
                    </div>

                    @if($inscricao->gateway_qr_code_url)
                        <div class="my-4">
                            <img src="data:image/png;base64,{{ $inscricao->gateway_qr_code_url }}" alt="QRCode PIX" class="img-fluid border rounded" style="max-height: 250px;">
                        </div>
                    @endif

                    @if($inscricao->gateway_qr_code)
                        <div class="mb-4">
                            <label class="form-label text-muted">Ou use o Pix Copia e Cola:</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="pixCopiaCola" value="{{ $inscricao->gateway_qr_code }}" readonly>
                                <button class="btn btn-outline-secondary" type="button" onclick="copiarPix()">
                                    <i class="fas fa-copy"></i> Copiar
                                </button>
                            </div>
                        </div>
                    @endif
                </div>

                <div id="sucesso-msg" class="alert alert-success d-none py-4">
                    <i class="fas fa-check-circle fa-4x mb-3 text-success"></i>
                    <h4>Pagamento Confirmado!</h4>
                    <p class="mb-0">O PIX caiu na conta com sucesso. Redirecionando em 2 segundos...</p>
                </div>
                
                <div id="erro-msg" class="alert alert-danger d-none py-4">
                    <i class="fas fa-times-circle fa-4x mb-3 text-danger"></i>
                    <h4>PIX Cancelado</h4>
                    <p class="mb-0">A transação expirou ou foi cancelada pelo banco. Voltando...</p>
                </div>

                <div id="botoes-acoes" class="mt-5 d-flex justify-content-between">
                    <a href="{{ route('inscricoes.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Voltar para Inscrições
                    </a>
                    
                    <button type="button" id="btn-checar" class="btn btn-success" onclick="atualizarStatusManual();">
                        <i class="fas fa-sync"></i> Já Paguei / Atualizar Status
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copiarPix() {
    var copyText = document.getElementById("pixCopiaCola");
    copyText.select();
    copyText.setSelectionRange(0, 99999); /* For mobile devices */
    navigator.clipboard.writeText(copyText.value).then(function() {
        alert("Pix copiado para a área de transferência!");
    });
}

function checarPagamento() {
    fetch('{{ route("inscricoes.status", $inscricao) }}')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'pago') {
                clearInterval(poller);
                document.getElementById('pix-content').classList.add('d-none');
                document.getElementById('botoes-acoes').classList.add('d-none');
                document.getElementById('sucesso-msg').classList.remove('d-none');
                
                setTimeout(() => {
                    window.location.href = '{{ route("inscricoes.index") }}';
                }, 2000);
            } else if (data.status === 'cancelado') {
                clearInterval(poller);
                document.getElementById('pix-content').classList.add('d-none');
                document.getElementById('botoes-acoes').classList.add('d-none');
                document.getElementById('erro-msg').classList.remove('d-none');
                
                setTimeout(() => {
                    window.location.href = '{{ route("inscricoes.index") }}';
                }, 2000);
            }
        })
        .catch(err => console.error("Erro na verificação de status:", err));
}

// Verifica sozinho a cada 5 segundos
let poller = setInterval(checarPagamento, 5000);

function atualizarStatusManual() {
    clearInterval(poller); // Pausa pra evitar colisão de requests
    checarPagamento();
    
    let btn = document.getElementById('btn-checar');
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Verificando...';
    setTimeout(() => {
        btn.innerHTML = '<i class="fas fa-sync"></i> Já Paguei / Atualizar Status';
        poller = setInterval(checarPagamento, 5000);
    }, 2000);
}
</script>
@endsection
