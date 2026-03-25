@extends('layout')

@section('content')
<h1>Listar Senhas</h1>
<p>Total de senhas: {{ $total }}</p>
<a class="btn btn-secondary mb-3" href="{{ route('vaqueiros.index') }}">Voltar</a>
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Número</th>
            <th>Vaqueiro</th>
        </tr>
    </thead>
    <tbody>
        @foreach($senhas as $senha)
            <tr>
                <td>{{ $senha->numero }}</td>
                <td>{{ $senha->vaqueiro->nome }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
