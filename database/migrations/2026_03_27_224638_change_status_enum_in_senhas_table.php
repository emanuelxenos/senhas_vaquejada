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
        DB::statement("ALTER TABLE senhas MODIFY COLUMN status ENUM('pendente', 'correu', 'boi_batido', 'cancelado') DEFAULT 'pendente'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE senhas MODIFY COLUMN status ENUM('pendente', 'correu', 'boi_batido') DEFAULT 'pendente'");
    }
};
