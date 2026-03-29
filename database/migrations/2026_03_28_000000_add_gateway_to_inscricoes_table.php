<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('inscricoes', function (Blueprint $table) {
            $table->string('gateway_provider')->nullable()->after('status_pagamento')->comment('ex: asaas, pagseguro');
            $table->string('gateway_transaction_id')->nullable()->after('gateway_provider');
            $table->text('gateway_qr_code')->nullable()->after('gateway_transaction_id')->comment('Copia e cola do pix');
            $table->text('gateway_qr_code_url')->nullable()->after('gateway_qr_code')->comment('URL da imagem do QRCode');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inscricoes', function (Blueprint $table) {
            $table->dropColumn([
                'gateway_provider',
                'gateway_transaction_id',
                'gateway_qr_code',
                'gateway_qr_code_url',
            ]);
        });
    }
};
