<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Usamos raw SQL pois alterar ENUM diretamente pelo Schema Builder pode exigir o doctrine/dbal e ter comportamentos inesperados
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'secretario', 'locutor', 'vaqueiro') DEFAULT 'vaqueiro'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tenta voltar ao anterior, mas não pode remover 'vaqueiro' se houver usuários com essa role.
        // Por segurança deixaremos o down genérico.
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'secretario', 'locutor') DEFAULT 'admin'");
    }
};
