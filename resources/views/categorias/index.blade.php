@extends('layout')

@section('page-title', 'Categorias')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Gerenciamento de Categorias</h2>
            <p class="text-muted mb-0">Defina os preços, limites de senhas e regras de bois na pista de cada categoria do evento.</p>
        </div>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="bi bi-plus-lg"></i> Nova Categoria
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Nome da Categoria</th>
                            <th>Preço por Senha</th>
                            <th>Limite por Vaqueiro</th>
                            <th>Quantidade de Bois</th>
                            <th>Mínimo para Sucesso (Senha Batida)</th>
                            <th class="text-end pe-4">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categorias as $categoria)
                            <tr>
                                <td class="ps-4 fw-bold text-dark">{{ $categoria->nome }}</td>
                                <td>R$ {{ number_format($categoria->preco_senha, 2, ',', '.') }}</td>
                                <td>{{ $categoria->limite_senhas_por_vaqueiro }} senha(s)</td>
                                <td>{{ $categoria->quantidade_bois }} boi(s)</td>
                                <td>{{ $categoria->minimo_bois_sucesso }} boi(s) batido(s)</td>
                                <td class="text-end pe-4">
                                    <button class="btn btn-sm btn-outline-secondary me-2" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editModal{{ $categoria->id }}">
                                        Editar
                                    </button>
                                    <form action="{{ route('categorias.destroy', $categoria->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Deseja realmente excluir esta categoria?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            Excluir
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editModal{{ $categoria->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <form action="{{ route('categorias.update', $categoria->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Editar Categoria: {{ $categoria->nome }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body text-start">
                                                <div class="mb-3">
                                                    <label class="form-label" for="nome-edit-{{ $categoria->id }}">Nome da Categoria</label>
                                                    <input id="nome-edit-{{ $categoria->id }}" name="nome" type="text" class="form-control" value="{{ old('nome', $categoria->nome) }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label" for="preco-edit-{{ $categoria->id }}">Preço por Senha (R$)</label>
                                                    <input id="preco-edit-{{ $categoria->id }}" name="preco_senha" type="number" step="0.01" min="0" class="form-control" value="{{ old('preco_senha', $categoria->preco_senha) }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label" for="limite-edit-{{ $categoria->id }}">Limite de Senhas por Vaqueiro</label>
                                                    <input id="limite-edit-{{ $categoria->id }}" name="limite_senhas_por_vaqueiro" type="number" min="1" max="10" class="form-control" value="{{ old('limite_senhas_por_vaqueiro', $categoria->limite_senhas_por_vaqueiro) }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label" for="qtd-bois-edit-{{ $categoria->id }}">Quantidade de Bois por Senha</label>
                                                    <input id="qtd-bois-edit-{{ $categoria->id }}" name="quantidade_bois" type="number" min="1" max="10" class="form-control" value="{{ old('quantidade_bois', $categoria->quantidade_bois) }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label" for="minimo-edit-{{ $categoria->id }}">Bois Mínimos para Sucesso (Senha Batida)</label>
                                                    <input id="minimo-edit-{{ $categoria->id }}" name="minimo_bois_sucesso" type="number" min="1" max="10" class="form-control" value="{{ old('minimo_bois_sucesso', $categoria->minimo_bois_sucesso) }}" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">Nenhuma categoria cadastrada.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Create Modal -->
    <div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('categorias.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Nova Categoria</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-start">
                        <div class="mb-3">
                            <label class="form-label" for="nome-create">Nome da Categoria</label>
                            <input id="nome-create" name="nome" type="text" class="form-control" placeholder="Ex: Aberto, Amador, Feminina" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="preco-create">Preço por Senha (R$)</label>
                            <input id="preco-create" name="preco_senha" type="number" step="0.01" min="0" class="form-control" placeholder="100.00" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="limite-create">Limite de Senhas por Vaqueiro</label>
                            <input id="limite-create" name="limite_senhas_por_vaqueiro" type="number" min="1" max="10" class="form-control" value="2" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="qtd-bois-create">Quantidade de Bois por Senha</label>
                            <input id="qtd-bois-create" name="quantidade_bois" type="number" min="1" max="10" class="form-control" value="3" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="minimo-create">Bois Mínimos para Sucesso (Senha Batida)</label>
                            <input id="minimo-create" name="minimo_bois_sucesso" type="number" min="1" max="10" class="form-control" value="2" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Criar Categoria</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
