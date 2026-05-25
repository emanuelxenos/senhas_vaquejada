@extends('layouts.portal')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="card-title glow-text" style="margin-bottom: 0;">Minhas Inscrições</h1>
        <p class="text-muted text-sm mt-1">Acompanhe suas senhas e status de pagamento</p>
    </div>
    <a href="{{ route('portal.inscricoes.create') }}" class="btn btn-primary text-sm" style="width: auto; padding: 0.6rem 1.25rem;">+ Nova Inscrição</a>
</div>

@if($inscricoes->isEmpty())
    <div class="card text-center text-muted" style="padding: 4rem 2rem; border-style: dashed; border-color: rgba(255,255,255,0.1);">
        <div style="width: 80px; height: 80px; background: rgba(255,255,255,0.05); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="color: rgba(255,255,255,0.4);"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
        </div>
        <h3 style="color: #fff; margin-bottom: 0.5rem; font-family: 'Outfit';">Nenhuma inscrição encontrada</h3>
        <p class="text-sm">Você ainda não tem inscrições cadastradas. Compre sua senha online agora mesmo!</p>
        <a href="{{ route('portal.inscricoes.create') }}" class="btn btn-primary mt-6" style="width: auto;">Comprar Senhas</a>
    </div>
@else
    <div class="flex flex-col gap-4">
        @foreach($inscricoes as $insc)
            <div class="card" style="margin-bottom: 0; transition: all 0.3s ease;">
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <h3 style="font-size: 1.25rem; font-weight: 700; color: #fff; font-family: 'Outfit';">Inscrição #{{ str_pad($insc->id, 4, '0', STR_PAD_LEFT) }}</h3>
                        <p class="text-sm text-muted mt-1" style="display: flex; align-items: center; gap: 0.25rem;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                            {{ $insc->created_at->format('d/m/Y H:i') }}
                        </p>
                    </div>
                    <span class="badge badge-{{ $insc->status_pagamento }}">
                        {{ ucfirst($insc->status_pagamento) }}
                    </span>
                </div>
                
                <div class="flex justify-between items-center" style="border-top: 1px solid var(--glass-border); padding-top: 1.25rem; margin-top: 1.25rem;">
                    <div>
                        <p class="text-sm"><strong style="color: #cbd5e1; font-weight: 500;">Bate-Esteira:</strong> <span style="color: #fff;">{{ $insc->bateEsteira ? $insc->bateEsteira->nome : 'N/A' }}</span></p>
                        <p class="text-sm mt-2"><strong style="color: #cbd5e1; font-weight: 500;">Senhas:</strong> <span style="background: rgba(255,255,255,0.1); padding: 0.1rem 0.5rem; border-radius: 4px; color: #fff;">{{ $insc->quantidade_senhas }}</span></p>
                    </div>
                    <div style="text-align: right;">
                        <p style="font-size: 1.5rem; font-weight: 800; color: var(--primary); font-family: 'Outfit'; letter-spacing: -0.02em;">R$ {{ number_format($insc->valor_total, 2, ',', '.') }}</p>
                        
                        @if($insc->status_pagamento === 'pendente' && $insc->forma_pagamento === 'Pix (Gateway)')
                            <a href="{{ route('portal.inscricoes.pagamento', $insc->id) }}" class="btn btn-primary text-sm mt-2" style="padding: 0.4rem 1rem; width: auto; font-size: 0.85rem; box-shadow: 0 4px 10px rgba(16,185,129,0.2);">
                                Finalizar Pagamento
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
@endsection
