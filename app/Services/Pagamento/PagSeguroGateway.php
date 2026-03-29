<?php

namespace App\Services\Pagamento;

use App\Models\Inscricao;
use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PagSeguroGateway implements PaymentGatewayInterface
{
    private $token;
    private $env;
    private $baseUrl;

    public function __construct()
    {
        $this->token = Setting::getValue('payment.pagseguro_token', '');
        $this->env = Setting::getValue('payment.pagseguro_env', 'sandbox');
        
        $this->baseUrl = $this->env === 'production' 
            ? 'https://api.pagseguro.com' 
            : 'https://sandbox.api.pagseguro.com';
    }

    public function gerarPix(Inscricao $inscricao, float $valor): array
    {
        if (empty($this->token)) {
            throw new \Exception("Chave de API do PagSeguro não configurada.");
        }

        $cpf = preg_replace('/\D/', '', $inscricao->vaqueiro->cpf ?? '');
        
        // Em Sandbox, se o banco tiver salvo um CPF fake (ex: 12345678901), o PagSeguro vai barrar
        // Por isso, escrevi um gerador matemático de CPF válido e aleatório debaixo dos panos.
        if ($this->env === 'sandbox') {
            $n = array_map(function() { return rand(0, 9); }, range(1, 9));
            $d1 = 11 - (($n[0]*10 + $n[1]*9 + $n[2]*8 + $n[3]*7 + $n[4]*6 + $n[5]*5 + $n[6]*4 + $n[7]*3 + $n[8]*2) % 11);
            $d1 = $d1 >= 10 ? 0 : $d1;
            $d2 = 11 - (($n[0]*11 + $n[1]*10 + $n[2]*9 + $n[3]*8 + $n[4]*7 + $n[5]*6 + $n[6]*5 + $n[7]*4 + $n[8]*3 + $d1*2) % 11);
            $d2 = $d2 >= 10 ? 0 : $d2;
            $cpf = implode('', $n) . $d1 . $d2;
        } else {
            if (empty($cpf) || (strlen($cpf) !== 11 && strlen($cpf) !== 14)) {
                throw new \Exception("Para criar cobrança no PagSeguro (Produção) é necessário informar um CPF/CNPJ válido do Vaqueiro (11 ou 14 dígitos).");
            }
        }

        $valorEmCentavos = (int) round($valor * 100);

        $payload = [
            'reference_id' => 'INSC-' . $inscricao->id . '-' . time(),
            'customer' => [
                'name' => mb_substr($inscricao->vaqueiro->nome, 0, 50),
                'email' => 'contato@vaquejada' . $inscricao->id . '.com',
                'tax_id' => $cpf,
            ],
            'items' => [
                [
                    'name' => 'Taxa de Inscricao Vaquejada',
                    'quantity' => 1,
                    'unit_amount' => $valorEmCentavos
                ]
            ],
            'qr_codes' => [
                [
                    'amount' => [
                        'value' => $valorEmCentavos
                    ],
                    'expiration_date' => now()->addDay()->toAtomString()
                ]
            ]
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Content-Type' => 'application/json'
        ])->post($this->baseUrl . '/orders', $payload);

        if ($response->failed()) {
            throw new \Exception("PagSeguro API Error: " . $response->body());
        }

        $dados = $response->json();
        
        $qrCodeInfo = $dados['qr_codes'][0] ?? null;
        if (!$qrCodeInfo) {
            throw new \Exception("PagSeguro API Error: Não retornou bloco de QRCode.");
        }

        $linkImagem = collect($qrCodeInfo['links'] ?? [])->first(function ($link) {
            return strtolower($link['rel']) === 'qr_code.png' || strtolower($link['rel']) === 'qrcode.png';
        });

        $base64 = null;
        if ($linkImagem && isset($linkImagem['href'])) {
            try {
                $imgContent = Http::get($linkImagem['href'])->body();
                $base64 = base64_encode($imgContent);
            } catch (\Exception $e) {
                // Ignore download error, display copia e cola only
                Log::error("Erro ao baixar imagem do PagSeguro QRCode: " . $e->getMessage());
            }
        }

        return [
            'transaction_id' => $dados['id'], // order_id
            'qr_code' => $qrCodeInfo['text'],
            'qr_code_url' => $base64,
        ];
    }

    public function consultarStatus(string $transactionId): string
    {
        if (empty($this->token)) {
            return 'pendente';
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->get($this->baseUrl . '/orders/' . $transactionId);

        if ($response->failed()) {
            return 'pendente';
        }

        $data = $response->json();
        
        // PagSeguro armazena pagamentos no array de "charges" dentro do "order"
        $status = 'WAITING';
        if (isset($data['charges']) && count($data['charges']) > 0) {
            $status = strtoupper($data['charges'][0]['status'] ?? 'WAITING');
        }

        switch ($status) {
            case 'PAID':
            case 'AUTHORIZED':
                return 'pago';
            case 'CANCELED':
            case 'DECLINED':
            case 'EXPIRED':
                return 'cancelado';
            default:
                return 'pendente';
        }
    }

    public function gerarCartao(Inscricao $inscricao, array $dadosCartao, float $valor): array
    {
        throw new \Exception("Pagamento via cartão pelo PagSeguro ainda não implementado.");
    }
}
