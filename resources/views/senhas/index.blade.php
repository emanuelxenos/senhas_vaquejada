@extends('layout')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="mb-2">Senhas</h1>
        <p class="text-muted mb-0">Total: <strong>{{ $total }}</strong> senhas cadastradas</p>
        <p class="text-muted small mb-0">Clique em uma senha para alterar o status.</p>
    </div>
    <div>
        <a class="btn btn-primary me-2" href="{{ route('senhas.create') }}">
            <i class="fas fa-plus"></i> Cadastrar Senhas
        </a>
        <a class="btn btn-secondary" href="{{ route('inscricoes.index') }}">Voltar</a>
    </div>
</div>

<div class="senhas-grid">
    @foreach($senhas as $senha)
        <div class="senha-card"
             role="button"
             tabindex="0"
             data-update-url="{{ route('senhas.update', $senha) }}"
             data-numero="{{ $senha->numero_senha }}"
             data-status="{{ $senha->status }}"
             data-dupla="{{ $senha->inscricao->vaqueiro->nome }} & {{ $senha->inscricao->bateEsteira->nome }}"
             data-bs-toggle="tooltip"
             data-bs-placement="top"
             title="Dupla: {{ $senha->inscricao->vaqueiro->nome }} & {{ $senha->inscricao->bateEsteira->nome }}">
            <div class="senha-number">{{ $senha->numero_senha }}</div>
            <div class="senha-status">
                <span class="badge
                    @if($senha->status == 'boi_batido') bg-success
                    @elseif($senha->status == 'correu') bg-light text-dark
                    @else bg-warning text-dark @endif">
                    {{ ucfirst(str_replace('_', ' ', $senha->status)) }}
                </span>
            </div>
        </div>
    @endforeach
</div>

<!-- Modal: atualizar status -->
<div class="modal fade" id="senhaStatusModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Atualizar status da senha</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <form method="POST" id="senhaStatusForm">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-2">
                        <div class="fw-bold">Senha <span id="modalSenhaNumero"></span></div>
                        <div class="text-muted small" id="modalSenhaDupla"></div>
                    </div>

                    <label for="modalSenhaStatus" class="form-label">Status</label>
                    <select class="form-select" name="status" id="modalSenhaStatus" required>
                        <option value="pendente">Pendente</option>
                        <option value="correu">Correu</option>
                        <option value="boi_batido">Boi batido</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Salvar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .senhas-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(90px, 1fr));
        gap: 12px;
        padding: 20px 0;
    }

    .senha-card {
        background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
        border-radius: 8px;
        padding: 8px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 90px;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(13, 110, 253, 0.15);
        border: 2px solid transparent;
        position: relative;
    }

    .senha-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 6px 16px rgba(13, 110, 253, 0.3);
        border-color: #fff;
        background: linear-gradient(135deg, #0a58ca 0%, #084298 100%);
    }

    .senha-number {
        font-size: 24px;
        font-weight: bold;
        color: white;
        text-align: center;
        margin-bottom: 4px;
    }

    .senha-status {
        font-size: 10px;
    }

    /* Responsividade */
    @media (max-width: 768px) {
        .senhas-grid {
            grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
            gap: 10px;
        }

        .senha-card {
            min-height: 80px;
        }

        .senha-number {
            font-size: 24px;
        }
    }

    @media (max-width: 480px) {
        .senhas-grid {
            grid-template-columns: repeat(auto-fill, minmax(70px, 1fr));
            gap: 8px;
        }

        .senha-card {
            min-height: 70px;
        }

        .senha-number {
            font-size: 20px;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
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

        function openModalFromCard(card) {
            const url = card.getAttribute('data-update-url');
            const numero = card.getAttribute('data-numero') || '';
            const status = card.getAttribute('data-status') || 'pendente';
            const dupla = card.getAttribute('data-dupla') || '';

            form.setAttribute('action', url);
            statusSelect.value = status;
            numeroEl.textContent = numero;
            duplaEl.textContent = dupla;
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
    });
</script>
@endsection
