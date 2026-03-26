@extends('layout')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="mb-2">Competidores</h1>
        <p class="text-muted mb-0">Gerencie os competidores cadastrados no sistema</p>
    </div>
    <a class="btn btn-primary" href="{{ route('competidores.create') }}">
        <i class="fas fa-plus"></i> Novo Competidor
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Nome</th>
                        <th>CPF</th>
                        <th>Cidade</th>
                        <th>Representação</th>
                        <th>Cadastrado em</th>
                        <th width="150">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($competidores as $competidor)
                        <tr>
                            <td>{{ $competidor->nome }}</td>
                            <td>{{ $competidor->cpf }}</td>
                            <td>{{ $competidor->cidade }}</td>
                            <td>{{ $competidor->representacao }}</td>
                            <td>{{ $competidor->created_at->format('d/m/Y') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('competidores.edit', $competidor) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('competidores.destroy', $competidor) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Tem certeza que deseja excluir este competidor?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="fas fa-users fa-2x mb-2"></i>
                                <br>
                                Nenhum competidor cadastrado ainda.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection