@extends('layout')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="mb-2">Inscrições</h1>
        <p class="text-muted mb-0">Gerencie as inscrições e pacotes comprados</p>
    </div>
    @can('manage-cadastros')
    <a class="btn btn-primary" href="{{ route('inscricoes.create') }}">
        <i class="fas fa-plus"></i> Nova Inscrição
    </a>
    @endcan
</div>

<div class="card">
    <div class="card-body">
        <form method="GET" action="{{ route('inscricoes.index') }}" class="row g-2 mb-3">
            <div class="col-md-8">
                <input
                    type="text"
                    name="q"
                    class="form-control"
                    placeholder="Pesquisar por nome do vaqueiro ou bate-esteira..."
                    value="{{ $search ?? '' }}"
                >
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i> Pesquisar
                </button>
                @if(!empty($search))
                    <a href="{{ route('inscricoes.index') }}" class="btn btn-outline-secondary">Limpar</a>
                @endif
            </div>
        </form>

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
                                <span class="badge bg-info">{{ $inscricao->senhas_count ?? 0 }}</span>
                            </td>
                            <td>{{ $inscricao->created_at->format('d/m/Y') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    @can('manage-cadastros')
                                    <a href="{{ route('inscricoes.edit', $inscricao) }}" class="btn btn-sm btn-outline-primary" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @endcan
                                    @if($inscricao->forma_pagamento == 'Pix (Gateway)' && $inscricao->status_pagamento == 'pendente')
                                        @if($inscricao->gateway_qr_code)
                                            <a href="{{ route('inscricoes.pagamento', $inscricao) }}" class="btn btn-sm btn-outline-success" title="Ver PIX Atual">
                                                <i class="fas fa-qrcode"></i>
                                            </a>
                                        @endif
                                        <form action="{{ route('inscricoes.gerarPix', $inscricao) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            <button class="btn btn-sm btn-outline-warning" onclick="return confirm('Isso gerará um novo código PIX para essa inscrição. Deseja continuar?')" title="Gerar Novo PIX">
                                                <i class="fas fa-sync"></i>
                                            </button>
                                        </form>
                                    @endif
                                    @if($inscricao->senhas->count() > 0)
                                        <a href="{{ route('inscricoes.pdf', $inscricao) }}" target="_blank" class="btn btn-sm btn-outline-secondary" title="PDF">
                                            <i class="fas fa-file-pdf"></i>
                                        </a>
                                    @endif
                                    @can('manage-cadastros')
                                    <form action="{{ route('inscricoes.destroy', $inscricao) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Tem certeza que deseja excluir esta inscrição?')" title="Excluir">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    @endcan>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="fas fa-clipboard-list fa-2x mb-2"></i>
                                <br>
                                @if(!empty($search))
                                    Nenhuma inscrição encontrada para "{{ $search }}".
                                @else
                                    Nenhuma inscrição cadastrada ainda.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($inscricoes->hasPages())
            <div class="d-flex justify-content-center mt-3">
                {{ $inscricoes->links() }}
            </div>
        @endif
    </div>
</div>
@endsection