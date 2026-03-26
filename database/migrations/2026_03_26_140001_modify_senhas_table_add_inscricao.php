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
        Schema::table('senhas', function (Blueprint $table) {
            // Remover a foreign key antiga
            $table->dropForeign(['vaqueiro_id']);
            $table->dropColumn('vaqueiro_id');
            
            // Adicionar nova estrutura
            $table->foreignId('inscricao_id')->constrained('inscricoes')->cascadeOnDelete();
            $table->enum('status', ['pendente', 'correu', 'boi_batido'])->default('pendente');
            
            // Renomear coluna numero para numero_senha para maior clareza
            $table->renameColumn('numero', 'numero_senha');
        });
    }

    public function down(): void
    {
        Schema::table('senhas', function (Blueprint $table) {
            // Reverter as mudanças
            $table->dropForeign(['inscricao_id']);
            $table->dropColumn(['inscricao_id', 'status']);
            
            // Recriar a estrutura antiga
            $table->foreignId('vaqueiro_id')->constrained('vaqueiros')->cascadeOnDelete();
            $table->renameColumn('numero_senha', 'numero');
        });
    }
};
