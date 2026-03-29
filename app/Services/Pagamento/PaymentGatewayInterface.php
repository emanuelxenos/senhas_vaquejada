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
     * Consulta o status da transação em tempo real.
     * Deve retornar 'pendente', 'pago' ou 'cancelado'.
     */
    public function consultarStatus(string $transactionId): string;

    /**
     * (Opcional) Gera uma transação via cartão de crédito.
     */
    public function gerarCartao(Inscricao $inscricao, array $dadosCartao, float $valor): array;
}
