<?php

namespace App\Services\Pagamento;

use App\Models\Inscricao;

interface PaymentGatewayInterface
{
    /**
     * Gera uma cobrança via Pix dinâmico.
     * Retorna um array com 'transaction_id', 'qr_code' (copia/cola) e 'qr_code_url' (imagem).
     */
    public function gerarPix(Inscricao $inscricao, float $valor): array;

    /**
     * (Opcional) Gera uma transação via cartão de crédito.
     */
    public function gerarCartao(Inscricao $inscricao, array $dadosCartao, float $valor): array;
}
