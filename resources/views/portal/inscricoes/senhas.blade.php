@extends('layouts.portal')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="card-title glow-text" style="margin-bottom: 0;">Escolha de Senhas</h1>
        <p class="text-muted text-sm mt-1">Inscrição #{{ str_pad($inscricao->id, 4, '0', STR_PAD_LEFT) }} • Bate-Esteira: {{ $inscricao->bateEsteira ? $inscricao->bateEsteira->nome : 'N/A' }}</p>
    </div>
    <a href="{{ route('portal.dashboard') }}" class="btn btn-secondary text-sm" style="width: auto; padding: 0.6rem 1.25rem;">Voltar ao Painel</a>
</div>

<div class="card" style="padding: 2.5rem;">
    @if ($errors->any())
        <div class="alert alert-error text-sm">
            <ul style="padding-left: 1rem; margin: 0;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if($inscricao->status_pagamento !== 'pago')
        <div class="alert" style="background: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.2); color: #fbbf24; margin-bottom: 2rem;">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
            <strong>Atenção:</strong> Você precisa realizar o pagamento desta inscrição antes de poder escolher seus números de senha.
        </div>
        
        <div class="text-center mt-6">
            <a href="{{ route('portal.inscricoes.pagamento', $inscricao->id) }}" class="btn btn-primary" style="width: auto;">
                Ir para o Pagamento PIX
            </a>
        </div>
    @else
        
        <!-- Senhas Já Escolhidas -->
        @if($inscricao->senhas->count() > 0)
            <div class="mb-8">
                <div class="flex flex-mobile-col justify-between items-center mb-4 gap-4">
                    <h3 style="color: #fff; font-family: 'Outfit'; font-size: 1.3rem; margin: 0; width: 100%;">Suas Senhas</h3>
                    
                    <div class="flex gap-2" style="width: 100%; justify-content: flex-start;">
                        <a href="{{ route('portal.inscricoes.pdf', $inscricao->id) }}" target="_blank" class="btn btn-secondary text-sm" style="padding: 0.4rem 1rem; width: auto; font-size: 0.8rem;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 0.4rem;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                            Baixar PDF
                        </a>
                        <button onclick="compartilharZap()" class="btn btn-primary text-sm" style="padding: 0.4rem 1rem; width: auto; font-size: 0.8rem; background: #25D366; box-shadow: 0 4px 15px rgba(37,211,102,0.2);">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 0.4rem;"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
                            WhatsApp
                        </button>
                    </div>
                </div>
                <div class="flex gap-4" style="flex-wrap: wrap;">
                    @foreach($inscricao->senhas as $senha)
                        <div style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.3); border-radius: 12px; padding: 1.5rem; text-align: center; min-width: 120px; box-shadow: 0 4px 15px rgba(16, 185, 129, 0.05);">
                            <span style="display: block; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; color: #34d399; margin-bottom: 0.5rem; font-weight: 700;">Número</span>
                            <span style="font-size: 2.5rem; font-family: 'Outfit'; font-weight: 800; color: #fff;">{{ $senha->numero_senha }}</span>
                            <span class="badge badge-{{ $senha->status }} mt-2" style="font-size: 0.7rem;">{{ ucfirst($senha->status) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Formulário de Escolha -->
        @if($restantes > 0)
            <div style="border-top: 1px solid var(--glass-border); padding-top: 2rem;">
                <h3 style="color: #fff; font-family: 'Outfit'; font-size: 1.3rem; margin-bottom: 0.5rem;">Escolher Números</h3>
                <p class="text-sm text-muted mb-6">Você tem direito a <strong>{{ $restantes }}</strong> senha(s). Digite o número desejado abaixo. O sistema avisará se o número já estiver em uso.</p>

                @if(count($senhasVendidas) > 0)
                <div class="mb-6">
                    <button type="button" onclick="openModalSenhas()" class="btn btn-secondary" style="width: 100%; border: 1px solid rgba(239, 68, 68, 0.3); color: #fca5a5; display: flex; gap: 0.5rem; justify-content: center; align-items: center; background: rgba(239, 68, 68, 0.05);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                        Ver Números Já Ocupados (Indisponíveis)
                    </button>
                </div>
                
                <!-- Modal de Senhas Indisponíveis -->
                <div id="modalSenhas" style="display: none; position: fixed; inset: 0; z-index: 9999; align-items: center; justify-content: center; padding: 1rem;">
                    <!-- Backdrop -->
                    <div style="position: absolute; inset: 0; background: rgba(0, 0, 0, 0.8); backdrop-filter: blur(5px);" onclick="closeModalSenhas()"></div>
                    
                    <!-- Modal Content -->
                    <div style="position: relative; background: #0f172a; border: 1px solid var(--glass-border); border-radius: 16px; width: 100%; max-width: 500px; max-height: 80vh; display: flex; flex-direction: column; box-shadow: 0 20px 50px rgba(0,0,0,0.5); z-index: 10000; animation: modalIn 0.3s ease;">
                        <div style="padding: 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.05); display: flex; justify-content: space-between; align-items: center;">
                            <h3 style="color: #fff; font-family: 'Outfit'; font-size: 1.25rem; margin: 0; display: flex; align-items: center; gap: 0.5rem;">
                                <svg style="color: #fca5a5;" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                                Números Já Ocupados
                            </h3>
                            <button type="button" onclick="closeModalSenhas()" style="background: none; border: none; color: var(--text-muted); cursor: pointer; padding: 0.5rem;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                            </button>
                        </div>
                        <div style="padding: 1.5rem; overflow-y: auto;">
                            <p class="text-sm text-muted mb-4">Os números abaixo já foram escolhidos por outros competidores e não podem ser utilizados.</p>
                            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(60px, 1fr)); gap: 0.75rem;">
                                @foreach($senhasVendidas as $sv)
                                    <div style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.2); color: #fca5a5; padding: 0.5rem; border-radius: 8px; text-align: center; font-weight: 700; font-family: 'Outfit';">
                                        {{ $sv }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div style="padding: 1.5rem; border-top: 1px solid rgba(255,255,255,0.05); text-align: center;">
                            <button type="button" onclick="closeModalSenhas()" class="btn btn-primary" style="width: 100%;">Entendi, Fechar</button>
                        </div>
                    </div>
                </div>
                <style>
                    @keyframes modalIn {
                        from { opacity: 0; transform: translateY(20px) scale(0.95); }
                        to { opacity: 1; transform: translateY(0) scale(1); }
                    }
                </style>
                @endif

                <form method="POST" action="{{ route('portal.inscricoes.senhas.store', $inscricao->id) }}">
                    @csrf
                    
                    <div class="flex flex-mobile-col gap-4" style="margin-bottom: 2rem;">
                        @for($i = 0; $i < $restantes; $i++)
                            <div class="form-group" style="margin-bottom: 0; flex: 1;">
                                <label class="form-label">Senha {{ $i + 1 }}</label>
                                <input type="number" name="senhas[]" class="form-control input-senha" placeholder="Ex: {{ rand(10, 99) }}" required oninput="verificarDisponibilidade(this)" min="1">
                                <span class="senha-status text-sm mt-1" style="display: block; min-height: 20px;"></span>
                            </div>
                        @endfor
                    </div>

                    <button type="submit" class="btn btn-primary" id="btn-submit">Confirmar Senhas</button>
                </form>
            </div>
        @elseif($inscricao->senhas->count() > 0)
            <div class="alert alert-success mt-4" style="justify-content: center; margin-bottom: 0;">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                Você já escolheu todas as suas senhas. Boa sorte na pista!
            </div>
        @endif

    @endif
</div>

@if(isset($restantes) && $restantes > 0)
@push('scripts')
<script>
    const senhasVendidas = @json($senhasVendidas ?? []);

    function openModalSenhas() {
        const modal = document.getElementById('modalSenhas');
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden'; // impede scroll atrás
    }

    function closeModalSenhas() {
        const modal = document.getElementById('modalSenhas');
        modal.style.display = 'none';
        document.body.style.overflow = '';
    }

    function compartilharZap() {
        const url = "{{ route('portal.inscricoes.pdf', $inscricao->id) }}";
        const texto = `Comprovante de Inscrição Vaquejada (Inscrição #${"{{ str_pad($inscricao->id, 4, '0', STR_PAD_LEFT) }}"}). Acesse e baixe suas senhas aqui: ${url}`;
        
        if (navigator.share) {
            navigator.share({
                title: 'Comprovante de Inscrição',
                text: texto,
                url: url
            }).catch(console.error);
        } else {
            window.open(`https://api.whatsapp.com/send?text=${encodeURIComponent(texto)}`, '_blank');
        }
    }

    function verificarDisponibilidade(input) {
        const val = input.value.trim();
        const statusSpan = input.nextElementSibling;
        
        if (!val) {
            statusSpan.innerHTML = '';
            input.style.borderColor = '';
            checkFormValidity();
            return;
        }

        if (senhasVendidas.includes(val)) {
            input.style.borderColor = 'var(--danger)';
            statusSpan.innerHTML = '<span style="color: #fca5a5;">❌ Já em uso</span>';
        } else {
            const allInputs = Array.from(document.querySelectorAll('.input-senha'));
            const duplicadoAqui = allInputs.filter(inp => inp.value.trim() === val).length > 1;
            
            if(duplicadoAqui) {
                input.style.borderColor = 'var(--warning)';
                statusSpan.innerHTML = '<span style="color: #fbbf24;">⚠️ Repetido no form</span>';
            } else {
                input.style.borderColor = 'var(--success)';
                statusSpan.innerHTML = '<span style="color: #6ee7b7;">✅ Disponível</span>';
            }
        }
        
        checkFormValidity();
    }

    function checkFormValidity() {
        const allInputs = Array.from(document.querySelectorAll('.input-senha'));
        const btn = document.getElementById('btn-submit');
        
        let temErro = false;
        let values = [];
        
        for (let inp of allInputs) {
            const val = inp.value.trim();
            if (val) {
                if (senhasVendidas.includes(val) || values.includes(val)) {
                    temErro = true;
                    break;
                }
                values.push(val);
            }
        }
        
        btn.disabled = temErro;
        if(temErro) {
            btn.style.opacity = '0.5';
            btn.style.cursor = 'not-allowed';
        } else {
            btn.style.opacity = '1';
            btn.style.cursor = 'pointer';
        }
    }
</script>
@endpush
@endif
@endsection
