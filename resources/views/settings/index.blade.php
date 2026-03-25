@extends('layout')

@section('page-title', 'Configurações do Parque')

@section('content')
    <h2>Configurações do Parque</h2>
    <p>Personalize os dados do parque para relatórios e PDFs.</p>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('settings.update') }}">
        @csrf

        <div class="mb-3">
            <label class="form-label" for="parque-name">Nome do Parque</label>
            <input id="parque-name" name="parque[name]" type="text" class="form-control @error('parque.name') is-invalid @enderror" value="{{ old('parque.name', $config['parque.name']) }}" required>
            @error('parque.name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label" for="parque-city">Cidade</label>
            <input id="parque-city" name="parque[city]" type="text" class="form-control @error('parque.city') is-invalid @enderror" value="{{ old('parque.city', $config['parque.city']) }}" required>
            @error('parque.city')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label" for="parque-state">Estado</label>
            <input id="parque-state" name="parque[state]" type="text" class="form-control @error('parque.state') is-invalid @enderror" value="{{ old('parque.state', $config['parque.state']) }}" required>
            @error('parque.state')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label" for="parque-contact">Contato</label>
            <input id="parque-contact" name="parque[contact]" type="text" class="form-control @error('parque.contact') is-invalid @enderror" value="{{ old('parque.contact', $config['parque.contact']) }}" required>
            @error('parque.contact')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <button type="submit" class="btn btn-primary">Salvar Configurações</button>
    </form>
@endsection
