@extends('layouts.portal')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="card-title glow-text" style="margin-bottom: 0;">Pagamento da Inscrição</h1>
        <p class="text-muted text-sm mt-1">Conclua o pagamento via PIX para garantir suas senhas</p>
    </div>
    <a href="{{ route('portal.dashboard') }}" class="btn btn-secondary text-sm" style="width: auto; padding: 0.6rem 1.25rem;">Voltar ao Painel</a>
</div>

<div class="alert" style="background: rgba(59, 130, 246, 0.1); border: 1px solid rgba(59, 130, 246, 0.2); color: #93c5fd; max-width: 550px; margin: 0 auto 1.5rem auto;">
    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
    Lembre-se: Você só poderá escolher os números das senhas para correr após este pagamento ser confirmado.
</div>

<div class="card text-center" style="max-width: 550px; margin: 0 auto; padding: 3rem 2rem;">
    <h2 style="font-size: 2.5rem; margin-bottom: 0.5rem; color: var(--primary); font-family: 'Outfit';">R$ {{ number_format($inscricao->valor_total, 2, ',', '.') }}</h2>
    <p class="text-muted text-sm mb-6" style="background: rgba(255,255,255,0.05); display: inline-block; padding: 0.25rem 0.75rem; border-radius: 999px;">
        Inscrição #{{ str_pad($inscricao->id, 4, '0', STR_PAD_LEFT) }} • {{ $inscricao->quantidade_senhas }} senhas
    </p>

    <div style="background: white; padding: 1.5rem; border-radius: 16px; display: inline-block; margin-bottom: 2rem; box-shadow: 0 0 30px rgba(255,255,255,0.1); position: relative;">
        <!-- Corners decor -->
        <div style="position: absolute; top: 0; left: 0; width: 20px; height: 20px; border-top: 3px solid var(--primary); border-left: 3px solid var(--primary); border-top-left-radius: 16px;"></div>
        <div style="position: absolute; top: 0; right: 0; width: 20px; height: 20px; border-top: 3px solid var(--primary); border-right: 3px solid var(--primary); border-top-right-radius: 16px;"></div>
        <div style="position: absolute; bottom: 0; left: 0; width: 20px; height: 20px; border-bottom: 3px solid var(--primary); border-left: 3px solid var(--primary); border-bottom-left-radius: 16px;"></div>
        <div style="position: absolute; bottom: 0; right: 0; width: 20px; height: 20px; border-bottom: 3px solid var(--primary); border-right: 3px solid var(--primary); border-bottom-right-radius: 16px;"></div>
        
        <img src="data:image/png;base64,{{ $inscricao->gateway_qr_code_url }}" alt="QR Code PIX" style="max-width: 250px; height: auto; display: block;">
    </div>

    <div class="form-group text-left" style="background: rgba(15, 23, 42, 0.5); padding: 1.5rem; border-radius: 12px; border: 1px solid var(--glass-border);">
        <label class="form-label" for="pix_copia_cola" style="color: #cbd5e1; margin-bottom: 0.75rem;">Pix Copia e Cola:</label>
        <div class="flex flex-mobile-col gap-2">
            <input type="text" id="pix_copia_cola" class="form-control" value="{{ $inscricao->gateway_qr_code }}" readonly style="font-family: monospace; font-size: 0.85rem; background: rgba(0,0,0,0.2);">
            <button class="btn btn-primary" onclick="copiarPix()" style="width: auto; padding: 0.75rem 1.5rem; font-size: 0.95rem; white-space: nowrap;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 0.5rem;"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
                Copiar
            </button>
        </div>
    </div>

    <div id="status-container" class="mt-6 p-4" style="background: rgba(245, 158, 11, 0.1); border-radius: 12px; border: 1px solid rgba(245, 158, 11, 0.3); transition: all 0.5s ease;">
        <p style="color: #fbbf24; font-weight: 600; display: flex; align-items: center; justify-content: center; gap: 0.75rem; font-family: 'Outfit'; font-size: 1.1rem;">
            <svg class="animate-spin" xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="2" x2="12" y2="6"></line><line x1="12" y1="18" x2="12" y2="22"></line><line x1="4.93" y1="4.93" x2="7.76" y2="7.76"></line><line x1="16.24" y1="16.24" x2="19.07" y2="19.07"></line><line x1="2" y1="12" x2="6" y2="12"></line><line x1="18" y1="12" x2="22" y2="12"></line><line x1="4.93" y1="19.07" x2="7.76" y2="16.24"></line><line x1="16.24" y1="7.76" x2="19.07" y2="4.93"></line></svg>
            Aguardando pagamento...
        </p>
    </div>
</div>

<style>
    @keyframes spin { 100% { transform: rotate(360deg); } }
    .animate-spin { animation: spin 2s linear infinite; }
</style>

@push('scripts')
<script>
    function copiarPix() {
        var copyText = document.getElementById("pix_copia_cola");
        copyText.select();
        copyText.setSelectionRange(0, 99999);
        navigator.clipboard.writeText(copyText.value);
        
        // Visual feedback
        const btn = event.currentTarget;
        const originalText = btn.innerHTML;
        btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 0.5rem;"><polyline points="20 6 9 17 4 12"></polyline></svg> Copiado!';
        btn.style.background = 'var(--success)';
        
        setTimeout(() => {
            btn.innerHTML = originalText;
            btn.style.background = '';
        }, 2000);
    }

    let interval = setInterval(function() {
        fetch("{{ route('portal.inscricoes.status', $inscricao->id) }}")
            .then(response => response.json())
            .then(data => {
                if(data.status === 'pago') {
                    clearInterval(interval);
                    
                    const container = document.getElementById('status-container');
                    container.style.background = 'rgba(16, 185, 129, 0.15)';
                    container.style.borderColor = 'var(--primary)';
                    container.style.boxShadow = '0 0 20px rgba(16, 185, 129, 0.2)';
                    
                    container.innerHTML = `
                        <p style="color: #34d399; font-weight: 700; display: flex; align-items: center; justify-content: center; gap: 0.75rem; font-family: 'Outfit'; font-size: 1.25rem;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                            Pagamento Confirmado!
                        </p>
                    `;
                    
                    setTimeout(function() {
                        window.location.href = "{{ route('portal.dashboard') }}";
                    }, 3000);
                }
            });
    }, 5000);
</script>
@endpush
@endsection
