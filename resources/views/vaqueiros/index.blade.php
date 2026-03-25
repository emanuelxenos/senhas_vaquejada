@extends('layout')

@section('content')
<h1>Lista de Vaqueiros</h1>
<a class="btn btn-primary mb-3" href="{{ route('vaqueiros.create') }}">Cadastrar Vaqueiro</a>
<a class="btn btn-success mb-3" href="{{ route('senhas.create') }}">Cadastrar Senhas</a>
<a class="btn btn-secondary mb-3" href="{{ route('senhas.index') }}">Listar Senhas</a>
<a class="btn btn-info mb-3" href="{{ route('relatorio') }}" target="_blank">Relatório PDF</a>
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Nome</th>
            <th>Esteira</th>
            <th>Qtd</th>
            <th>Pagamento</th>
            <th>Representação</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        @foreach($vaqueiros as $vaqueiro)
            <tr>
                <td>{{ $vaqueiro->nome }}</td>
                <td>{{ $vaqueiro->esteira }}</td>
                <td>{{ $vaqueiro->quantidade }}</td>
                <td>{{ $vaqueiro->pagamento }}</td>
                <td>{{ $vaqueiro->representacao }}</td>
                <td>
                    <a href="{{ route('vaqueiros.edit', $vaqueiro) }}" class="btn btn-sm btn-warning">Editar</a>
                    <form action="{{ route('vaqueiros.destroy', $vaqueiro) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Excluir?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger">Excluir</button>
                    </form>
                    <a href="{{ route('vaqueiros.pdf', $vaqueiro) }}" target="_blank" class="btn btn-sm btn-secondary">PDF</a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
