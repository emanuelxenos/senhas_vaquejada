@extends('layout')

@section('page-title', 'Novo Usuário')

@section('content')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary me-3">
        <i class="fas fa-arrow-left"></i> Voltar
    </a>
    <h1 class="mb-0">Novo Usuário</h1>
</div>

<div class="card">
    <div class="card-body p-4">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('users.store') }}">
            @csrf

            <div class="row">
                <!-- Lado Esquerdo: Dados Pessoais -->
                <div class="col-md-6 mb-4">
                    <h5 class="border-bottom pb-2 mb-3">Informações Pessoais</h5>
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Nome Completo</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email de Login</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email') }}" required>
                        <small class="text-muted">Este email será usado para entrar no sistema.</small>
                    </div>

                    <div class="mb-3">
                        <label for="role" class="form-label">Nível de Acesso (Cargo)</label>
                        <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                            <option value="" disabled selected>Selecione um perfil...</option>
                            <option value="locutor" {{ old('role') == 'locutor' ? 'selected' : '' }}>Locutor (Somente visualiza)</option>
                            <option value="secretario" {{ old('role') == 'secretario' ? 'selected' : '' }}>Secretário (Acesso aos cadastros diarios)</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrador (Acesso total)</option>
                        </select>
                    </div>
                </div>

                <!-- Lado Direito: Segurança -->
                <div class="col-md-6 mb-4">
                    <h5 class="border-bottom pb-2 mb-3">Segurança da Conta</h5>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Senha Inicial</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                               id="password" name="password" required autocomplete="new-password">
                        <small class="text-muted">Mínimo de 8 caracteres.</small>
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirmar Senha</label>
                        <input type="password" class="form-control" 
                               id="password_confirmation" name="password_confirmation" required autocomplete="new-password">
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end">
                <a href="{{ route('users.index') }}" class="btn btn-secondary me-2">Cancelar</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Salvar Usuário
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
