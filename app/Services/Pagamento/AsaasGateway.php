<?php

namespace App\Services\Pagamento;

use App\Models\Inscricao;
use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Exception;

class AsaasGateway implements PaymentGatewayInterface
{
    protected string $apiKey;
    protected string $baseUrl;

    public function __construct()
    {
        $this->apiKey = Setting::getValue('payment.asaas_api_key', '');
        $env = Setting::getValue('payment.asaas_env', 'sandbox');
        
        $this->baseUrl = $env === 'sandbox' 
            ? 'https://sandbox.asaas.com/api/v3'
            : 'https://api.asaas.com/v3';
    }

    public function gerarPix(Inscricao $inscricao, float $valor): array
    {
        if (empty($this->apiKey)) {
            throw new Exception("Chave de API do Asaas não configurada.");
        }

        // 1. O Asaas exige um "Customer" (Cliente) para gerar a cobrança.
        // Se a gente não tem salvo no BD o customer_id, podemos buscar pelo CPF/Nome,
        // ou criar um cliente avulso para essa transação específica.
        // Como o foco atual não exigiu CPF na Inscrição nas migrations existentes, vou criar
        // com o nome da dupla (Vaqueiro + Esteira).
        
        $customerName = $inscricao->vaqueiro->nome;
        $customerCpf = preg_replace('/[^0-9]/', '', $inscricao->vaqueiro->cpf); // Limpar máscara

        $customerId = $this->getOrCreateCustomer($customerName, $customerCpf);

        // 2. Criar a cobrança (Charge) com BillingType = PIX
        $response = Http::withHeaders([
            'access_token' => $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post("{$this->baseUrl}/payments", [
            'customer' => $customerId,
            'billingType' => 'PIX',
            'value' => $valor,
            'dueDate' => date('Y-m-d', strtotime('+1 day')),
            'description' => "Inscrição Vaquejada #{$inscricao->id} - {$customerName}",
        ]);

        if ($response->failed()) {
            $errors = $response->json('errors');
            $errorMsg = $errors[0]['description'] ?? 'Erro desconhecido ao criar cobrança no Asaas.';
            throw new Exception("Asaas API Error: " . $errorMsg);
        }

        $paymentId = $response->json('id');

        // 3. Obter o payload do QRCode Dinâmico e Copia e Cola
        $qrResponse = Http::withHeaders([
            'access_token' => $this->apiKey,
        ])->get("{$this->baseUrl}/payments/{$paymentId}/pixQrCode");

        if ($qrResponse->failed()) {
            throw new Exception("Erro ao obter o QRCode Pix do Asaas.");
        }

        return [
            'transaction_id' => $paymentId,
            'qr_code' => $qrResponse->json('payload'),
            'qr_code_url' => $qrResponse->json('encodedImage'),
        ];
    }

    public function gerarCartao(Inscricao $inscricao, array $dadosCartao, float $valor): array
    {
        throw new Exception("Integração de cartão com Asaas ainda não implementada.");
    }

    public function consultarStatus(string $transactionId): string
    {
        $response = Http::withHeaders([
            'access_token' => $this->apiKey,
        ])->get("{$this->baseUrl}/payments/{$transactionId}");

        if ($response->successful()) {
            $status = $response->json('status');
            
            if (in_array($status, ['RECEIVED', 'CONFIRMED'])) {
                return 'pago';
            }
            if (in_array($status, ['OVERDUE', 'REFUNDED', 'DELETED'])) {
                return 'cancelado';
            }
        }
        
        return 'pendente';
    }

    /**
     * Cria ou retorna um cliente dummy por nome para receber a cobrança
     */
    private function getOrCreateCustomer(string $name, string $cpf): string
    {
        $response = Http::withHeaders([
            'access_token' => $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post("{$this->baseUrl}/customers", [
            'name' => $name,
            'cpfCnpj' => $cpf,
            'email' => "competidor-" . uniqid() . "@vaquejada.local"
        ]);

        if ($response->successful()) {
            return $response->json('id');
        }

        $errorMsg = "Desconhecido";
        if ($response->json('errors')) {
            $errorMsg = $response->json('errors')[0]['description'] ?? json_encode($response->json('errors'));
        }

        throw new Exception("Não foi possível registrar o cliente no Asaas: " . $errorMsg);
    }
}
