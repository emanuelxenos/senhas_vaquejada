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
        Schema::dropIfExists('vaqueiros');
    }

    public function down(): void
    {
        // Recriar a tabela vaqueiros se necessário para rollback
        Schema::create('vaqueiros', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 255);
            $table->string('representacao', 255);
            $table->string('esteira', 255);
            $table->string('pagamento', 50);
            $table->integer('quantidade')->default(0);
            $table->timestamp('data')->nullable();
            $table->enum('disponivel', ['sim', 'nao'])->default('sim');
        });
    }
};
