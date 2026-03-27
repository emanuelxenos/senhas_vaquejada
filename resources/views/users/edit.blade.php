@extends('layout')

@section('page-title', 'Editar Usuário')

@section('content')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary me-3">
        <i class="fas fa-arrow-left"></i> Voltar
    </a>
    <h1 class="mb-0">Editar Usuário</h1>
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

        <form method="POST" action="{{ route('users.update', $user) }}">
            @csrf
            @method('PUT')

            <div class="row">
                <!-- Lado Esquerdo: Dados Pessoais -->
                <div class="col-md-6 mb-4">
                    <h5 class="border-bottom pb-2 mb-3">Informações Pessoais</h5>
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Nome Completo</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name', $user->name) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email de Login</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email', $user->email) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="role" class="form-label">Nível de Acesso (Cargo)</label>
                        <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required
                            @if($user->id === auth()->id()) disabled @endif>
                            <option value="locutor" {{ old('role', $user->role) == 'locutor' ? 'selected' : '' }}>Locutor (Somente visualiza)</option>
                            <option value="secretario" {{ old('role', $user->role) == 'secretario' ? 'selected' : '' }}>Secretário (Acesso aos cadastros diarios)</option>
                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Administrador (Acesso total)</option>
                        </select>
                        @if($user->id === auth()->id())
                            <small class="text-danger">Você não pode alterar seu próprio nível de acesso.</small>
                            <input type="hidden" name="role" value="{{ $user->role }}">
                        @endif
                    </div>
                </div>

                <!-- Lado Direito: Segurança -->
                <div class="col-md-6 mb-4">
                    <h5 class="border-bottom pb-2 mb-3">Segurança da Conta</h5>
                    
                    <div class="alert alert-warning py-2 mb-3">
                        Deixe a senha em branco caso não queira modificá-la.
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Nova Senha</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                               id="password" name="password" autocomplete="new-password">
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirmar Nova Senha</label>
                        <input type="password" class="form-control" 
                               id="password_confirmation" name="password_confirmation" autocomplete="new-password">
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end">
                <a href="{{ route('users.index') }}" class="btn btn-secondary me-2">Cancelar</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Salvar Alterações
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
