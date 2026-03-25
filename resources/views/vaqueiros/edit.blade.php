@extends('layout')

@section('content')
<h1>Editar Vaqueiro</h1>
<form method="POST" action="{{ route('vaqueiros.update', $vaqueiro) }}">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label class="form-label">Puxador</label>
        <input type="text" name="nome" class="form-control" value="{{ $vaqueiro->nome }}" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Esteira</label>
        <input type="text" name="esteira" class="form-control" value="{{ $vaqueiro->esteira }}" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Forma de Pagamento</label>
        <select name="pagamento" class="form-select" required>
            <option value="A vista" {{ $vaqueiro->pagamento == 'A vista' ? 'selected' : '' }}>À vista</option>
            <option value="Crediario" {{ $vaqueiro->pagamento == 'Crediario' ? 'selected' : '' }}>Crediário</option>
            <option value="Troca" {{ $vaqueiro->pagamento == 'Troca' ? 'selected' : '' }}>Troca</option>
            <option value="Cheque" {{ $vaqueiro->pagamento == 'Cheque' ? 'selected' : '' }}>Cheque</option>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Representação</label>
        <input type="text" name="repre" class="form-control" value="{{ $vaqueiro->representacao }}" required>
    </div>

    <button class="btn btn-success">Salvar</button>
</form>
@endsection
