@extends('layout')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="mb-2">Inscrições</h1>
        <p class="text-muted mb-0">Gerencie as inscrições e pacotes comprados</p>
    </div>
    <a class="btn btn-primary" href="{{ route('inscricoes.create') }}">
        <i class="fas fa-plus"></i> Nova Inscrição
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Dupla</th>
                        <th>Forma de Pagamento</th>
                        <th>Valor Total</th>
                        <th>Status</th>
                        <th>Senhas</th>
                        <th>Data</th>
                        <th width="200">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($inscricoes as $inscricao)
                        <tr>
                            <td>
                                <strong>{{ $inscricao->vaqueiro->nome }}</strong><br>
                                <small class="text-muted">com {{ $inscricao->bateEsteira->nome }}</small>
                            </td>
                            <td>{{ $inscricao->forma_pagamento }}</td>
                            <td>R$ {{ number_format($inscricao->valor_total, 2, ',', '.') }}</td>
                            <td>
                                <span class="badge
                                    @if($inscricao->status_pagamento == 'pago') bg-success
                                    @elseif($inscricao->status_pagamento == 'pendente') bg-warning
                                    @else bg-danger @endif">
                                    {{ ucfirst($inscricao->status_pagamento) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $inscricao->senhas->count() }}</span>
                            </td>
                            <td>{{ $inscricao->created_at->format('d/m/Y') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('inscricoes.edit', $inscricao) }}" class="btn btn-sm btn-outline-primary" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($inscricao->senhas->count() > 0)
                                        <a href="{{ route('inscricoes.pdf', $inscricao) }}" target="_blank" class="btn btn-sm btn-outline-secondary" title="PDF">
                                            <i class="fas fa-file-pdf"></i>
                                        </a>
                                    @endif
                                    <form action="{{ route('inscricoes.destroy', $inscricao) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Tem certeza que deseja excluir esta inscrição?')" title="Excluir">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="fas fa-clipboard-list fa-2x mb-2"></i>
                                <br>
                                Nenhuma inscrição cadastrada ainda.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection