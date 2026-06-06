@extends('layout')

@section('page-title', 'Controle de Senhas e Corridas')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div>
        <h1 class="mb-2">Senhas de Corrida</h1>
        <p class="text-muted mb-0">Total: <strong>{{ $total }}</strong> senhas exibidas</p>
        <p class="text-muted small mb-0"><i class="fas fa-info-circle text-primary"></i> Clique em uma senha para ver detalhes @can('update-status')e atualizar os bois@endcan.</p>
    </div>
    <div class="d-flex flex-column align-items-end gap-3 w-sm-100">
        <div class="d-flex flex-wrap gap-2 justify-content-end w-100">
            @can('manage-cadastros')
            <a class="btn btn-primary" href="{{ route('senhas.create') }}">
                <i class="fas fa-plus"></i> Cadastrar Senhas
            </a>
            @endcan
            <select class="form-select shadow-sm" style="width: auto; min-width: 180px;" onchange="window.location.href = this.value">
                <option value="{{ route('senhas.index', ['status' => $statusFiltro, 'categoria_id' => 'todas']) }}">Todas as Categorias</option>
                @foreach($categorias as $cat)
                    <option value="{{ route('senhas.index', ['status' => $statusFiltro, 'categoria_id' => $cat->id]) }}" {{ $categoriaFiltro == $cat->id ? 'selected' : '' }}>
                        {{ $cat->nome }}
                    </option>
                @endforeach
            </select>
            <a class="btn btn-secondary" href="{{ route('inscricoes.index') }}">Voltar</a>
        </div>
        
        <div class="btn-group shadow-sm" role="group" aria-label="Filtro de Senhas">
            <a href="{{ route('senhas.index', ['status' => 'todos', 'categoria_id' => $categoriaFiltro]) }}" class="btn {{ ($statusFiltro ?? 'todos') === 'todos' ? 'btn-primary' : 'btn-outline-primary' }}">Todos</a>
            <a href="{{ route('senhas.index', ['status' => 'pendente', 'categoria_id' => $categoriaFiltro]) }}" class="btn {{ ($statusFiltro ?? '') === 'pendente' ? 'btn-warning' : 'btn-outline-warning' }}">Pendente</a>
            <a href="{{ route('senhas.index', ['status' => 'correu', 'categoria_id' => $categoriaFiltro]) }}" class="btn {{ ($statusFiltro ?? '') === 'correu' ? 'btn-secondary' : 'btn-outline-secondary' }}">Correu</a>
            <a href="{{ route('senhas.index', ['status' => 'boi_batido', 'categoria_id' => $categoriaFiltro]) }}" class="btn {{ ($statusFiltro ?? '') === 'boi_batido' ? 'btn-success' : 'btn-outline-success' }}">Boi Batido</a>
            <a href="{{ route('senhas.index', ['status' => 'cancelado', 'categoria_id' => $categoriaFiltro]) }}" class="btn {{ ($statusFiltro ?? '') === 'cancelado' ? 'btn-danger' : 'btn-outline-danger' }}">Cancelado</a>
        </div>
    </div>
</div>

<div class="senhas-grid">
    @foreach($senhas as $senha)
        <div class="senha-card {{ $senha->status == 'cancelado' ? 'cancelado' : '' }}"
             role="button"
             tabindex="0"
             data-id="{{ $senha->id }}"
             data-update-url="{{ route('senhas.update', $senha) }}"
             data-numero="{{ $senha->numero_senha }}"
             data-status="{{ $senha->status }}"
             data-dupla="{{ $senha->inscricao->vaqueiro->nome }} & {{ $senha->inscricao->bateEsteira->nome }}"
             data-tipo="{{ $senha->is_boi_tv ? 'boi_tv' : 'comum' }}"
             data-categoria="{{ $senha->inscricao->categoria->nome ?? 'N/A' }}"
             data-motivo="{{ $senha->motivo_cancelamento }}"
             data-cancelado_por="{{ $senha->cancelado_por }}"
             data-corridas='@json($senha->corridas)'
             data-bs-toggle="tooltip"
             data-bs-html="true"
             data-bs-placement="top"
             title="Vaqueiro: {{ $senha->inscricao->vaqueiro->nome }}<br>Bate-Esteira: {{ $senha->inscricao->bateEsteira->nome }}<br>Categoria: {{ $senha->inscricao->categoria->nome ?? 'N/A' }}<br>Tipo: {{ $senha->is_boi_tv ? 'Boi TV' : 'Comum' }}">
            
            <div class="senha-number">{{ $senha->numero_senha }}</div>
            <div style="font-size: 10px; color: rgba(255,255,255,0.85); font-weight: bold; margin-bottom: 4px; text-transform: uppercase;">
                {{ $senha->is_boi_tv ? 'Boi TV' : 'Comum' }}
            </div>
            
            <!-- Indicadores das corridas -->
            <div class="d-flex justify-content-center gap-1 mb-2">
                @foreach($senha->corridas as $corrida)
                    <span class="badge rounded-circle p-0 d-flex align-items-center justify-content-center"
                          style="width: 18px; height: 18px; font-size: 9px;
                                 @if($corrida->resultado == 'boi_batido') background-color: #198754; color: white;
                                 @elseif($corrida->resultado == 'zero') background-color: #dc3545; color: white;
                                 @else background-color: rgba(255,255,255,0.35); color: white; @endif"
                          title="Boi {{ $corrida->numero_corrida }}: {{ $corrida->resultado == 'boi_batido' ? 'Valeu o Boi' : ($corrida->resultado == 'zero' ? 'Zero (Correu)' : 'Pendente') }}">
                        {{ $corrida->numero_corrida }}
                    </span>
                @endforeach
            </div>

            <div class="senha-status">
                <span class="badge
                    @if($senha->status == 'boi_batido') bg-success
                    @elseif($senha->status == 'correu') bg-danger text-white
                    @elseif($senha->status == 'cancelado') bg-dark text-white
                    @else bg-warning text-dark @endif" id="badge-status-{{ $senha->id }}">
                    {{ ucfirst(str_replace('_', ' ', $senha->status)) }}
                </span>
            </div>
        </div>
    @endforeach
</div>

<!-- Modal: atualizar status e corridas -->
<div class="modal fade" id="senhaStatusModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Controle da Senha</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <div class="h5 fw-bold text-primary">Senha <span id="modalSenhaNumero"></span></div>
                    <div class="text-muted small" id="modalSenhaDupla"></div>
                    <div class="text-muted small" id="modalSenhaCategoria"></div>
                </div>

                <div class="mb-3 d-flex align-items-center gap-2">
                    <span class="fw-bold text-dark">Status Geral:</span>
                    <span class="badge" id="modalSenhaStatusBadge" style="font-size: 0.9rem; padding: 0.4rem 0.8rem;"></span>
                </div>

                <hr>

                <!-- Controle de bois de pista (Corrida) -->
                <div class="mb-4" id="secaoBoisPista">
                    <h6 class="fw-bold mb-3"><i class="fas fa-gavel text-warning"></i> Resultados dos Bois na Pista</h6>
                    <div id="corridasLista" class="d-flex flex-column gap-3">
                        <!-- Gerado por JavaScript -->
                    </div>
                </div>

                <!-- Formulário de Cancelamento (Apenas Admin/Secretaria) -->
                @can('manage-cadastros')
                <hr id="linhaDivisoriaCancelamento">
                <form method="POST" id="senhaStatusForm">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="modalSenhaStatus" class="form-label fw-bold">Alterar Status Manual</label>
                        <select class="form-select" name="status" id="modalSenhaStatus" required>
                            <!-- Será preenchido dinamicamente para preservar o calculado, ou permitir cancelar -->
                        </select>
                    </div>

                    <div id="motivoCancelamentoContainer" class="mt-3" style="display: none;">
                        <label for="modalSenhaMotivo" class="form-label text-danger fw-bold">Motivo do Cancelamento *</label>
                        <textarea class="form-control" name="motivo_cancelamento" id="modalSenhaMotivo" rows="2"></textarea>
                    </div>

                    <div class="modal-footer px-0 pb-0 mt-3">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Salvar Alterações
                        </button>
                    </div>
                </form>
                @else
                <div class="modal-footer px-0 pb-0 mt-3">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </div>
                @endcan
            </div>
        </div>
    </div>
</div>

