@extends('layout')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">
                    <i class="fas fa-hashtag"></i> Cadastrar Senhas
                </h4>
                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalSenhasVendidas">
                    <i class="fas fa-eye"></i> Senhas Já Existentes
                </button>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('senhas.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="inscricao_id" class="form-label">Inscrição *</label>
                        <select id="inscricao_id" name="inscricao_id" class="form-select @error('inscricao_id') is-invalid @enderror" required>
                            <option value="">Selecione uma inscrição...</option>
                            @foreach($inscricoes as $inscricao)
                                <option value="{{ $inscricao->id }}">
                                    {{ $inscricao->vaqueiro->nome }} & {{ $inscricao->bateEsteira->nome }}
                                    ({{ $inscricao->senhas_count ?? 0 }}/{{ $inscricao->quantidade_senhas ?? 0 }} cadastradas)
                                </option>
                            @endforeach
                        </select>
                        @error('inscricao_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3" id="senhasFields">
                        <p class="text-muted">Selecione uma inscrição para ver os campos de senhas.</p>
                    </div>

                    @error('senhas')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('senhas.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Voltar
                        </a>
                        <button type="submit" class="btn btn-primary" id="submitBtn" style="display: none;">
                            <i class="fas fa-save"></i> Cadastrar Senhas
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal para exibir senhas já vendidas -->
<div class="modal fade" id="modalSenhasVendidas" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header bg-light">
        <h5 class="modal-title"><i class="fas fa-list-ol text-primary"></i> Senhas Ativas no Sistema</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="text-muted small mb-3">Estes números já foram atribuídos a alguma inscrição e não devem ser repetidos:</p>
        @if(count($senhasVendidas) > 0)
            <div class="d-flex flex-wrap gap-2">
                @foreach($senhasVendidas as $numSenha)
                    <span class="badge bg-dark fs-6">{{ $numSenha }}</span>
                @endforeach
            </div>
        @else
            <div class="text-center text-muted p-4">
                <i class="fas fa-inbox fa-3x mb-2 opacity-50"></i>
                <p class="mb-0">Nenhuma senha cadastrada livremente ainda.</p>
            </div>
        @endif
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Entendi</button>
      </div>
    </div>
  </div>
</div>

<script>
    const inscricoes = @json($inscricoes);
    const campo = document.getElementById('senhasFields');
    const select = document.getElementById('inscricao_id');
    const submitBtn = document.getElementById('submitBtn');

    select.addEventListener('change', () => {
        campo.innerHTML = '';
        submitBtn.style.display = 'none';

        const id = parseInt(select.value, 10);
        const inscricao = inscricoes.find(item => item.id === id);

        if (inscricao) {
            const quantidade = parseInt(inscricao.quantidade_senhas ?? 0, 10);
            const jaCadastradas = parseInt(inscricao.senhas_count ?? 0, 10);
            const restantes = Math.max(quantidade - jaCadastradas, 0);

            // Verificar se já tem senhas cadastradas
            if (jaCadastradas > 0) {
                campo.innerHTML = `
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        Esta inscrição já possui ${jaCadastradas} senha(s) cadastrada(s).
                        Restam ${restantes} senha(s) para completar o pacote.
                    </div>
                `;
            }

            if (restantes === 0) {
                campo.innerHTML += `
                    <div class="alert alert-success mb-0">
                        <i class="fas fa-check-circle"></i>
                        Esta inscrição já está completa.
                    </div>
                `;
                return;
            }

            // Adicionar campos para as senhas restantes
            for (let i = 1; i <= restantes; i++) {
                const div = document.createElement('div');
                div.className = 'mb-2';
                div.innerHTML = `
                    <label class="form-label">Senha ${i}</label>
                    <input class="form-control" name="senhas[]" placeholder="Ex: 001, 002, etc." required />
                `;
                campo.appendChild(div);
            }

            submitBtn.style.display = 'block';
        } else {
            campo.innerHTML = '<p class="text-muted">Selecione uma inscrição para ver os campos de senhas.</p>';
        }
    });
</script>
@endsection
