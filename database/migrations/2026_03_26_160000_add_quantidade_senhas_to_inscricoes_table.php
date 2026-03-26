<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inscricoes', function (Blueprint $table) {
            $table->unsignedInteger('quantidade_senhas')->default(1)->after('bate_esteira_id');
        });
    }

    public function down(): void
    {
        Schema::table('inscricoes', function (Blueprint $table) {
            $table->dropColumn('quantidade_senhas');
        });
    }
};