<style>
    .senhas-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(110px, 1fr));
        gap: 12px;
        padding: 20px 0;
    }

    .senha-card {
        background: linear-gradient(135deg, #2c3e50 0%, #1a252f 100%);
        border-radius: 10px;
        padding: 10px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 110px;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        border: 2px solid transparent;
        position: relative;
    }

    .senha-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.3);
        border-color: #3498db;
    }

    .senha-card.cancelado {
        background: linear-gradient(135deg, #7f8c8d 0%, #95a5a6 100%);
    }

    .senha-number {
        font-size: 26px;
        font-weight: 800;
        color: white;
        text-align: center;
        margin-bottom: 2px;
        font-family: 'Outfit', sans-serif;
    }

    .senha-status {
        font-size: 10px;
    }

    /* Responsividade */
    @media (max-width: 768px) {
        .senhas-grid {
            grid-template-columns: repeat(auto-fill, minmax(95px, 1fr));
            gap: 10px;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const canUpdateStatus = @json(auth()->user()->can('update-status'));

        // Inicializar tooltips do Bootstrap
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        const modalEl = document.getElementById('senhaStatusModal');
        const modal = new bootstrap.Modal(modalEl);
        const form = document.getElementById('senhaStatusForm');
        const statusSelect = document.getElementById('modalSenhaStatus');
        const numeroEl = document.getElementById('modalSenhaNumero');
        const duplaEl = document.getElementById('modalSenhaDupla');
        const categoriaEl = document.getElementById('modalSenhaCategoria');
        const statusBadgeEl = document.getElementById('modalSenhaStatusBadge');
        const motivoContainer = document.getElementById('motivoCancelamentoContainer');
        const motivoInput = document.getElementById('modalSenhaMotivo');
        const listContainer = document.getElementById('corridasLista');

        if (statusSelect) {
            statusSelect.addEventListener('change', function() {
                if (this.value === 'cancelado') {
                    motivoContainer.style.display = 'block';
                    motivoInput.setAttribute('required', 'required');
                } else {
                    motivoContainer.style.display = 'none';
                    motivoInput.removeAttribute('required');
                }
            });
        }

        function openModalFromCard(card) {
            const senhaId = card.getAttribute('data-id');
            const url = card.getAttribute('data-update-url');
            const numero = card.getAttribute('data-numero') || '';
            const status = card.getAttribute('data-status') || 'pendente';
            const dupla = card.getAttribute('data-dupla') || '';
            const categoria = card.getAttribute('data-categoria') || '';
            const motivo = card.getAttribute('data-motivo') || '';
            const corridasRaw = card.getAttribute('data-corridas');
            const corridas = JSON.parse(corridasRaw) || [];

            if (form) {
                form.setAttribute('action', url);
            }

            // Atualizar statusBadge
            statusBadgeEl.className = 'badge ' + (
                status === 'boi_batido' ? 'bg-success' :
                (status === 'correu' ? 'bg-danger text-white' :
                (status === 'cancelado' ? 'bg-dark text-white' : 'bg-warning text-dark'))
            );
            statusBadgeEl.textContent = status.charAt(0).toUpperCase() + status.slice(1).replace('_', ' ');

            // Configurar select de alteração manual (evitando burlar os cálculos, apenas permitindo "Manter" ou "Cancelar")
            if (statusSelect) {
                statusSelect.innerHTML = '';
                
                if (status === 'cancelado') {
                    const optReativar = document.createElement('option');
                    optReativar.value = 'pendente';
                    optReativar.textContent = 'Reativar Senha (Calcular Automático)';
                    statusSelect.appendChild(optReativar);

                    const optManter = document.createElement('option');
                    optManter.value = 'cancelado';
                    optManter.textContent = 'Manter Cancelada';
                    optManter.selected = true;
                    statusSelect.appendChild(optManter);

                    // Se cancelada, esconde controle dos bois
                    const secaoBois = document.getElementById('secaoBoisPista');
                    if (secaoBois) secaoBois.style.display = 'none';
                } else {
                    const optCalculado = document.createElement('option');
                    optCalculado.value = status;
                    optCalculado.textContent = `Manter Calculado (${status.charAt(0).toUpperCase() + status.slice(1).replace('_', ' ')})`;
                    optCalculado.selected = true;
                    statusSelect.appendChild(optCalculado);

                    const optCancelar = document.createElement('option');
                    optCancelar.value = 'cancelado';
                    optCancelar.textContent = 'Cancelar Senha (Manual)';
                    statusSelect.appendChild(optCancelar);

                    const secaoBois = document.getElementById('secaoBoisPista');
                    if (secaoBois) secaoBois.style.display = 'block';
                }
                statusSelect.dispatchEvent(new Event('change'));
            }

            motivoInput.value = motivo;
            numeroEl.textContent = numero;
            duplaEl.textContent = dupla;
            categoriaEl.innerHTML = `<strong>Categoria:</strong> ${categoria}`;

            const formatDate = (isoString) => {
                if (!isoString) return '';
                const date = new Date(isoString);
                if (isNaN(date.getTime())) return '';
                const pad = (n) => String(n).padStart(2, '0');
                const day = pad(date.getDate());
                const month = pad(date.getMonth() + 1);
                const year = date.getFullYear();
                const hours = pad(date.getHours());
                const minutes = pad(date.getMinutes());
                const seconds = pad(date.getSeconds());
                return `${day}/${month}/${year} às ${hours}:${minutes}:${seconds}`;
            };

            // Renderizar bois/corridas
            if (listContainer) {
                listContainer.innerHTML = '';
                corridas.forEach(corrida => {
                    const item = document.createElement('div');
                    item.className = 'd-flex justify-content-between align-items-center p-2 rounded bg-light border';
                    
                    let resultBadge = '';
                    if(corrida.resultado === 'boi_batido') {
                        resultBadge = '<span class="badge bg-success">Valeu o Boi</span>';
                    } else if(corrida.resultado === 'zero') {
                        resultBadge = '<span class="badge bg-danger">Zero (Correu)</span>';
                    } else {
                        resultBadge = '<span class="badge bg-warning text-dark">Pendente</span>';
                    }

                    let timeInfo = '';
                    if (corrida.resultado !== 'pendente' && corrida.updated_at) {
                        timeInfo = `<small class="text-muted d-block mt-1" id="corrida-time-${corrida.id}"><i class="far fa-clock"></i> em ${formatDate(corrida.updated_at)}</small>`;
                    } else {
                        timeInfo = `<small class="text-muted d-block mt-1" id="corrida-time-${corrida.id}"></small>`;
                    }

                    let btnGroup = '';
                    if (canUpdateStatus) {
                        btnGroup = `
                            <div class="btn-group btn-group-sm" role="group">
                                <button type="button" class="btn btn-outline-success btn-update-corrida" data-corrida-id="${corrida.id}" data-resultado="boi_batido" ${corrida.resultado === 'boi_batido' ? 'disabled' : ''}>Valeu</button>
                                <button type="button" class="btn btn-outline-danger btn-update-corrida" data-corrida-id="${corrida.id}" data-resultado="zero" ${corrida.resultado === 'zero' ? 'disabled' : ''}>Zero</button>
                                <button type="button" class="btn btn-outline-secondary btn-update-corrida" data-corrida-id="${corrida.id}" data-resultado="pendente" ${corrida.resultado === 'pendente' ? 'disabled' : ''}>Pendente</button>
                            </div>
                        `;
                    }

                    item.innerHTML = `
                        <div>
                            <strong class="text-dark">Boi ${corrida.numero_corrida}</strong>
                            <div class="mt-1" id="corrida-badge-${corrida.id}">${resultBadge}</div>
                            ${timeInfo}
                        </div>
                        ${btnGroup}
                    `;
                    listContainer.appendChild(item);
                });

                // Lidar com o clique para atualização via AJAX
                document.querySelectorAll('.btn-update-corrida').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const corridaId = this.getAttribute('data-corrida-id');
                        const resultado = this.getAttribute('data-resultado');
                        const buttonsInGroup = this.parentElement.querySelectorAll('.btn-update-corrida');
                        
                        buttonsInGroup.forEach(b => b.disabled = true); // Bloqueia clicks repetidos

                        fetch(`/corridas/${corridaId}/update`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ resultado: resultado })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                // Re-habilitar botões menos o selecionado
                                buttonsInGroup.forEach(b => {
                                    if (b.getAttribute('data-resultado') === resultado) {
                                        b.disabled = true;
                                    } else {
                                        b.disabled = false;
                                    }
                                });

                                // Atualizar badge da corrida no modal
                                const badgeSpan = document.getElementById(`corrida-badge-${corridaId}`);
                                if(resultado === 'boi_batido') {
                                    badgeSpan.innerHTML = '<span class="badge bg-success">Valeu o Boi</span>';
                                } else if(resultado === 'zero') {
                                    badgeSpan.innerHTML = '<span class="badge bg-danger">Zero (Correu)</span>';
                                } else {
                                    badgeSpan.innerHTML = '<span class="badge bg-warning text-dark">Pendente</span>';
                                }

                                // Atualizar a data/hora da corrida
                                const timeSpan = document.getElementById(`corrida-time-${corridaId}`);
                                if (timeSpan) {
                                    if (resultado !== 'pendente' && data.corrida_updated_at) {
                                        timeSpan.innerHTML = `<i class="far fa-clock"></i> em ${formatDate(data.corrida_updated_at)}`;
                                    } else {
                                        timeSpan.innerHTML = '';
                                    }
                                }

                                // Atualizar status geral no modal
                                statusBadgeEl.className = 'badge ' + (
                                    data.senha_status === 'boi_batido' ? 'bg-success' :
                                    (data.senha_status === 'correu' ? 'bg-danger text-white' :
                                    (data.senha_status === 'cancelado' ? 'bg-dark text-white' : 'bg-warning text-dark'))
                                );
                                statusBadgeEl.textContent = data.senha_status.charAt(0).toUpperCase() + data.senha_status.slice(1).replace('_', ' ');
                                
                                if (statusSelect) {
                                    statusSelect.options[0].value = data.senha_status;
                                    statusSelect.options[0].textContent = `Manter Calculado (${data.senha_status.charAt(0).toUpperCase() + data.senha_status.slice(1).replace('_', ' ')})`;
                                }

                                // Atualizar badge e atributo do card original para evitar desconfigurações
                                const cardOriginal = document.querySelector(`.senha-card[data-id="${senhaId}"]`);
                                if (cardOriginal) {
                                    cardOriginal.setAttribute('data-status', data.senha_status);
                                    
                                    // Atualizar a lista local de corridas no card
                                    const cardCorridas = JSON.parse(cardOriginal.getAttribute('data-corridas'));
                                    const corridaIndex = cardCorridas.findIndex(c => c.id == corridaId);
                                    if (corridaIndex !== -1) {
                                        cardCorridas[corridaIndex].resultado = resultado;
                                        if (data.corrida_updated_at) {
                                            cardCorridas[corridaIndex].updated_at = data.corrida_updated_at;
                                        }
                                        cardOriginal.setAttribute('data-corridas', JSON.stringify(cardCorridas));
                                    }

                                    // Atualizar visual do badge no card
                                    const cardBadge = document.getElementById(`badge-status-${senhaId}`);
                                    if(cardBadge) {
                                        cardBadge.className = 'badge ' + (
                                            data.senha_status === 'boi_batido' ? 'bg-success' :
                                            (data.senha_status === 'correu' ? 'bg-danger text-white' :
                                            (data.senha_status === 'cancelado' ? 'bg-dark text-white' : 'bg-warning text-dark'))
                                        );
                                        cardBadge.innerText = data.senha_status.charAt(0).toUpperCase() + data.senha_status.slice(1).replace('_', ' ');
                                    }

                                    // Atualizar a bolinha do Boi no card
                                    const cardBoiBadges = cardOriginal.querySelectorAll('.badge.rounded-circle');
                                    const boiNumber = cardCorridas[corridaIndex].numero_corrida;
                                    const targetBoiBadge = cardBoiBadges[boiNumber - 1];
                                    if (targetBoiBadge) {
                                        if(resultado === 'boi_batido') {
                                            targetBoiBadge.style.backgroundColor = '#198754';
                                        } else if(resultado === 'zero') {
                                            targetBoiBadge.style.backgroundColor = '#dc3545';
                                        } else {
                                            targetBoiBadge.style.backgroundColor = 'rgba(255,255,255,0.35)';
                                        }
                                    }
                                }
                            }
                        })
                        .catch(err => {
                            console.error(err);
                            buttonsInGroup.forEach(b => {
                                if (b.disabled && b.getAttribute('data-resultado') !== resultado) {
                                    b.disabled = false;
                                }
                            });
                        });
                    });
                });
            }

            modal.show();
        }

        document.querySelectorAll('.senha-card').forEach(card => {
            card.addEventListener('click', () => openModalFromCard(card));
            card.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    openModalFromCard(card);
                }
            });
        });
        
        modalEl.addEventListener('hidden.bs.modal', function () {
            window.location.reload();
        });
    });
</script>
@endsection
