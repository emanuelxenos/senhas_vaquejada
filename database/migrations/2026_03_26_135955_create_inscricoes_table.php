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
        Schema::create('inscricoes', function (Blueprint $table) {
            $table->id();            $table->foreignId('vaqueiro_id')->constrained('competidores')->cascadeOnDelete();
            $table->foreignId('bate_esteira_id')->constrained('competidores')->cascadeOnDelete();
            $table->string('forma_pagamento', 50);
            $table->decimal('valor_total', 10, 2);
            $table->enum('status_pagamento', ['pendente', 'pago', 'cancelado'])->default('pendente');            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inscricoes');
    }
};
