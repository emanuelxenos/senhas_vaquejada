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

        <hr class="my-4">
        <h4>Integração de Pagamento Online</h4>
        <div class="mb-3">
            <label class="form-label" for="payment-gateway">Gateway Ativo</label>
            <select id="payment-gateway" name="payment[gateway]" class="form-select @error('payment.gateway') is-invalid @enderror">
                <option value="none" {{ old('payment.gateway', $config['payment.gateway'] ?? 'none') == 'none' ? 'selected' : '' }}>Nenhum (Apenas modo Offline)</option>
                <option value="asaas" {{ old('payment.gateway', $config['payment.gateway'] ?? 'none') == 'asaas' ? 'selected' : '' }}>Asaas</option>
                <option value="pagseguro" {{ old('payment.gateway', $config['payment.gateway'] ?? 'none') == 'pagseguro' ? 'selected' : '' }}>PagSeguro</option>
            </select>
            @error('payment.gateway')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div id="asaas-config" style="display: none;">
            <div class="mb-3">
                <label class="form-label" for="payment-asaas-api-key">Chave de API (Asaas)</label>
                <input id="payment-asaas-api-key" name="payment[asaas_api_key]" type="text" class="form-control @error('payment.asaas_api_key') is-invalid @enderror" value="{{ old('payment.asaas_api_key', $config['payment.asaas_api_key'] ?? '') }}">
                <small class="text-muted">Apenas preencha se o Asaas estiver ativo. Uma chave Sandbox ou Produção.</small>
                @error('payment.asaas_api_key')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label" for="payment-asaas-env">Ambiente do Asaas</label>
                <select id="payment-asaas-env" name="payment[asaas_env]" class="form-select @error('payment.asaas_env') is-invalid @enderror">
                    <option value="sandbox" {{ old('payment.asaas_env', $config['payment.asaas_env'] ?? 'sandbox') == 'sandbox' ? 'selected' : '' }}>Sandbox (Testes)</option>
                    <option value="production" {{ old('payment.asaas_env', $config['payment.asaas_env'] ?? 'sandbox') == 'production' ? 'selected' : '' }}>Produção (Real)</option>
                </select>
                @error('payment.asaas_env')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        <div id="pagseguro-config" style="display: none;">
            <div class="mb-3">
                <label class="form-label" for="payment-pagseguro-token">Token (PagSeguro / PagBank)</label>
                <input id="payment-pagseguro-token" name="payment[pagseguro_token]" type="text" class="form-control @error('payment.pagseguro_token') is-invalid @enderror" value="{{ old('payment.pagseguro_token', $config['payment.pagseguro_token'] ?? '') }}">
                <small class="text-muted">Insira seu token Vendedor/Empresarial do PagSeguro (Produção ou Sandbox).</small>
                @error('payment.pagseguro_token')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label" for="payment-pagseguro-env">Ambiente do PagSeguro</label>
                <select id="payment-pagseguro-env" name="payment[pagseguro_env]" class="form-select @error('payment.pagseguro_env') is-invalid @enderror">
                    <option value="sandbox" {{ old('payment.pagseguro_env', $config['payment.pagseguro_env'] ?? 'sandbox') == 'sandbox' ? 'selected' : '' }}>Sandbox (Testes)</option>
                    <option value="production" {{ old('payment.pagseguro_env', $config['payment.pagseguro_env'] ?? 'sandbox') == 'production' ? 'selected' : '' }}>Produção (Real)</option>
                </select>
                @error('payment.pagseguro_env')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Salvar Configurações</button>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const gatewaySelect = document.getElementById('payment-gateway');
            const asaasDiv = document.getElementById('asaas-config');
            const pagseguroDiv = document.getElementById('pagseguro-config');

            function toggleGateways() {
                asaasDiv.style.display = 'none';
                pagseguroDiv.style.display = 'none';

                if (gatewaySelect.value === 'asaas') {
                    asaasDiv.style.display = 'block';
                } else if (gatewaySelect.value === 'pagseguro') {
                    pagseguroDiv.style.display = 'block';
                }
            }

            gatewaySelect.addEventListener('change', toggleGateways);
            toggleGateways(); // Call on load
        });
    </script>
@endsection
