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
            if (Schema::hasColumn('senhas', 'tipo')) {
                $table->dropColumn('tipo');
            }
            $table->boolean('is_boi_tv')->default(false)->after('numero_senha');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('senhas', function (Blueprint $table) {
            $table->dropColumn('is_boi_tv');
            $table->string('tipo', 50)->default('amador')->after('numero_senha');
        });
    }
};
