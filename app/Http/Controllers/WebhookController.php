<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Inscricao;

class WebhookController extends Controller
{
    public function asaas(Request $request)
    {
        // Aqui recebemos a notificação do Asaas
        // O payload vem em $request->all()
        $event = $request->input('event');
        $paymentId = $request->input('payment.id');

        Log::info("Webhook Asaas Recebido: Evento {$event} para pagamento {$paymentId}");

        if ($event === 'PAYMENT_RECEIVED' || $event === 'PAYMENT_CONFIRMED') {
            $inscricao = Inscricao::where('gateway_transaction_id', $paymentId)->first();
            
            if ($inscricao) {
                $inscricao->update(['status_pagamento' => 'pago']);
                Log::info("Inscrição {$inscricao->id} atualizada para pago via Webhook.");
            }
        }

        return response()->json(['received' => true]);
    }
}
