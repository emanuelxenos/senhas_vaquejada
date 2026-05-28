<?php

namespace App\Http\Controllers;

use App\Models\Competidor;
use App\Models\Inscricao;
use App\Models\Senha;
use Illuminate\Support\Facades\DB;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

class DashboardController extends Controller
{
    public function index()
    {
        $totalCompetidores = Competidor::count();
        $totalInscricoes = Inscricao::count();
        $totalSenhas = Senha::count();
        $totalFaturamento = Inscricao::where('status_pagamento', 'pago')->sum('valor_total');

        // Dados para gráfico: distribuição de pagamentos (por inscrição)
        $pagamentos = Inscricao::selectRaw('forma_pagamento, COUNT(*) as total')
            ->groupBy('forma_pagamento')
            ->pluck('total', 'forma_pagamento')
            ->toArray();

        // Dados para gráfico: senhas por categoria/tipo
        $rawCategorias = Senha::selectRaw('tipo, COUNT(*) as total')
            ->groupBy('tipo')
            ->pluck('total', 'tipo')
            ->toArray();

        $senhasPorCategoria = [];
        foreach ($rawCategorias as $tipo => $total) {
            $label = match($tipo) {
                'amador' => 'Amador',
                'profissional' => 'Profissional',
                'boi_tv' => 'Boi TV',
                default => ucfirst($tipo ?: 'Outros')
            };
            $senhasPorCategoria[$label] = $total;
        }

        // URL para celular (detecta IP local se estiver rodando em localhost)
        $localIp = '127.0.0.1';
        $ips = gethostbynamel(gethostname()) ?: [];
        
        // Prioriza IPs de rede local padrão (192.168.X.X ou 10.X.X.X)
        foreach ($ips as $ip) {
            if (str_starts_with($ip, '192.168.') || str_starts_with($ip, '10.')) {
                $localIp = $ip;
                break;
            }
        }
        
        // Se não encontrar o padrão local, usa o gethostbyname padrão
        if ($localIp === '127.0.0.1' && !empty($ips)) {
            $localIp = gethostbyname(gethostname());
        }

        $isLocal = in_array(request()->getHost(), ['localhost', '127.0.0.1']);
        $mobileUrl = $isLocal ? "http://{$localIp}:8000" : url('/');

        // Geração do QR Code direto no PHP (100% robusto, offline e independente de JS)
        $qrCodeSvg = '';
        try {
            $options = new QROptions([
                'version'      => 4,
                'addQuietzone' => false,
            ]);
            $qrCodeSvg = (new QRCode($options))->render($mobileUrl);
        } catch (\Exception $e) {
            // Fallback caso ocorra erro
            $qrCodeSvg = '<div class="text-danger small">Erro ao gerar QR Code</div>';
        }

        return view('dashboard', compact(
            'totalCompetidores',
            'totalInscricoes',
            'totalSenhas',
            'totalFaturamento',
            'pagamentos',
            'senhasPorCategoria',
            'qrCodeSvg',
            'mobileUrl'
        ));
    }
}
