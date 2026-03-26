@extends('layout')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">
                    <i class="fas fa-edit"></i> Editar Senha
                </h4>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Dupla:</strong> {{ $senha->inscricao->vaqueiro->nome }} & {{ $senha->inscricao->bateEsteira->nome }}
                </div>
                <div class="mb-3">
                    <strong>Número da Senha:</strong> {{ $senha->numero_senha }}
                </div>

                <form method="POST" action="{{ route('senhas.update', $senha) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="status" class="form-label">Status *</label>
                        <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="pendente" {{ old('status', $senha->status) == 'pendente' ? 'selected' : '' }}>Pendente</option>
                            <option value="correu" {{ old('status', $senha->status) == 'correu' ? 'selected' : '' }}>Correu</option>
                            <option value="boi_batido" {{ old('status', $senha->status) == 'boi_batido' ? 'selected' : '' }}>Boi Batido</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('senhas.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Voltar
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Salvar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection