@extends('layout')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="mb-2">Competidores</h1>
        <p class="text-muted mb-0">Gerencie os competidores cadastrados no sistema</p>
    </div>
    @can('manage-cadastros')
    <a class="btn btn-primary" href="{{ route('competidores.create') }}">
        <i class="fas fa-plus"></i> Novo Competidor
    </a>
    @endcan
</div>

<div class="card">
    <div class="card-body">
        <form method="GET" action="{{ route('competidores.index') }}" class="row g-2 mb-3">
            <div class="col-md-8">
                <input
                    type="text"
                    name="q"
                    class="form-control"
                    placeholder="Pesquisar por nome, CPF, cidade ou representação..."
                    value="{{ $search ?? '' }}"
                >
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i> Pesquisar
                </button>
                @if(!empty($search))
                    <a href="{{ route('competidores.index') }}" class="btn btn-outline-secondary">Limpar</a>
                @endif
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Nome</th>
                        <th>CPF</th>
                        <th>Cidade</th>
                        <th>Representação</th>
                        <th>Cadastrado em</th>
                        @can('manage-cadastros')
                        <th width="150">Ações</th>
                        @endcan
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
                            @can('manage-cadastros')
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
                            @endcan
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ auth()->user()->can('manage-cadastros') ? 6 : 5 }}" class="text-center text-muted py-4">
                                <i class="fas fa-users fa-2x mb-2"></i>
                                <br>
                                @if(!empty($search))
                                    Nenhum competidor encontrado para "{{ $search }}".
                                @else
                                    Nenhum competidor cadastrado ainda.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($competidores->hasPages())
            <div class="d-flex justify-content-center mt-3">
                {{ $competidores->links() }}
            </div>
        @endif
    </div>
</div>
@endsection