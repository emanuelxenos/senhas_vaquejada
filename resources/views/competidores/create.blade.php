@extends('layout')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">
                    <i class="fas fa-user-plus"></i> Cadastrar Competidor
                </h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('competidores.store') }}">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nome" class="form-label">Nome Completo *</label>
                            <input type="text" name="nome" id="nome" class="form-control @error('nome') is-invalid @enderror"
                                   value="{{ old('nome') }}" required>
                            @error('nome')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="cpf" class="form-label">CPF *</label>
                            <input type="text" name="cpf" id="cpf" class="form-control @error('cpf') is-invalid @enderror"
                                   value="{{ old('cpf') }}" placeholder="000.000.000-00" required>
                            @error('cpf')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="cidade" class="form-label">Cidade *</label>
                            <input type="text" name="cidade" id="cidade" class="form-control @error('cidade') is-invalid @enderror"
                                   value="{{ old('cidade') }}" required>
                            @error('cidade')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="representacao" class="form-label">Representação *</label>
                            <input type="text" name="representacao" id="representacao" class="form-control @error('representacao') is-invalid @enderror"
                                   value="{{ old('representacao') }}" placeholder="Ex: Rancho XYZ" required>
                            @error('representacao')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('competidores.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Voltar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Cadastrar Competidor
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Máscara para CPF
document.getElementById('cpf').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length <= 11) {
        value = value.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
        e.target.value = value;
    }
});
</script>
@endsection