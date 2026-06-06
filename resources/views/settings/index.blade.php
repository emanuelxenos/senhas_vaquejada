@extends('layout')

@section('page-title', 'Configurações do Parque')

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css">
<style>
    .img-crop-container {
        max-height: 400px;
        width: 100%;
        overflow: hidden;
        background-color: #f7f7f7;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .img-crop-container img {
        max-width: 100%;
        max-height: 400px;
        display: block;
    }
</style>
@endsection

@section('content')
    <h2>Configurações do Parque</h2>
    <p>Personalize os dados do parque para relatórios e PDFs.</p>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('settings.update') }}" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label class="form-label" for="parque-name">Nome do Parque</label>
            <input id="parque-name" name="parque[name]" type="text" class="form-control @error('parque.name') is-invalid @enderror" value="{{ old('parque.name', $config['parque.name']) }}" required>
            @error('parque.name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label" for="parque-logo">Logotipo do Parque</label>
            <input id="parque-logo" name="parque_logo" type="file" class="form-control @error('parque_logo') is-invalid @enderror">
            <small class="text-muted">Formatos aceitos: JPG, PNG, GIF, SVG, WEBP (Max 2MB). O logotipo poderá ser recortado após a seleção.</small>
            @if(!empty($config['parque.logo']))
                <div class="mt-2">
                    <img src="{{ asset($config['parque.logo']) }}" alt="Logo do Parque" style="max-height: 80px;" class="img-thumbnail">
                </div>
            @endif
            @error('parque_logo')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label" for="parque-cronograma">Cronograma / Programação do Evento</label>
            <textarea id="parque-cronograma" name="parque[cronograma]" class="form-control @error('parque.cronograma') is-invalid @enderror" rows="5" placeholder="Digite aqui a programação do evento, horários, categorias e outras instruções relevantes...">{{ old('parque.cronograma', $config['parque.cronograma'] ?? '') }}</textarea>
            <small class="text-muted">Esta informação será exibida em destaque na página inicial e no painel do vaqueiro.</small>
            @error('parque.cronograma')<div class="invalid-feedback">{{ $message }}</div>@enderror
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

        <div class="mb-3 d-none">
            <!-- Desativado pois o preço agora é definido na Categoria -->
            <label class="form-label" for="parque-preco-senha">Preço Padrão da Senha (R$)</label>
            <input id="parque-preco-senha" name="parque[preco_senha]" type="number" step="0.01" min="0" class="form-control" value="{{ old('parque.preco_senha', $config['parque.preco_senha']) }}" required>
        </div>

        <hr class="my-4">
        <h4>Regras de Bois e Limites de Senhas</h4>
        
        <div class="mb-3">
            <label class="form-label" for="senha-data-limite-boi-tv">Data Limite de Compra Online (Boi TV)</label>
            <input id="senha-data-limite-boi-tv" name="senha[data_limite_boi_tv]" type="date" class="form-control @error('senha.data_limite_boi_tv') is-invalid @enderror" value="{{ old('senha.data_limite_boi_tv', $config['senha.data_limite_boi_tv']) }}">
            <small class="text-muted">A partir desta data, a opção "Boi TV" só poderá ser comprada pela Secretaria/Caixa (ficará oculta no Portal).</small>
            @error('senha.data_limite_boi_tv')<div class="invalid-feedback">{{ $message }}</div>@enderror
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

    <!-- Modal para Recortar Imagem -->
    <div class="modal fade" id="cropModal" tabindex="-1" aria-labelledby="cropModalLabel" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cropModalLabel"><i class="fas fa-crop-alt me-2"></i>Recortar Logotipo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <div class="img-crop-container">
                        <img id="image-to-crop" src="">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" id="crop-button" class="btn btn-primary btn-sm">Recortar e Aplicar</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Lógica do painel de pagamento existente
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

            // Lógica do Cropper.js para Logotipo do Parque
            const fileInput = document.getElementById('parque-logo');
            const cropModalEl = new bootstrap.Modal(document.getElementById('cropModal'), {
                backdrop: 'static',
                keyboard: false
            });
            const imageToCrop = document.getElementById('image-to-crop');
            const cropConfirmBtn = document.getElementById('crop-button');
            
            let cropperInstance = null;
            let originalFile = null;
            let isCroppingConfirmed = false;

            fileInput.addEventListener('change', function(e) {
                const files = e.target.files;
                if (files && files.length > 0) {
                    originalFile = files[0];
                    isCroppingConfirmed = false;
                    
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        imageToCrop.src = event.target.result;
                        cropModalEl.show();
                    };
                    reader.readAsDataURL(originalFile);
                }
            });

            document.getElementById('cropModal').addEventListener('shown.bs.modal', function() {
                cropperInstance = new Cropper(imageToCrop, {
                    aspectRatio: 1, // Proporção padrão travada em 1:1 (quadrado)
                    viewMode: 1,
                    autoCropArea: 1,
                    responsive: true,
                    restore: false,
                    guides: true,
                    center: true,
                    highlight: false,
                    cropBoxMovable: true,
                    cropBoxResizable: true,
                    toggleDragModeOnDblclick: false,
                });
            });

            document.getElementById('cropModal').addEventListener('hidden.bs.modal', function() {
                if (cropperInstance) {
                    cropperInstance.destroy();
                    cropperInstance = null;
                }
                imageToCrop.src = '';
                
                if (!isCroppingConfirmed) {
                    fileInput.value = '';
                }
            });

            cropConfirmBtn.addEventListener('click', function() {
                if (cropperInstance) {
                    const canvas = cropperInstance.getCroppedCanvas({
                        maxWidth: 1200,
                        maxHeight: 1200,
                        imageSmoothingEnabled: true,
                        imageSmoothingQuality: 'high'
                    });

                    if (canvas) {
                        canvas.toBlob(function(blob) {
                            const extension = originalFile.name.split('.').pop() || 'png';
                            const croppedFile = new File([blob], 'cropped_logo.' + extension, {
                                type: originalFile.type
                            });

                            const dataTransfer = new DataTransfer();
                            dataTransfer.items.add(croppedFile);
                            fileInput.files = dataTransfer.files;

                            const container = fileInput.closest('.mb-3');
                            let imgPreview = container.querySelector('.img-thumbnail');
                            if (!imgPreview) {
                                const wrapper = document.createElement('div');
                                wrapper.className = 'mt-2';
                                imgPreview = document.createElement('img');
                                imgPreview.className = 'img-thumbnail';
                                imgPreview.style.maxHeight = '80px';
                                imgPreview.alt = 'Logo do Parque';
                                wrapper.appendChild(imgPreview);
                                container.appendChild(wrapper);
                            }
                            
                            imgPreview.src = URL.createObjectURL(blob);

                            isCroppingConfirmed = true;
                            cropModalEl.hide();
                        }, originalFile.type);
                    }
                }
            });
        });
    </script>
@endsection
