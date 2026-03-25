@extends('layout')

@section('content')
<h1>Cadastrar Senhas</h1>
<form method="POST" action="{{ route('senhas.store') }}">
    @csrf

    <div class="mb-3">
        <label class="form-label">Vaqueiro</label>
        <select id="vaqueiro_id" name="vaqueiro_id" class="form-select" required>
            <option value="">Selecione</option>
            @foreach($vaqueiros as $vaqueiro)
                <option value="{{ $vaqueiro->id }}">{{ $vaqueiro->nome }} ({{ $vaqueiro->quantidade }} senhas)</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3" id="senhasFields"></div>

    <button type="submit" class="btn btn-primary">Cadastrar</button>
</form>

<script>
    const vaqueiros = @json($vaqueiros);
    const campo = document.getElementById('senhasFields');
    const select = document.getElementById('vaqueiro_id');

    select.addEventListener('change', () => {
        campo.innerHTML = '';
        const id = parseInt(select.value, 10);
        const v = vaqueiros.find(item => item.id === id);
        if (v) {
            for (let i = 1; i <= v.quantidade; i++) {
                const div = document.createElement('div');
                div.className = 'mb-2';
                div.innerHTML = `<label class="form-label">Senha ${i}</label><input class="form-control" name="senhas[]" required />`;
                campo.appendChild(div);
            }
        }
    });
</script>
@endsection
