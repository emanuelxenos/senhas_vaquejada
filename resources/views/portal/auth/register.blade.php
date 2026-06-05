@extends('layouts.portal')

@section('content')
<div class="card" style="max-width: 550px; margin: 2rem auto;">
    <div class="card-header text-center">
        @php $logo = \App\Models\Setting::getValue('parque.logo') @endphp
        @if(!empty($logo))
            <div class="mb-4 text-center">
                <img src="{{ asset($logo) }}" alt="Logo" style="max-height: 80px; width: auto; border-radius: 8px;" class="d-block mx-auto">
            </div>
        @endif
        <h1 class="card-title glow-text">Crie sua Conta</h1>
        <p class="text-muted text-sm mt-2">Faça seu cadastro para comprar senhas online com facilidade</p>
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

    <form method="POST" action="{{ route('portal.register.post') }}">
        @csrf
        
        <div class="form-group">
            <label class="form-label" for="name">Nome Completo</label>
            <input type="text" id="name" name="name" class="form-control" placeholder="João da Silva" value="{{ old('name') }}" required autofocus>
        </div>

        <div class="form-group">
            <label class="form-label" for="cpf">CPF (Apenas números)</label>
            <input type="text" id="cpf" name="cpf" class="form-control" placeholder="000.000.000-00" value="{{ old('cpf') }}" required>
        </div>

        <div class="flex flex-mobile-col gap-4">
            <div class="form-group" style="flex: 1;">
                <label class="form-label" for="cidade">Cidade/UF</label>
                <input type="text" id="cidade" name="cidade" class="form-control" placeholder="Ex: Carnaíba/PE" value="{{ old('cidade') }}" required>
            </div>
            
            <div class="form-group" style="flex: 1;">
                <label class="form-label" for="representacao">Representação</label>
                <input type="text" id="representacao" name="representacao" class="form-control" placeholder="Haras, Parque..." value="{{ old('representacao') }}">
            </div>
        </div>

        <div class="form-group">
            <label class="form-label" for="email">E-mail</label>
            <input type="email" id="email" name="email" class="form-control" placeholder="seu@email.com" value="{{ old('email') }}" required>
        </div>

        <div class="flex flex-mobile-col gap-4">
            <div class="form-group" style="flex: 1;">
                <label class="form-label" for="password">Senha</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>

            <div class="form-group" style="flex: 1;">
                <label class="form-label" for="password_confirmation">Confirmar Senha</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="••••••••" required>
            </div>
        </div>

        <button type="submit" class="btn btn-primary mt-6">Concluir Cadastro</button>

        <div class="text-center mt-6 text-sm">
            <span class="text-muted">Já tem uma conta?</span>
            <a href="{{ route('portal.login') }}" style="color: var(--primary); text-decoration: none; font-weight: 600;">Faça Login</a>
        </div>
    </form>
</div>
@endsection
