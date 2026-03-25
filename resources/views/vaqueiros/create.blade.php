@extends('layout')

@section('content')
<h1>Cadastrar Vaqueiro</h1>
<form method="POST" action="{{ route('vaqueiros.store') }}">
    @csrf
    <div class="mb-3">
        <label class="form-label">Puxador</label>
        <input type="text" name="nome" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Esteira</label>
        <input type="text" name="esteira" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Forma de Pagamento</label>
        <select name="pagamento" class="form-select" required>
            <option value="">Escolha...</option>
            <option value="A vista">À vista</option>
            <option value="Crediario">Crediário</option>
            <option value="Troca">Troca</option>
            <option value="Cheque">Cheque</option>
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Quantidade de Senhas</label>
        <input type="number" name="qtd" class="form-control" min="0" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Representação</label>
        <input type="text" name="repre" class="form-control" required>
    </div>
    <button class="btn btn-primary">Cadastrar</button>
</form>
@endsection
