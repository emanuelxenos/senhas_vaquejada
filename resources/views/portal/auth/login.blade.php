@extends('layouts.portal')

@section('content')
<div class="card" style="max-width: 420px; margin: 4rem auto; padding: 2.5rem;">
    <div class="card-header text-center">
        @php $logo = \App\Models\Setting::getValue('parque.logo') @endphp
        @if(!empty($logo))
            <div class="mb-4 text-center">
                <img src="{{ asset($logo) }}" alt="Logo" style="max-height: 80px; width: auto; border-radius: 8px;" class="d-block mx-auto">
            </div>
        @else
            <div style="width: 64px; height: 64px; background: var(--primary-glow); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; border: 1px solid var(--glass-border);">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--primary)"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
            </div>
        @endif
        <h1 class="card-title glow-text">Acesse o Portal</h1>
        <p class="text-muted text-sm mt-2">Faça login para gerenciar suas inscrições</p>
    </div>

    @if ($errors->any())
        <div class="alert alert-error text-sm">
            <ul style="padding-left: 1rem; margin: 0;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('portal.login.post') }}">
        @csrf
        <div class="form-group">
            <label class="form-label" for="email">E-mail</label>
            <input type="email" id="email" name="email" class="form-control" placeholder="seu@email.com" value="{{ old('email') }}" required autofocus>
        </div>

        <div class="form-group">
            <label class="form-label" for="password">Senha</label>
            <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
        </div>

        <button type="submit" class="btn btn-primary mt-6">Entrar</button>

        <div class="text-center mt-6 text-sm">
            <span class="text-muted">Ainda não é cadastrado?</span> 
            <a href="{{ route('portal.register') }}" style="color: var(--primary); text-decoration: none; font-weight: 600; transition: color 0.2s;">Crie sua conta</a>
        </div>
    </form>
</div>
@endsection
