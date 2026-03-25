@extends('layout')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="mb-2">Senhas Vendidas</h1>
        <p class="text-muted mb-0">Total: <strong>{{ $total }}</strong> senhas cadastradas</p>
    </div>
    <a class="btn btn-secondary" href="{{ route('vaqueiros.index') }}">Voltar</a>
</div>

<div class="senhas-grid">
    @foreach($senhas as $senha)
        <div class="senha-card" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $senha->vaqueiro->nome }}">
            <div class="senha-number">{{ $senha->numero }}</div>
        </div>
    @endforeach
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
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 90px;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(13, 110, 253, 0.15);
        border: 2px solid transparent;
    }

    .senha-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 6px 16px rgba(13, 110, 253, 0.3);
        border-color: #fff;
        background: linear-gradient(135deg, #0a58ca 0%, #084298 100%);
    }

    .senha-number {
        font-size: 28px;
        font-weight: bold;
        color: white;
        text-align: center;
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
    });
</script>
@endsection
